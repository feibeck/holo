<?php
/**
 * HOLO
 *
 * @copyright Copyright (c) 2016 Florian Eibeck
 * @license   THE BEER-WARE LICENSE (Revision 42)
 *
 * "THE BEER-WARE LICENSE" (Revision 42):
 * Florian Eibeck wrote this software. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return.
 */

namespace Holo;

use GuzzleHttp\Client;

/**
 * Simple client for a volkszaehler.org middleware
 */
class VzApi
{

    /**
     * @var string
     */
    private $middlewareUrl;

    /**
     * VzApi constructor.
     *
     * @param string $middlewareUrl
     */
    public function __construct($middlewareUrl)
    {
        $this->middlewareUrl = $middlewareUrl;
    }

    /**
     * Sends data to a volkszaehler.org middleware
     *
     * @param $uuid
     * @param $value
     *
     * @return bool
     */
    public function sendData(
        $uuid,
        $value
    ) {
        $urlToMiddleWare = $this->middlewareUrl;
        $context = 'data';
        $format = 'json';
        $operation = 'add';
        $key = "value";

        $url = sprintf(
            "%s/%s/%s.%s?operation=%s&%s=%s",
            $urlToMiddleWare,
            $context,
            $uuid,
            $format,
            $operation,
            $key,
            $value
        );

        try {
            $client = new Client();
            $response = $client->get($url);
        } catch (\Exception $e) {
            return false;
        }

        return $response->getStatusCode() == 200;
    }

}
