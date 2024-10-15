<?php

class API
{
    private $access_token;
    private $user_id;

    public function __construct($access_token, $user_id)
    {
        $this->access_token = $access_token;
        $this->user_id = $user_id;
    }

    public function getAccountData($username)
    {
        // Obtém as informações do perfil
        $profileInfo = $this->getProfileInfo($username);

        // Obtém todas as mídias com paginação
        $allMedia = $this->getAllMedia($username);

        // Adiciona as mídias ao array de informações do perfil
        if ($allMedia) {
            $profileInfo['media']['data'] = $allMedia;
        }

        return $profileInfo;
    }

    public function getProfileInfo($username)
    {
        $api_url = "https://graph.facebook.com/v18.0/{$this->user_id}?fields=business_discovery.username({$username}){id,profile_picture_url,followers_count,follows_count,media_count}&access_token={$this->access_token}";

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return ['error' => 'Erro na solicitação cURL: ' . curl_error($ch)];
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data['business_discovery'])) {
            return $data['business_discovery'];
        } else {
            return [];
        }
    }

    public function getAllMedia($username)
    {
        $allMedia = [];

        // Primeira chamada para obter um conjunto de mídia
        $mediaSet = $this->getMediaSet($username);
        //if (!$mediaSet) {
            //var_dump($allMedia);
            //exit;
        //}

        if ($mediaSet) {

            do {
                // Adiciona as mídias ao array
                $allMedia = array_merge($allMedia, $mediaSet['data']);

                // Verifica se há mais páginas disponíveis
                if (isset($mediaSet['paging']['cursors']['after'])) {
                    // Obtém o cursor para a próxima página
                    $afterCursor = $mediaSet['paging']['cursors']['after'];

                    // Obtém o próximo conjunto de mídia usando o cursor
                    $mediaSet = $this->getMediaSet($username, $afterCursor);
                    //if (!$mediaSet) {
                        //var_dump($allMedia);
                        //exit;
                    //}
                } else {
                    // Se não houver mais páginas, encerra o loop
                    break;
                }
            } while (isset($mediaSet['paging']['cursors']['after'])); // Continue enquanto houver mais páginas

            return $allMedia;

        } else {
            return false;
        }

    }

    public function getMediaSet($username, $afterCursor = null)
    {
        // Constrói a parte da consulta responsável pela paginação
        $pagination = $afterCursor ? ".after($afterCursor)" : "";

        $api_url = "https://graph.facebook.com/v18.0/{$this->user_id}?fields=business_discovery.username({$username}){media.limit(300)$pagination{id,permalink,caption,media_type,media_url,thumbnail_url,comments_count,like_count}}&access_token={$this->access_token}";

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return ['error' => 'Erro na solicitação cURL: ' . curl_error($ch)];
        }

        curl_close($ch);

        $data = json_decode($response, true);

        //if (!isset($data["business_discovery"]["media"])) {
           // print_r($data);
        //}

        if (isset($data["business_discovery"]["media"])) {
            return $data["business_discovery"]["media"];
        } else {
            return false;
        }
    }
}
