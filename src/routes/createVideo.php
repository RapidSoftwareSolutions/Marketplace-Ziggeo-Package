<?php

$app->post('/api/Ziggeo/createVideo', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['appToken', 'appPrivateKey', 'file']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/videos/";

    $file = fopen($postData['args']['file'], 'r');

    $formData[] = [
        "name" => "file",
        "contents" => $file
    ];
    if (!empty($postData['args']['minDuration'])) {
        $formData[] = [
            "name" => "min_duration",
            "contents" => $postData['args']['minDuration']
        ];
    }
    if (!empty($postData['args']['maxDuration'])) {
        $formData[] = [
            "name" => "max_duration",
            "contents" => $postData['args']['maxDuration']
        ];
    }
    if (!empty($postData['args']['tags'])) {
        if (is_array($postData['args']['tags'])) {
            $tags = implode(',', $postData['args']['tags']);
        }
        else {
            $tags = $postData['args']['tags'];
        }
        $formData[] = [
            "name" => "tags",
            "contents" => $tags
        ];
    }
    if (!empty($postData['args']['key'])) {
        $formData[] = [
            "name" => "key",
            "contents" => $postData['args']['key']
        ];
    }
    if (isset($postData['args']['volatile']) && strlen($postData['args']['volatile']) > 0) {
        $formData[] = [
            "name" => "volatile",
            "contents" => $postData['args']['volatile']
        ];
    }

    if ($file) {
        try {
            /** @var GuzzleHttp\Client $client */
            $client = $this->httpClient;
            $vendorResponse = $client->post($url, [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode($postData['args']['appToken'] . ":" . $postData['args']['appPrivateKey'])
                ],
                'multipart' => $formData
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
    } else {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Check file';
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});