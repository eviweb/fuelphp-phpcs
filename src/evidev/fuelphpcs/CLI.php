<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/**
 * The MIT License
 *
 * Copyright 2015 Eric VILLARD <dev@eviweb.fr>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package     evidev\fuelphpcs
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright   (c) 2015 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 */

namespace evidev\fuelphpcs;

use PHP_CodeSniffer;
use PHP_CodeSniffer_CLI;

/**
 * CLI
 *
 * @package     evidev\fuelphpcs
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright   (c) 2015 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 */
final class CLI extends PHP_CodeSniffer_CLI
{
    /**
     * constructor
     */
    private function __construct()
    {
        $this->values = $this->getCommandLineValues();
        $this->values['standard'] = StandardsProvider::create()->getRuleSet();
    }

    /**
     * static creator method
     *
     * @return CLI
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Processes a long (--example) command line argument.
     *
     * @param string $arg The command line argument.
     * @param int    $pos The position of the argument on the command line.
     *
     * @return void
     */
    public function processLongArgument($arg, $pos)
    {
        if ($arg === 'version') {
            $this->printVersion();
        } else {
            parent::processLongArgument($arg, $pos);
        }
    }

    /**
     * print version
     */
    private function printVersion()
    {
        echo 'FuelPHP CodeSniffer based on PHP_CodeSniffer version '.PHP_CodeSniffer::VERSION.' ('.PHP_CodeSniffer::STABILITY.') ';
        echo 'by Squiz (http://www.squiz.net)'.PHP_EOL;
        exit(0);
    }

    /**
     * Prints out the usage information for this script.
     *
     * @return void
     */
    public function printUsage()
    {
        if (defined('PHP_CODESNIFFER_CBF') && PHP_CODESNIFFER_CBF === true) {
            $this->printPHPCBFUsage();
        } else {
            $this->printPHPCSUsage();
        }
    }

    /**
     * Prints out the usage information for PHPCS.
     *
     * @return void
     */
    public function printPHPCSUsage()
    {
        ob_start();
        parent::printPHPCSUsage();
        $help = ob_get_contents();
        ob_end_clean();

        echo $this->fixHelp($help);
    }

    /**
     * Prints out a list of installed coding standards.
     *
     * @return void
     */
    public function printInstalledStandards()
    {
        echo 'Only FuelPHP standard is available.'.PHP_EOL;
    }

    /**
     * alter the PHP_CodeSniffer_CLI usage message to match FuelPHPCS
     *
     * @param  string $help PHP_CodeSniffer_CLI usage message to alter
     * @return string returns the usage message updated
     */
    private function fixHelp($help)
    {
        $help = $this->fixCLIName($help);
        $help = $this->fixStandard($help);

        return $help;
    }

    /**
     * fix the command name
     *
     * @param  string $help usage message to alter
     * @return string returns the usage message with fuelphpcs as command name
     */
    private function fixCLIName($help)
    {
        return str_replace('phpcs', 'fuelphpcs', $help);
    }

    /**
     * remove the standard option from the message as it is fixed
     *
     * @param  string $help usage message to alter
     * @return string returns the usage message without the standard option
     */
    private function fixStandard($help)
    {
        return str_replace('[--standard=<standard>] ', '', $help);
    }
}
