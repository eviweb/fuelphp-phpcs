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

/**
 * FuelPhpCs
 *
 * @package     evidev\fuelphpcs
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright   (c) 2015 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 */
class fuelphpcs extends \PHPUnit_Framework_TestCase
{
    public function testRunFuelPHPCs()
    {
        $file = __DIR__.'/resources/testfiles/tabindent-wellformed.php';

        $this->assertStatus(0, $this->runCLI($file));
    }

    public function testFuelPHPStandardsAreEnforced()
    {
        $file = __DIR__.'/resources/testfiles/tabindent-wellformed.php';
        $standards = '--standard=PSR2';

        $this->assertStatus(0, $this->runCLI($standards.' '.$file));
    }

    public function testFuelPHPCsVersion()
    {
        $result = $this->runCLI('--version');

        $this->assertContains('FuelPHP CodeSniffer based on PHP_CodeSniffer version', $result['output'][0]);
    }

    public function testFuelPHPCsHelp()
    {
        $result = $this->runCLI('--help');

        $this->assertOutputContains('fuelphpcs', $result);
        $this->assertOutputNotContains('--standard', $result);
    }

    public function testFuelPHPCsListStandardOption()
    {
        $this->assertOutputContains('Only FuelPHP standard is available', $this->runCLI('-i'));
    }

    /**
     * assert the status code of a run command
     *
     * @param integer $expected the expected status code
     * @param array   $result   the result array of a run command
     * @param string  $message  message to display in case of error
     */
    private function assertStatus($expected, array $result, $message = null)
    {
        $this->assertEquals($expected, $result['status'], $message);
    }

    /**
     * assert the result of an execution of a command contains a given string
     *
     * @param string $expected the expected string
     * @param array  $result   the result array of a run command
     * @param string $message  message to display in case of error
     */
    private function assertOutputContains($expected, array $result, $message = null)
    {
        $this->assertTrue($this->arrayContains($expected, $result['output']), $message);
    }

    /**
     * assert the result of an execution of a command does not contain a given string
     *
     * @param string $needle  the string to check
     * @param array  $result  the result array of a run command
     * @param string $message message to display in case of error
     */
    private function assertOutputNotContains($needle, array $result, $message = null)
    {
        $this->assertFalse($this->arrayContains($needle, $result['output']), $message);
    }

    /**
     * check if an array contains a given string
     *
     * @param  string  $needle   string to look for
     * @param  array   $haystack array to look in
     * @return boolean returns true if $needle is found or false
     */
    private function arrayContains($needle, array $haystack)
    {
        $found = false;
        for ($i = 0, $l = count($haystack); $i < $l && !$found; $i++) {
            $found = strpos($haystack[$i], $needle) !== false;
        }

        return $found;
    }

    /**
     * execute the fuelphpcs command
     *
     * @param  string $args string of command arguments
     * @return array  returns an associative array:
     *                     - status: integer, the status code of the command
     *                     - output: array, the ouptut array of the command
     * @see http://php.net/manual/en/function.exec.php for the output description
     */
    private function runCLI($args = '')
    {
        $script = realpath(__DIR__.'/../bin/fuelphpcs');
        $output = array();

        $this->assertFileExists($script, "Script to be executed should be found: $script");
        $this->assertTrue(is_executable($script), "Script must have executable permissions: $script");

        $args = implode(' ', $this->escapeShellArgs(explode(' ', $args)));

        exec($script.' '.$args, $output, $status);

        return array(
            'status' => $status,
            'output' => $output
        );
    }

    /**
     * escape shell args
     *
     * @param  array $args list of shell arguments
     * @return array returns the list of escaped shell arguments
     */
    private function escapeShellArgs($args = array())
    {
        foreach ($args as $key => $arg) {
            $args[$key] = escapeshellarg($arg);
        }

        return $args;
    }
}
