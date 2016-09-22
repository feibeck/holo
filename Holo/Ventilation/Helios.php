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

namespace Holo\Ventilation;

use Goutte\Client;

/**
 * Ventilation data fetcher for a Helios KWL EC 370W R
 */
class Helios
{

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string
     */
    private $password;

    /**
     * Helios constructor.
     *
     * @param string $hostname
     * @param string $password
     */
    public function __construct($hostname, $password)
    {
        $this->hostname = $hostname;
        $this->password = $password;
    }

    /**
     * @return VentilationResult
     */
    public function fetch()
    {
        $httpClient = new Client();

        $httpClient->request(
            'POST',
            sprintf('http://%s/info.htm', $this->hostname),
            [],
            [],
            [],
            sprintf('v00402=%s', $this->password)
        );

        $crawler = $httpClient->request(
            'POST',
            sprintf('http://%s/data/werte8.xml', $this->hostname),
            [],
            [],
            [],
            'xml=/data/werte8.xml'
        );

        $xml = $crawler->html();

        $result = new VentilationResult();
        $result->airTemperatureOutsideIncoming = $this->readValue('v00104', $xml);
        $result->airTemperatureOutsideOutgoing = $this->readValue('v00106', $xml);
        $result->airTemperatureInsideIncoming = $this->readValue('v00105', $xml);
        $result->airTemperatureInsideOutgoing = $this->readValue('v00107', $xml);
        $result->settingLevel = $this->readValue('v00102', $xml);
        $result->settingPercent = $this->readValue('v00103', $xml);
        $result->revolutionSpeedIncomingFan = $this->readValue('v00348', $xml);
        $result->revolutionSpeedOutgoingFan = $this->readValue('v00349', $xml);
        $result->stateBypass = $this->readValue('v02119', $xml);

        return $result;
    }

    /**
     * @param string $id
     * @param string $xml
     *
     * @return string
     */
    private function readValue($id, $xml)
    {
        $regex = sprintf("|<ID>%s</ID>\n<VA>(.*)</VA>|", $id);
        $matches = [];
        preg_match($regex, $xml, $matches);
        return $matches[1];
    }

}
