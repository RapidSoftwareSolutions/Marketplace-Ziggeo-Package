<?php

$app->post('/api/Ziggeo/pushStreamToPushService', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['appToken', 'appPrivateKey', 'videoId', 'streamId', 'serviceToken']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/videos/" . $postData['args']['videoId'] . "/streams/" . $postData['args']['streamId'] . "/push";
//    $z = new Ziggeo($postData['args']['appToken'], $postData['args']['appPrivateKey']);
//    $test = $z->streams()->push_to_service($postData['args']['videoId'], ['pushservicetoken' => $postData['args']['serviceToken']]);
//    /videos/646a4ddc27eb061fb6636f1fff21b43f/push

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->post($url, [
            "headers" => [
                "Authorization" => "Basic " . base64_encode($postData['args']['appToken'] . ":" . $postData['args']['appPrivateKey'])
            ],
            'multipart' => [
                [
                    "name" => "pushservicetoken",
                    "contents" => $postData['args']['serviceToken']
                ]
            ]
        ]);
        $vendorResponseBody = $vendorResponse->getBody()->getContents();
        if ($vendorResponse->getStatusCode() == 200) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = true;
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

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});