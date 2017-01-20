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

/**
 * Status from a ventilation system
 */
class VentilationStatus
{

    const INFO_EMPTY = '00000000';
    const INFO_FREEZE = '00000010';

    const ERROR_EMPTY = '00000000000000000000000000000000';
    const ERROR_VENTILATION = '00000000000000000000000000000010';

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $warning;

    /**
     * @var string
     */
    private $info;

    /**
     * VentilationStatus constructor.
     *
     * @param string $error
     * @param string $warning
     * @param string $info
     */
    public function __construct($error, $warning, $info)
    {
        $this->error = $error;
        $this->warning = $warning;
        $this->info = $info;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getWarning()
    {
        return $this->warning;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    public function hasNoInfo()
    {
        return $this->getInfo() == VentilationStatus::INFO_EMPTY;
    }

    public function hasErrorVentilation()
    {
        return $this->getError() == VentilationStatus::ERROR_VENTILATION;
    }

}
