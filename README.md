# HOLO

HOLO reads data from web-frontends of various devices in a home. It currently
supports inverters and ventilation systems.

Supported systems are:

* Inverter Kostal Piko 7.0
* Ventilation Helios KWL EC 370W R

Holo sends the data to a volkszaehler.org installation.

## Installation

Clone the repository and install dependencies with Composer. You need to have composer installed on your system. See https://getcomposer.org/doc/00-intro.md

    git clone https://github.com/feibeck/holo/holo.git
    cd holo
    composer install

## Configuration

Copy the holoconf.yml.example file to holoconf.yml and enter your parameters.

Either place the config file in the application root directory or in /etc. 
Alternative locations can be given on the command line with the -c option.

## Run

Fetch and send the inverter data with
    
    ./holo.php inverter:fetch

Fetch and send the ventilation data with

    ./holo.php ventilation:fetch

## License

"THE BEER-WARE LICENSE" (Revision 42):

Florian Eibeck wrote this software. As long as you retain this notice you
can do whatever you want with this stuff. If we meet some day, and you think
this stuff is worth it, you can buy me a beer in return.
