<?php

$app->post('/api/Ziggeo/createStream', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['appToken', 'appPrivateKey', 'videoId', 'file']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/videos/" . $postData['args']['videoId'] . "/streams";

    $file = fopen($postData['args']['file'], 'r');

    if ($file) {
        try {
            /** @var GuzzleHttp\Client $client */
            $client = $this->httpClient;
            $vendorResponse = $client->post($url, [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode($postData['args']['appToken'] . ":" . $postData['args']['appPrivateKey'])
                ],
                'multipart' => [
                    [
                        "name" => "file",
                        "contents" => $file
                    ]
                ]
            ]);
            $vendorResponseBody = $vendorResponse->getBody()->getContents();
            if ($vendorResponse->getStatusCode() == 201) {
                $result['callback'] = 'success';
                $result['contextWrites']['to'] = json_decode($vendorResponse->getBody());
            } else {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                $result['contextWrites']['to']['status_msg'] = is_array($vendorResponseBody) ? $vendorResponseBody : json_decode($vendorResponseBody);
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = json_decode($exception->getResponse()->getBody());

        }
    }
    else {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Check file format.';
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});