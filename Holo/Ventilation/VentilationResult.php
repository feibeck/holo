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
 * Data from a ventilation system
 */
class VentilationResult
{

    public $airTemperatureOutsideIncoming;
    public $airTemperatureOutsideOutgoing;
    public $airTemperatureInsideIncoming;
    public $airTemperatureInsideOutgoing;
    public $settingLevel;
    public $settingPercent;
    public $revolutionSpeedIncomingFan;
    public $revolutionSpeedOutgoingFan;
    public $stateBypass;

}
