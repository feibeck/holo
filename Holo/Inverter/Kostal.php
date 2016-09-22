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

namespace Holo\Inverter;

use Goutte\Client;

/**
 * Data fetcher for a Kostal Piko 7.0
 *
 * @package Holo\Inverter
 */
class Kostal
{

    private $httpClient;
    private $username;
    private $password;
    private $host;

    /**
     * Kostal constructor.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct($host, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->host = $host;

        $this->httpClient = new Client();
    }

    /**
     * @return InverterResult
     */
    public function fetch()
    {
        $this->httpClient->setAuth($this->username, $this->password);
        $crawler = $this->httpClient->request('GET', sprintf("http://%s/index.fhtml", $this->host));

        $result = new InverterResult();

        $xpath = '//body/form/font/table[2]/tr[4]/td[3]';
        $crawler->filterXPath($xpath)->each(function ($node) use (&$result) {
            $result->current = (int) $node->text();
        });

        $xpath = '//body/form/font/table[2]/tr[4]/td[6]';
        $crawler->filterXPath($xpath)->each(function ($node) use (&$result) {
            $result->total = (int) $node->text();
        });

        return $result;
    }

}
