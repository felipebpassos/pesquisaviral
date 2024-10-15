<?php

class Sessao
{

    public function validarToken($accessToken)
    {
        return true;
        
        // Endpoint de validação do token de acesso do Facebook
        $url = "https://graph.facebook.com/debug_token?input_token=$accessToken&access_token=EAAJog2OT3zQBO6qZCz1q5QR5ezZBbF1TF9Lfh8eQSzYZBHvmoC6AhIJtuswhoyR05g1E34MwbbWW8ZB381JsWwzAytc5j8sLWuHRoMfrYdxQo5ngkz1V8wqhINTXwFq8JLtq2sxdL3WK2NZC5FYh0s8LDHZBUM2zEOXlZB6S0ipHhMbPrqVG6OEZBgXB6ydAyNQJl4kBXD4x";

        // Faz uma solicitação para validar o token de acesso
        $response = file_get_contents($url);
        $responseData = json_decode($response, true);

        // Verifica se a resposta indica que o token é válido
        if (isset($responseData['data']['is_valid']) && $responseData['data']['is_valid'] === true) {
            return true; // O token é válido
        } else {
            return false; // O token é inválido
        }
    }

    public function verificaLogin()
    {
        if (isset($_SESSION['access_token'])) {
            header("Location: " . BASE_URL . "search");
            exit();
        }
    }

}
