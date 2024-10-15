<?php

require_once 'Conexao.php';

class Searches
{

    private $con;
    private $collection;

    public function __construct()
    {
        $this->con = Conexao::getConexao();
        $this->collection = $this->con->searches; // Nome da coleção 'searches'
    }

    public function registerSearch($username, $profilePictureUrl)
    {
        // Verifica se o perfil de usuário já existe na coleção de pesquisas
        $existingSearch = $this->collection->findOne(['username' => $username]);

        if ($existingSearch) {
            // Se o perfil de usuário já existe, incrementa o contador e atualiza o campo profile_picture_url
            $this->collection->updateOne(
                ['_id' => $existingSearch['_id']],
                ['$inc' => ['count' => 1], '$set' => ['profile_picture_url' => $profilePictureUrl]]
            );
        } else {
            // Se o perfil de usuário não existe, insere um novo documento
            $this->collection->insertOne([
                'username' => $username,
                'count' => 1,
                'profile_picture_url' => $profilePictureUrl
            ]);
        }
    }

    public function getTopSearches($limit = 7)
    {
        // Ordena os documentos por count em ordem decrescente e limita o resultado
        $topSearches = $this->collection->find([], ['sort' => ['count' => -1], 'limit' => $limit]);

        // Converte o cursor em um array
        $result = iterator_to_array($topSearches);

        return $result;
    }
}
