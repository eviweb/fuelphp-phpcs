<?php

/* vim: set noexpandtab tabstop=8 shiftwidth=8 softtabstop=8: */
/**
 * The MIT License
 *
 * Copyright 2012 Eric VILLARD <dev@eviweb.fr>.
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
 * @package     phpcs
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright	(c) 2012 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 */

namespace evidev\fuelphp\phpcs\tests\helpers;

use \PHP_CodeSniffer_CLI;

/**
 * add some features to facilitate the testing process
 * 
 * @package     phpcs
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright	(c) 2012 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 */
final class Helper
{
    /**
     * codesniffer cli instance
     * 
     * @var PHP_CodeSniffer_CLI 
     */
    private $phpcs_cli;

    /**
     * main ruleset file path
     * 
     * @var string
     */
    private $ruleset;

    /**
     * directory path of resources
     * 
     * @var string
     */
    private $resources_path;

    /**
     * project root directory path
     * 
     * @var string 
     */
    private static $root_dir;

    /**
     * autoloader function
     * requires that pear path is in the include_path
     * 
     * @param string $class
     */
    public static function autoload($class)
    {
        if (!class_exists($class, false)) {
            $class = preg_replace(
                '/^(\w+_Sniffs_)/',
                'PHP_CodeSniffer_Standards_$1',
                str_replace('FuelPHP_', '', $class)
            );
            require_once preg_replace(
                '/[_\\\\]/',
                DIRECTORY_SEPARATOR,
                $class
            ) . '.php';
        }
    }

    /**
     * instance of self
     * 
     * @var evidev\fuelphp\phpcs\tests\helpers\Helper 
     */
    private static $instance;

    /**
     * initialization state
     * 
     * @var boolean
     */
    private static $initialized = false;

    /**
     * singleton
     * 
     * @return evidev\fuelphp\phpcs\tests\helpers\Helper
     */
    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * initialization
     * 
     * @param  string $ruleset	ruleset file path
     * @return \evidev\fuelphp\phpcs\tests\helpers\Helper
     */
    public function init($ruleset)
    {
        if (!static::$initialized) {
            static::$root_dir = dirname(dirname(__DIR__));
            set_include_path(
                get_include_path()
                . PATH_SEPARATOR
                . static::$root_dir
                . PATH_SEPARATOR
                . static::$root_dir
                . DIRECTORY_SEPARATOR
                . 'Standards'
                . DIRECTORY_SEPARATOR
                . 'FuelPHP'
                . PATH_SEPARATOR
                . static::$root_dir
                . DIRECTORY_SEPARATOR
                . 'tests'
                . DIRECTORY_SEPARATOR
                . 'resources'
                . DIRECTORY_SEPARATOR
                . 'codesniffer'
            );
            spl_autoload_register(get_class($this) . '::autoload');
            static::$initialized = true;
        }
        $this->phpcs_cli = new PHP_CodeSniffer_CLI();
        $this->phpcs_cli->checkRequirements();
        $this->ruleset = $ruleset;
        $this->resources_path = dirname(__DIR__)
            . DIRECTORY_SEPARATOR
            . 'resources'
            . DIRECTORY_SEPARATOR;
        return $this;
    }

    /**
     * read accessor
     * 
     * @see    $phpcs_cli
     * @return PHP_CodeSniffer_CLI
     */
    public function getPhpCsCli()
    {
        return $this->phpcs_cli;
    }

    /**
     * read accessor
     * 
     * @see    $ruleset
     * @return string
     */
    public function getMainRuleset()
    {
        return $this->ruleset;
    }

    /**
     * write accessor
     * 
     * @see    $ruleset
     * @param  string $ruleset	ruleset file path
     * @return \evidev\fuelphp\phpcs\tests\helpers\Helper
     */
    public function setMainRuleset($ruleset)
    {
        $this->ruleset = $ruleset;
        return $this->ruleset;
    }

    /**
     * run the sniff process
     * 
     * @param  mixed  $files		string or array : single file path or list of files
     * @param  string $ruleset	ruleset file path
     * @return array returns the list of errors
     */
    public function runPhpCsCli($files, $ruleset = '')
    {
        $values = $this->phpcs_cli->getDefaults();
        $values['files'] = $files;
        $values['standard'] = empty($ruleset) ? $this->ruleset : $ruleset;
        $values['tabWidth'] = 0;
        $values['encoding'] = 'utf-8';
        $values['reports'] = array('xml' => null);
        //
        $result = array(
            'xml' => '',
            'errors' => 0
        );
        ob_start();
        $result['errors'] = $this->phpcs_cli->process($values);
        $result['xml'] = simplexml_load_string(ob_get_contents());
        ob_end_clean();
        return $result;
    }
    
    /**
     * get the path of a test file
     * 
     * @param  string $file     file name with or without .php extension
     * @return string		returns the full path of the test file
     */
    public function getTestFile($file)
    {
        $file = preg_match('/\.php$/', $file) ? $file : $file.'.php';
        return $this->resources_path
            . 'testfiles'
            . DIRECTORY_SEPARATOR
            . $file;
    }

    /**
     * get the path of a test file containing errors
     * 
     * @param  string $fileid	the part of the file name before -error
     * @return string		returns the path of the test file
     */
    public function getErrorTestFile($fileid)
    {
        return $this->resources_path
            . 'testfiles'
            . DIRECTORY_SEPARATOR
            . $fileid
            . '-error.php';
    }

    /**
     * get the path of a well formed test file
     * 
     * @param  string $fileid	the part of the file name before -wellformed
     * @return string		returns the path of the test file
     */
    public function getWellFormedTestFile($fileid)
    {
        return $this->resources_path
            . 'testfiles'
            . DIRECTORY_SEPARATOR
            . $fileid
            . '-wellformed.php';
    }

    /**
     * get the path of a ruleset file
     * 
     * @param  string $fileid	the part of the file name before -ruleset
     * @return string		returns the path of the ruleset file
     */
    public function getTestRuleset($fileid)
    {
        return $this->resources_path
            . 'codesniffer'
            . DIRECTORY_SEPARATOR
            . $fileid
            . DIRECTORY_SEPARATOR
            . 'FuelPHP'
            . DIRECTORY_SEPARATOR
            . 'ruleset.xml';
    }

    /**
     * require a Sniff
     * 
     * @param  string $name		name of the Sniff file
     * @return void
     */
    public function requireSniff($name)
    {
        require_once 'Sniffs' . DIRECTORY_SEPARATOR . $name;
    }

    /**
     * get all sniff files
     *
     * @return array returns all sniff files
     */
    public function getAllSniffs()
    {
        return glob(
            static::$root_dir .
            DIRECTORY_SEPARATOR .
            'Standards' .
            DIRECTORY_SEPARATOR .
            'FuelPHP' .
            DIRECTORY_SEPARATOR .
            'Sniffs' .
            DIRECTORY_SEPARATOR .
            '*' .
            DIRECTORY_SEPARATOR .
            '*Sniff.php'
        );
    }
}
