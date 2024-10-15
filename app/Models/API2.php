<?php

class API2
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
        $api_url = "https://graph.facebook.com/v3.2/{$this->user_id}?fields=business_discovery.username({$username}){id,profile_picture_url,followers_count,follows_count,media_count,media.limit(30){id,permalink,caption,media_type,media_url,thumbnail_url,comments_count,like_count}}&access_token={$this->access_token}";

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
}
