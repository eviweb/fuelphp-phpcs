PHP_CodeSniffer Fuel PHP Standard
=================================
    
PHP Code Sniffer rules that cover a large part of the [Fuel PHP coding standards]
(http://docs.fuelphp.com/general/coding_standards.html "Fuel PHP Coding Standards").
    
[![Build Status](https://travis-ci.org/eviweb/fuelphp-phpcs.png?branch=master)](https://travis-ci.org/eviweb/fuelphp-phpcs)
    
Install (Linux Only)
-------------------- 
1. clone the project ``git clone https://github.com/eviweb/fuelphp-phpcs.git``    
2. change directory to ./fuelphp-phpcs ``cd fuelphp-phpcs``    
3. run installer with root privileges ``sudo ./install.sh``
    
Uninstall (Linux Only)
----------------------
1. change directory to ./fuelphp-phpcs ``cd fuelphp-phpcs``    
2. run uninstaller with root privileges ``sudo ./uninstall.sh``
    
How to use
----------
run ``phpcs --standard=FuelPHP PROJECT_TO_SNIFF_DIRECTORY`` where 
*PROJECT_TO_SNIFF_DIRECTORY* is your fuel php project directory.     
    
**BE AWARE not to use --tab-width phpcs option with another value than 0, 
this would disable tabs recognition !**    
