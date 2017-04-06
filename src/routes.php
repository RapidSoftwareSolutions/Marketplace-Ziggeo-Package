<?php
$routes = [
    'getVideos',
    'getSingleVideo',
    'createVideo',
    'deleteVideo',
    'updateVideo',
    'getStreams',
    'getSingleStream',
    'createStream',
    'deleteStream',
    'createAuthtoken',
    'getAuthtoken',
    'updateAuthtoken',
    'deleteAuthtoken',
    'pushVideoToPushService',
    'pushStreamToPushService',
    'metadata'
];
foreach($routes as $file) {
    require __DIR__ . '/../src/routes/'.$file.'.php';
}

