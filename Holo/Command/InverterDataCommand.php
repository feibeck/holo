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

use Holo\Inverter\Kostal;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to fetch data from an inverter
 */
class InverterDataCommand extends HoloCommand
{

    /**
     * @var string
     */
    protected $configurationKey = 'inverter';

    protected function configure()
    {
        $this->setName('inverter:fetch')
             ->setDescription('Fetch data from inverter');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inverter = new Kostal(
            $this->getFromConfig('hostname'),
            $this->getFromConfig('username'),
            $this->getFromConfig('password')
        );
        $output->writeln(sprintf("Fetching from inverter at %s", $this->getFromConfig('hostname')));
        $data = $inverter->fetch();
        $output->writeln(sprintf("Current: %d W, Total: %d kWh", $data->current, $data->total));

        $this->send($output, 'uuid_current', $data->current);
        $this->send($output, 'uuid_total', $data->total);

        return null;
    }

}
