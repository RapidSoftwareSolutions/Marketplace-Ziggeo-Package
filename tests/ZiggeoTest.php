<?php

namespace Tests;

require_once(__DIR__ . '/../src/Models/checkRequest.php');

class ZiggeoTest extends BaseTestCase
{
    /**
     * @dataProvider dataProvider
     * @param $url
     */
    public function testRoutes($url)
    {
        $response = $this->runApp("POST", '/api/Ziggeo/' . $url);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function dataProvider()
    {
        $asd =  [
            ['getVideos'],
            ['getSingleVideo'],
            ['createVideo'],
            ['deleteVideo'],
            ['updateVideo'],
            ['getStreams'],
            ['getSingleStream'],
            ['createStream'],
            ['deleteStream'],
            ['createAuthtoken'],
            ['getAuthtoken'],
            ['updateAuthtoken'],
            ['deleteAuthtoken'],
            ['pushVideoToPushService'],
            ['pushStreamToPushService'],
            ['downloadSingleVideo'],
            ['downloadVideoThumbnail'],
            ['downloadStreamVideo'],
            ['downloadStreamThumbnail']
        ];
        return $asd;
    }
}