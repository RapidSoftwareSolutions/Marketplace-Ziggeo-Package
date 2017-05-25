<?php

$app->post('/api/Ziggeo/getVideos', function ($request, $response) {
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

    $url = $settings['apiUrl'] . "/videos/";

    $params = [];

    if (!empty($postData['args']['limit'])) {
        $params['limit'] = (int) $postData['args']['limit'];
    }
    if (!empty($postData['args']['skip'])) {
        $params['skip'] = (int) $postData['args']['skip'];
    }
    if (isset($postData['args']['reverse']) && strlen($postData['args']['reverse']) > 0) {
        $params['reverse'] = filter_var($postData['args']['reverse'], FILTER_VALIDATE_BOOLEAN);
    }
    if (!empty($postData['args']['states'])) {
        $params['states'] = $postData['args']['states'];
    }
    if (!empty($postData['args']['tags'])) {
        if (is_array($postData['args']['tags'])) {
            $params['tags'] = implode(',', $postData['args']['tags']);
        }
        else {
            $params['tags'] = $postData['args']['tags'];
        }
    }

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->get($url, [
            "headers" => [
                "Authorization" => "Basic " . base64_encode($postData['args']['appToken'] . ":" . $postData['args']['appPrivateKey'])
            ],
            'query' => $params
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

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);
});