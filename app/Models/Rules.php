<?php

require __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Predis\Client as RedisClient;
use Dotenv\Dotenv;

class Rules
{

    private $plans;

    private $con;

    private $collection;

    private $redis;

    // Caminho do arquivo JSON com a lista de e-mails
    private $emailListPath = __DIR__ . '/../data/email_list.json';

    // Caminho do arquivo JSON com a lista de e-mails
    private $plansListPath = __DIR__ . '/../data/plans.json';

    public function __construct()
    {

        // Carregar variáveis de ambiente do arquivo .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $redisUrl = $_ENV['REDIS_URL'];

        $this->redis = new Predis\Client($redisUrl);

        $this->redis->connect();

        // Caminho para o arquivo plans.json
        $plansFile = __DIR__ . '/../data/plans.json';

        // Verifica se o arquivo existe e é legível
        if (file_exists($plansFile) && is_readable($plansFile)) {
            // Lê o conteúdo do arquivo JSON
            $jsonContent = file_get_contents($plansFile);

            // Decodifica o conteúdo JSON em um array associativo
            $this->plans = json_decode($jsonContent, true);
        } else {
            echo "Erro - C23";
            echo $plansFile;
            exit;
        }

        $this->con = Conexao::getConexao();
        $this->collection = $this->con->users;
    }

    public function __destruct()
    {
        $this->con = null;
    }

    public function getPlan($user)
    {
        // Chama o método para atualizar o last_reset_search_date e o monthly_search_count, se necessário
        $this->updateSearchResetDate($user);

        // Supondo que $user['plan']['type'] contenha o tipo do plano do usuário
        $userPlan = $user['plan']['type'];

        // Verifica se a decodificação foi bem-sucedida e se o plano do usuário existe
        if ($this->plans !== null && isset($userPlan) && array_key_exists($userPlan, $this->plans)) {
            // Atribui a coleção correspondente ao tipo de plano do usuário à variável $plan
            return $this->plans[$userPlan];
        } else {
            echo "Erro ao obter o plano do usuário.";
            exit;
        }
    }

    public function updateSearchResetDate($user)
    {
        // Verifica se o campo 'start_date' está presente
        if (!isset($user['plan']['start_date'])) {
            throw new Exception("Campo 'start_date' não está presente ou tem um formato incorreto.");
        }

        // Converte o BSON para DateTime
        $startDate = new MongoDB\BSON\UTCDateTime((int)$user['plan']['start_date']['$date']['$numberLong']);
        $startDateTime = $startDate->toDateTime();

        // Obtém a data atual (UTC)
        $currentDate = new DateTime('now', new DateTimeZone('UTC'));

        // Se não existir last_reset_search_date, ele será igual ao start_date
        if (!isset($user['last_reset_search_date'])) {
            // Debug: Verifique se o _id é um ObjectId válido
            if (!isset($user['_id']['$oid']) || !preg_match('/^[0-9a-f]{24}$/', $user['_id']['$oid'])) {
                throw new Exception("ID do usuário não é válido.");
            }

            // Atualiza o campo last_reset_search_date com o valor de start_date
            $this->collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($user['_id']['$oid'])], // Converte para ObjectId
                ['$set' => ['last_reset_search_date' => $startDate]]
            );

            return;
        }

        // Converte last_reset_search_date para DateTime
        if (isset($user['last_reset_search_date']['$date']['$numberLong'])) {
            $lastResetSearchDate = new MongoDB\BSON\UTCDateTime((int)$user['last_reset_search_date']['$date']['$numberLong']);
            $lastResetSearchDateTime = $lastResetSearchDate->toDateTime();
        } else {
            throw new Exception("Campo 'last_reset_search_date' não está presente ou tem um formato incorreto.");
        }

        // Comparações de data para determinar se o reset é necessário
        $startDay = (int)$startDateTime->format('d');
        $currentDay = (int)$currentDate->format('d');
        $currentMonth = (int)$currentDate->format('m');
        $currentYear = (int)$currentDate->format('Y');

        // Lógica para determinar o mês a ser usado na comparação
        $tempMonth = ($currentDay < $startDay) ? $currentMonth - 1 : $currentMonth;

        // Corrige o mês se for menor que 1
        if ($tempMonth < 1) {
            $tempMonth = 12;
            $currentYear--; // Decrementa o ano se voltarmos para dezembro
        }

        // Verifica se a data de reset precisa ser atualizada
        $comparisonDate = new DateTime("{$currentYear}-{$tempMonth}-{$startDay}", new DateTimeZone('UTC'));

        if ($lastResetSearchDateTime < $comparisonDate) {
            // Atualiza o last_reset_search_date e zera monthly_search_count
            $this->collection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($user['_id']['$oid'])], // Converte para ObjectId
                [
                    '$set' => [
                        'last_reset_search_date' => new MongoDB\BSON\UTCDateTime($currentDate->getTimestamp() * 1000), // Converte para BSON
                        'monthly_search_count' => 0 // Zera a contagem de pesquisas mensais
                    ]
                ]
            );
        }
    }

    // Função para verificar se o e-mail está na lista JSON
    public function verifyEmailInList($email)
    {
        // Verifica se o arquivo JSON existe
        if (!file_exists($this->emailListPath)) {
            return false;
        }

        // Carrega a lista de e-mails do arquivo JSON
        $emailList = json_decode(file_get_contents($this->emailListPath), true);

        // Verifica se o e-mail está na lista
        if (in_array($email, $emailList)) {
            return true;
        }

        return false;
    }

    // Função para enviar o e-mail com código de verificação
    public function sendEmailWithCode($email)
    {
        // Gera um código aleatório de 8 caracteres
        $code = $this->generateRandomCode(8);

        // Assunto e mensagem do e-mail
        $subject = "Codigo de Verificacao";
        $message = "Olá, \n\nAqui está o seu código de verificação: $code.\n\nEsse código expira em 5 minutos.";

        // Cria uma instância do PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';           // Servidor SMTP do Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'felipebpassos@gmail.com'; // Seu e-mail
            $mail->Password = 'zfec dvgu ebnp khwf';            // Sua senha de e-mail ou senha de app
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configurações do e-mail
            $mail->setFrom('felipebpassos@gmail.com', 'Pesquisa Viral'); // Remetente
            $mail->addAddress($email);                                   // Destinatário

            // Conteúdo do e-mail
            $mail->isHTML(false);                                       // Formato do e-mail em texto simples
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Envia o e-mail
            if ($mail->send()) {
                // Se o e-mail foi enviado, salva o código no banco de dados
                if ($this->saveVerificationCode($email, $code)) {
                    return $code; // Retorna o código para uso posterior
                }
            } else {
                return false; // Erro ao enviar o e-mail
            }
        } catch (Exception $e) {
            // Log de erros (opcional)
            error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
            return false;
        }
    }

    // Função para salvar o código de verificação no Redis com expiração de 5 minutos (300 segundos)
    public function saveVerificationCode($email, $code)
    {
        try {
            // Usar o e-mail como chave e o código como valor
            // Expira em 300 segundos (5 minutos)
            $this->redis->setex('verification_code:' . $email, 300, $code);
            return true;
        } catch (Exception $e) {
            error_log("Erro ao salvar o código no Redis: {$e->getMessage()}");
            return false;
        }
    }

    // Função para verificar o código de verificação
    public function verifyVerificationCode($email, $code)
    {
        try {
            // Recupera o código armazenado no Redis
            $storedCode = $this->redis->get('verification_code:' . $email);

            // Verifica se o código armazenado existe e corresponde ao código fornecido
            if ($storedCode === $code) {
                // Se corresponder, o código é válido e pode ser considerado como verificado
                return true;
            } else {
                // Se não corresponder ou o código não existir, o código é inválido
                return false;
            }
        } catch (Exception $e) {
            error_log("Erro ao verificar o código no Redis: {$e->getMessage()}");
            return false;
        }
    }

    // Função privada para gerar um código aleatório de N caracteres
    private function generateRandomCode($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    // Função para verificar limite de pesquisa de um usuário
    public function verifySearchLimits($user)
    {
        // Verifica se o arquivo JSON com a lista de planos existe
        if (!file_exists($this->plansListPath)) {
            return false;
        }

        // Carrega a lista de planos do arquivo JSON
        $plansList = json_decode(file_get_contents($this->plansListPath), true);

        // Verifica se o usuário tem um plano associado e se o tipo de plano existe na lista de planos
        if (!isset($user['plan']['type']) || !isset($plansList[$user['plan']['type']])) {
            return false;
        }

        // Pega o tipo de plano do usuário
        $userPlan = $plansList[$user['plan']['type']];

        // Verifica se o plano tem um limite de buscas ("max_searches")
        if (!isset($userPlan['max_searches'])) {
            return false;
        }

        // Se o limite de buscas for "unlimited", o usuário pode continuar a fazer buscas
        if ($userPlan['max_searches'] === 'unlimited') {
            return true;
        }

        // Compara o número atual de buscas do usuário com o limite do plano
        if ($user['monthly_search_count'] < $userPlan['max_searches']) {
            return true; // O usuário ainda pode fazer mais buscas
        }

        // Se o número de buscas já atingiu ou ultrapassou o limite, retorna falso
        return false;
    }
}
