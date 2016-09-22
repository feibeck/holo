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

namespace Holo\Command;

use Holo\VzApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base class for HOLO commands
 *
 * @package Holo\Command
 */
class HoloCommand extends Command implements Configurable
{

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $configurationKey;

    /**
     * @var VzApi
     */
    protected $api;

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    protected function isAvailable($key)
    {
        return isset($this->configuration[$this->configurationKey][$key]) && $this->configuration[$this->configurationKey][$key] != "";
    }

    /**
     * @param OutputInterface $output
     * @param string          $key
     * @param string          $value
     */
    protected function send(OutputInterface $output, $key, $value)
    {
        if (!$this->isAvailable($key)) {
            return;
        }
        $api = $this->getApi();
        if ($api->sendData($this->getFromConfig($key), $value)) {
            $output->writeln('<info>Written total to middleware</info>');
        } else {
            $output->writeln('<error>Error writing total to middleware</error>');
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getFromConfig($key)
    {
        return $this->configuration[$this->configurationKey][$key];
    }

    /**
     * @return VzApi
     */
    public function getApi()
    {
        if ($this->api == null) {
            $this->api = new VzApi($this->configuration['middleware']['url']);
        }
        return $this->api;
    }

}
