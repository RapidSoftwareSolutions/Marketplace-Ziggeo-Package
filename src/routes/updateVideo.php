<?php

$app->post('/api/Ziggeo/updateVideo', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['appToken', 'appPrivateKey', 'videoId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/videos/" . $postData['args']['videoId'];

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
        $formData[] = [
            "name" => "tags",
            "contents" => $postData['args']['tags']
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
            "contents" => (int) filter_var($postData['args']['volatile'], FILTER_VALIDATE_BOOLEAN)
        ];
    }
    if (isset($postData['args']['expirationDays']) && strlen($postData['args']['expirationDays']) > 0) {
        $formData[] = [
            "name" => "expiration_days",
            "contents" => $postData['args']['expirationDays']
        ];
    }


    if (!empty($formData)) {
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
            if ($vendorResponse->getStatusCode() == 200) {
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
        $result['contextWrites']['to']['status_code'] = 'REQUIRED_FIELDS';
        $result['contextWrites']['to']['status_msg'] = 'Your update call does not have any parameter. Atleast one parameter is required.';
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});