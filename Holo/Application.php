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

use Holo\Command\Configurable;
use Holo\Command\InverterDataCommand;
use Holo\Command\VentilationDataCommand;
use Holo\Command\VentilationErrorResetCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\ConsoleEvents;

/**
 * Application class
 */
class Application extends SymfonyConsoleApplication
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(
            'HOLO', '@package_version@'
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(ConsoleEvents::COMMAND, array($this, 'configureCommand'));
        $this->setDispatcher($dispatcher);

        $this->add(new InverterDataCommand());
        $this->add(new VentilationDataCommand());
        $this->add(new VentilationErrorResetCommand());
    }

    /**
     * @param InputInterface|null  $input
     * @param OutputInterface|null $output
     *
     * @return int
     */
    public function doRun(
        InputInterface $input = null,
        OutputInterface $output = null
    ) {

        if (true === $input->hasParameterOption(array('--config', '-c'))) {
            $requestedConfigFile = $input->getParameterOption(
                array('--config', '-c')
            );
        } else {
            $requestedConfigFile = null;
        }

        $configFile = $this->getConfigFileLocation($requestedConfigFile);

        $statsConfig = Yaml::parse(
            file_get_contents($configFile)
        );

        $processor = new Processor();
        $this->config = $processor->processConfiguration(
            new Configuration(),
            array($statsConfig)
        );

        return parent::doRun(
            $input,
            $output
        );
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function configureCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        if ($command instanceof Configurable) {
            $command->setConfiguration($this->config);
        }
    }

    /**
     * Returns the default input definition.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help',    '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
            new InputOption('--config',  '-c', InputOption::VALUE_REQUIRED, 'Configuration file'),
        ));
    }

    /**
     * @param string $requestedConfigFile
     *
     * @return string
     */
    protected function getConfigFileLocation($requestedConfigFile)
    {
        if (!empty($requestedConfigFile)) {
            if ($requestedConfigFile[0] != '/') {
                $requestedConfigFile = __DIR__ . '/../' . $requestedConfigFile;
            }
            $configLocations = [$requestedConfigFile];
        } else {
            $configLocations = [
                __DIR__ . '/../holoconf.yml',
                '/etc/holoconf.yml',
                $_SERVER['HOME'] . '/.holoconf.yml',
            ];
        }

        foreach ($configLocations as $location) {
            if (file_exists($location)) {
                return $location;
            }
        }

        throw new \RuntimeException("No config file found");
    }

}
