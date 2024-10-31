<?php

require_once 'Conexao.php';

class Usuarios
{

    private $con;
    private $collection;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
        $this->collection = $this->con->users; // Nome da coleção 'users'
    }

    // Verificar se o usuário já existe pelo e-mail
    public function usuarioExiste($email)
    {
        $usuario = $this->collection->findOne(['email' => $email]);
        return $usuario ? true : false;
    }

    // Adicionar um novo usuário
    public function setUsuario($nome, $email)
    {
        // Se o usuário não existir, adicionar ao banco de dados
        $usuario = [
            'nome' => $nome,
            'email' => $email,
            'plan' => [
                'type' => 'freemium',
                'start_date' => new MongoDB\BSON\UTCDateTime(), // Data atual em formato BSON
                'expiration_date' => null
            ],
            'monthly_search_count' => 0
            // Outros campos do usuário, se necessário
        ];
        $result = $this->collection->insertOne($usuario);
        return $result->getInsertedCount() ? true : false; // Retorna true se o usuário for adicionado com sucesso
    }

    // Obter os metadados do usuário e retornar JSON
    public function getUsuario($email)
    {
        $usuario = $this->collection->findOne(['email' => $email]);
        if ($usuario) {
            // Se o usuário for encontrado, retornar seus metadados como JSON
            return json_encode($usuario);
        } else {
            return false; // Usuário não encontrado
        }
    }

    public function saveSearchResult($user, $username, $searchResult)
    {
        // Verifica se o usuário existe
        $usuario = $this->collection->findOne(['email' => $user]);

        if ($usuario) {
            // Inicializa ou atualiza o campo 'recent_searches'
            $recentSearches = isset($usuario['recent_searches']) ? $usuario['recent_searches'] : [];

            // Substitui o ponto por um sublinhado no nome de usuário
            $usernameKey = str_replace('.', '_', $username);

            // Verifica se o perfil de usuário pesquisado já existe na lista de pesquisas recentes
            if (isset($recentSearches[$usernameKey])) {
                // Remove o perfil pesquisado existente
                $this->collection->updateOne(
                    ['email' => $user],
                    ['$unset' => ["recent_searches.$usernameKey" => ""]],
                    ['upsert' => true]
                );
            }

            // Remove a pesquisa mais antiga se houver mais de 5 pesquisas recentes
            if (count($recentSearches) >= 5) {
                $keys = array_keys((array)$recentSearches);
                $oldestKey = $keys[0];

                // Remove o elemento mais antigo
                $this->collection->updateOne(
                    ['email' => $user],
                    ['$unset' => ["recent_searches.$oldestKey" => ""]],
                    ['upsert' => true]
                );
            }

            // Define a data atual
            $currentDate = new MongoDB\BSON\UTCDateTime();

            $searchResult['saved_at'] = $currentDate;

            // Atualiza 'recent_searches' e 'last_search_date'
            $updateResult = $this->collection->updateOne(
                ['email' => $user],
                [
                    '$set' => [
                        "recent_searches.$usernameKey" => $searchResult,
                        "last_search_date" => $currentDate
                    ]
                ],
                ['upsert' => true]
            );

            return $updateResult->getModifiedCount() ? true : false;
        } else {
            // Se o usuário não for encontrado, retorna falso
            return false;
        }
    }

    public function removeOldSearches($email)
    {
        // Define a data limite para considerar como "há mais de 3 dias"
        $threeDaysAgo = new MongoDB\BSON\UTCDateTime((time() - 3 * 24 * 60 * 60) * 1000);

        // Encontra o usuário para inspecionar as pesquisas recentes
        $usuario = $this->collection->findOne(['email' => $email]);

        if ($usuario && isset($usuario['recent_searches'])) {
            $recentSearches = $usuario['recent_searches'];

            // Itera sobre as pesquisas para identificar as mais antigas
            foreach ($recentSearches as $username => $searchResult) {
                // Verifica se a pesquisa tem o campo saved_at e se é mais antigo que o limite de 3 dias
                if (isset($searchResult['saved_at']) && $searchResult['saved_at'] < $threeDaysAgo) {
                    // Remove as pesquisas antigas
                    $this->collection->updateOne(
                        ['email' => $email],
                        ['$unset' => ["recent_searches.$username" => ""]]
                    );
                }
            }
        }
    }

    public function getUserSearches($email)
    {
        // Chama o método para remover pesquisas antigas antes de buscar as pesquisas recentes
        $this->removeOldSearches($email);

        // Verifica se o usuário existe
        $usuario = $this->collection->findOne(['email' => $email]);

        if ($usuario) {
            // Verifica se existem pesquisas recentes para este usuário
            if (isset($usuario['recent_searches']) && count($usuario['recent_searches']) > 0) {
                // Converte os documentos BSON para JSON
                $jsonSearches = [];
                foreach ($usuario['recent_searches'] as $username => $searchResult) {
                    // Substitui o sublinhado por ponto no nome de usuário para saída
                    $outputUsername = str_replace('_', '.', $username);
                    $jsonSearches[$outputUsername] = json_encode($searchResult);
                }
                return $jsonSearches;
            } else {
                return []; // Retorna um array vazio se não houver pesquisas recentes para o usuário
            }
        } else {
            // Se o usuário não for encontrado, retorna falso
            return false;
        }
    }

    public function getSearchResult($email, $username)
    {
        // Substitui o ponto por um sublinhado no nome de usuário para entrada
        $username = str_replace('.', '_', $username);

        // Verifica se o usuário existe
        $usuario = $this->collection->findOne(['email' => $email]);

        if ($usuario && isset($usuario['recent_searches'][$username])) {
            // Retorna os resultados da pesquisa para o usuário e o nome de usuário específico
            return $usuario['recent_searches'][$username];
        } else {
            // Se o usuário não for encontrado ou a pesquisa não existir, retorna null ou um valor indicando a ausência de resultados
            return null;
        }
    }

    public function updatePlan($email, $tipoPlano, $dataExpiracao = null)
    {
        // Verifica se o usuário existe
        $usuario = $this->collection->findOne(['email' => $email]);

        if ($usuario) {
            // Define os novos dados do plano
            $novoPlano = [
                'type' => $tipoPlano,
                'start_date' => new MongoDB\BSON\UTCDateTime(), // Data e hora atual
                'expiration_date' => $dataExpiracao ? new MongoDB\BSON\UTCDateTime(strtotime($dataExpiracao) * 1000) : null // Converte a data de expiração se fornecida
            ];

            // Atualiza o plano do usuário no banco de dados
            $updateResult = $this->collection->updateOne(
                ['email' => $email],
                ['$set' => ['plan' => $novoPlano]]
            );

            // Retorna true se o plano foi atualizado com sucesso
            return $updateResult->getModifiedCount() ? true : false;
        } else {
            // Se o usuário não for encontrado, retorna false
            return false;
        }
    }

    public function incrementUserSearchCount($user)
    {
        // Verifica se o usuário existe
        $usuario = $this->collection->findOne(['email' => $user]);

        if ($usuario) {
            // Incrementa o contador de pesquisas mensais
            $updateResult = $this->collection->updateOne(
                ['email' => $user],
                ['$inc' => ['monthly_search_count' => 1]] // Incrementa o campo 'monthly_search_count' em 1
            );

            // Retorna true se o contador foi atualizado com sucesso
            return $updateResult->getModifiedCount() > 0;
        } else {
            // Se o usuário não for encontrado, retorna false
            return false;
        }
    }
}
