<?php
/**
 * HOLO
 *
 * @copyright Copyright (c) 2017 Florian Eibeck
 * @license   THE BEER-WARE LICENSE (Revision 42)
 *
 * "THE BEER-WARE LICENSE" (Revision 42):
 * Florian Eibeck wrote this software. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return.
 */

namespace Holo\Command;

use Holo\Ventilation\Helios;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to reset ventilation error
 */
class VentilationErrorResetCommand extends HoloCommand
{
    /**
     * @var string
     */
    protected $configurationKey = 'ventilation';

    protected function configure()
    {
        $this->setName('ventilation:error-reset')
            ->setDescription('Reset specific errors');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ventilation = new Helios(
            $this->getFromConfig('hostname'),
            $this->getFromConfig('password')
        );

        $status = $ventilation->fetchStatus();

        if ($status->hasNoInfo() && $status->hasErrorVentilation()) {
            $ventilation->resetErrors();
        }

        return null;
    }

}
