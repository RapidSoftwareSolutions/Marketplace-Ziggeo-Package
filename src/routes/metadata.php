<?php

$app->get('/api/Ziggeo', function ($request, $response, $args) {
    /** @var \Slim\Http\Response $response */
    $metaData = new \GuzzleHttp\Psr7\LazyOpenStream(__DIR__ . '/../../src/metadata/metadata.json', 'r');
    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withBody($metaData);
});
