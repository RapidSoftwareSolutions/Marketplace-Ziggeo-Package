<?php

$app->post('/api/Ziggeo/updateAuthtoken', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['appToken', 'appPrivateKey', 'authTokenId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/authtokens/" . $postData['args']['authTokenId'];

    if (isset($postData['args']['volatile']) && strlen($postData['args']['volatile']) > 0) {
        $formData[] = [
            'name' => 'volatile',
            'contents' => (int) filter_var($postData['args']['volatile'], FILTER_VALIDATE_BOOLEAN)
        ];
    }
    if (isset($postData['args']['hidden']) && strlen($postData['args']['hidden']) > 0) {
        $formData[] = [
            'name' => 'hidden',
            'contents' => $postData['args']['hidden']
        ];
    }
    if (isset($postData['args']['expirationDate']) && strlen($postData['args']['expirationDate']) > 0) {
        $formData[] = [
            'name' => 'expiration_date',
            'contents' => $postData['args']['expirationDate']
        ];
    }
    if (isset($postData['args']['usageExperitationTime']) && strlen($postData['args']['usageExperitationTime']) > 0) {
        $formData[] = [
            'name' => 'usage_experitation_time',
            'contents' => $postData['args']['usageExperitationTime']
        ];
    }
    if (!empty($postData['args']['sessionLimit'])) {
        $formData[] = [
            'name' => 'session_limit',
            'contents' => $postData['args']['sessionLimit']
        ];
    }
    if (!empty($postData['args']['grants'])) {
        $formData[] = [
            'name' => 'grants',
            'contents' => $postData['args']['grants']
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
        $result['contextWrites']['to']['status_msg'] = 'Your update call does not have any parameter. Atleast one parameter is required to update.';
    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});