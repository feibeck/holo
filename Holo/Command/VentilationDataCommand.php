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

use Holo\Ventilation\Helios;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command to fetch data from a ventilation system
 */
class VentilationDataCommand extends HoloCommand
{
    /**
     * @var string
     */
    protected $configurationKey = 'ventilation';

    protected function configure()
    {
        $this->setName('ventilation:fetch')
             ->setDescription('Fetch data from ventilation');
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
        $data = $ventilation->fetch();

        $output->writeln("Outside Air: " . $data->airTemperatureOutsideIncoming);
        $output->writeln("Incoming Air: " . $data->airTemperatureInsideIncoming);
        $output->writeln("Outgoing Air: " . $data->airTemperatureInsideOutgoing);
        $output->writeln("Exhaust Air: " . $data->airTemperatureOutsideOutgoing);
        $output->writeln("Bypass: " . $data->stateBypass);
        $output->writeln("Stage: " . $data->settingLevel);
        $output->writeln("Stage Percent: " . $data->settingPercent);
        $output->writeln("Revolution Incoming: " . $data->revolutionSpeedIncomingFan);
        $output->writeln("Revolution Outgoing: " . $data->revolutionSpeedOutgoingFan);

        $this->send($output, 'uuid_air_outside_incoming', $data->airTemperatureOutsideIncoming);
        $this->send($output, 'uuid_air_outside_outgoing', $data->airTemperatureOutsideOutgoing);
        $this->send($output, 'uuid_air_inside_incoming', $data->airTemperatureInsideIncoming);
        $this->send($output, 'uuid_air_inside_outgoing', $data->airTemperatureInsideOutgoing);
        $this->send($output, 'uuid_setting_level', $data->settingLevel);
        $this->send($output, 'uuid_setting_percent', $data->settingPercent);
        $this->send($output, 'uuid_revolution_speed_incoming', $data->revolutionSpeedIncomingFan);
        $this->send($output, 'uuid_revolution_speed_outgoing', $data->revolutionSpeedOutgoingFan);
        $this->send($output, 'uuid_state_bypass', $data->stateBypass);

        return null;
    }

}
