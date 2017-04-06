<?php

$app->post('/api/Ziggeo/createAuthtoken', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['appToken', 'appPrivateKey']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/authtokens/";

    $json = [];
    if (!empty($postData['args']['volatile'])) {
        $json['volatile'] = $postData['args']['volatile'];
    }
    if (isset($postData['args']['hidden']) && strlen($postData['args']['hidden']) > 0) {
        $json['hidden'] = $postData['args']['hidden'];
    }
    if (isset($postData['args']['expirationDate']) && strlen($postData['args']['expirationDate']) > 0) {
        $json['expiration_date'] = $postData['args']['expirationDate'];
    }
    if (isset($postData['args']['usageExperitationTime']) && strlen($postData['args']['usageExperitationTime']) > 0) {
        $json['usage_experitation_time'] = $postData['args']['usageExperitationTime'];
    }
    if (!empty($postData['args']['sessionLimit'])) {
        $json['session_limit'] = $postData['args']['sessionLimit'];
    }
    if (!empty($postData['args']['grants'])) {
        $json['grants'] = $postData['args']['grants'];
    }

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->post($url, [
            "headers" => [
                "Authorization" => "Basic " . base64_encode($postData['args']['appToken'] . ":" . $postData['args']['appPrivateKey'])
            ],
            'json' => $json
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

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});