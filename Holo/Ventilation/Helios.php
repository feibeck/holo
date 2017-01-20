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
     * @var Client
     */
    private $httpClient;

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

        $this->httpClient = new Client();
    }

    /**
     * @return VentilationResult
     */
    public function fetch()
    {
        $this->login();

        $xml = $this->fetchXml(8);

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
     * @return VentilationStatus
     */
    public function fetchStatus()
    {
        $this->login();

        $xml = $this->fetchXml(16);

        return new VentilationStatus(
            $this->readValue('v01303', $xml),
            $this->readValue('v01304', $xml),
            $this->readValue('v01305', $xml)
        );
    }

    /**
     * Reset any errors to restart ventilation
     */
    public function resetErrors()
    {
        $this->login();

        $this->httpClient->request(
            'POST',
            sprintf('http://%s/fehl.htm', $this->hostname),
            [],
            [],
            [],
            'v01300=0&v01303=00000000000000000000000000000000&v01301=0&v01304=00000000&v01302=0&v01305=00000000&v02104=1&v01120=1&v02105=1'
        );
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

    private function login()
    {
        $this->httpClient->request(
            'POST',
            sprintf('http://%s/info.htm', $this->hostname),
            [],
            [],
            [],
            sprintf('v00402=%s', $this->password)
        );
    }

    /**
     * @return string
     */
    private function fetchXml($pageNumber)
    {
        $path = sprintf('/data/werte%s.xml', $pageNumber);

        $crawler = $this->httpClient->request(
            'POST',
            sprintf('http://%s' . $path, $this->hostname),
            [],
            [],
            [],
            'xml=' . $path
        );

        return $crawler->html();
    }

}
