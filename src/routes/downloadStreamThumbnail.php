<?php

$app->post('/api/Ziggeo/downloadStreamThumbnail', function ($request, $response) {
    /** @var \Slim\Http\Response $response */
    /** @var \Slim\Http\Request $request */
    /** @var \Models\checkRequest $checkRequest */

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['appToken', 'appPrivateKey', 'videoId', 'streamId']);
    if (!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback'] == 'error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $postData = $validateRes;
    }

    $url = $settings['apiUrl'] . "/videos/" . $postData['args']['videoId'] . "/streams/" . $postData['args']['streamId'] . "/image";

    try {
        /** @var GuzzleHttp\Client $client */
        $client = $this->httpClient;
        $vendorResponse = $client->get($url, [
            "headers" => [
                "Authorization" => "Basic " . base64_encode($postData['args']['appToken'] . ":" . $postData['args']['appPrivateKey'])
            ]
        ]);
        $vendorResponseBody = $vendorResponse->getBody()->getContents();
        if ($vendorResponse->getStatusCode() == 200) {
            $size = $vendorResponse->getHeader('Content-Length')[0];
            $contentDisposition = $vendorResponse->getHeader('Content-Disposition')[0];
            $contentDisposition = str_replace("attachment", "", $contentDisposition);
            $contentDisposition = str_replace('filename=', "", $contentDisposition);
            $contentDisposition = str_replace(';', "", $contentDisposition);
            $uploadServiceResponse = $client->post($settings['uploadServiceUrl'], [
                'multipart' => [
                    [
                        "name" => "file",
                        "filename" => trim($contentDisposition),
                        "contents" => $vendorResponseBody
                    ],
                    [
                        'name' => 'length',
                        'contents' => $size
                    ]
                ]
            ]);
            $uploadServiceResponseBody = $uploadServiceResponse->getBody()->getContents();
            if ($uploadServiceResponse->getStatusCode() == 200) {
                $result['callback'] = 'success';
                $result['contextWrites']['to'] = json_decode($uploadServiceResponse->getBody());
            }
            else {
                $result['callback'] = 'error';
                $result['contextWrites']['to']['status_code'] = 'API_ERROR';
                $result['contextWrites']['to']['status_msg'] = is_array($uploadServiceResponseBody) ? $uploadServiceResponseBody : json_decode($uploadServiceResponseBody);
            }
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