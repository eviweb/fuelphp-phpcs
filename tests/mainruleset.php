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

namespace evidev\fuelphp\phpcs\tests;

use \evidev\fuelphp\phpcs\tests\helpers\Helper;

/**
 * Main ruleset test class
 * 
 * @package     phpcs
 * @author      Eric VILLARD <dev@eviweb.fr>
 * @copyright	(c) 2012 Eric VILLARD <dev@eviweb.fr>
 * @license     http://opensource.org/licenses/MIT MIT License
 * @group	phpcs
 */
class MainRuleset extends \PHPUnit_Framework_TestCase
{
	/**
	 * set up the test environment
	 */
	public function setUp()
	{
		Helper::instance()->init(dirname(__DIR__).DIRECTORY_SEPARATOR.'ruleset.xml');
	}
	
	/**
	 * revert to initial state
	 */
	public function tearDown()
	{
		
	}

	/**
	 * check php closing tag
	 * 
	 * @coversNothing
	 */
	public function testClosingTag()
	{
		$ruleset = Helper::instance()->getTestRuleset('closingtag');
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getErrorTestFile('closingtag'),
			$ruleset
		);		
		$this->assertEquals(1, $test['errors']);
		$source  = $test['xml']->xpath('//error/@source');
		$this->assertEquals('Zend.Files.ClosingTag.NotAllowed', (string)$source[0]);
		//
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getWellFormedTestFile('closingtag'),
			$ruleset
		);		
		$this->assertEquals(0, $test['errors']);
	}
	
	/**
	 * check indentation is done using tabs only
	 * 
	 * @coversNothing
	 */
	public function testTabIndent()
	{
		$ruleset = Helper::instance()->getTestRuleset('tabindent');
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getErrorTestFile('tabindent'),
			$ruleset
		);
		$this->assertEquals(6, $test['errors']);
		$source  = $test['xml']->xpath('//error/@source');
		$this->assertEquals('FuelPHP.WhiteSpace.DisallowSpaceIndent.SpacesUsed', (string)$source[0]);
		//
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getWellFormedTestFile('tabindent'),
			$ruleset
		);
		$this->assertEquals(0, $test['errors']);
	}

	/**
	 * check filename is in lowercase
	 * 
	 * @coversNothing
	 */
	public function testFilenameInLowercase()
	{
		$ruleset = Helper::instance()->getTestRuleset('filename');
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getErrorTestFile('Filename'),
			$ruleset
		);
		$this->assertEquals(1, $test['errors']);
		$source  = $test['xml']->xpath('//error/@source');
		$this->assertEquals('FuelPHP.NamingConventions.LowerCaseFileName.UpperCaseInFileName', (string)$source[0]);
		//
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getWellFormedTestFile('filename'),
			$ruleset
		);
		$this->assertEquals(0, $test['errors']);
	}
	
	/**
	 * check function names are in lowercase, use underscores and their visibility is set
	 * 
	 * @coversNothing
	 */
	public function testLowercaseUnderscoreFunctionName()
	{
		$ruleset  = Helper::instance()->getTestRuleset('functionname');
		$test     = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getErrorTestFile('functionname'),
			$ruleset
		);
		$php54    = version_compare(PHP_VERSION, '5.4.0', '>=');
		$errors   = $php54 ? 11 : 8;
		$this->assertEquals($errors, $test['errors']);
		$sources  = $test['xml']->xpath('//error/@source');
		$expected = array(
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.VisibilityScope',
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.ScopeNotUnderscore',
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.ScopeNotUnderscore',
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.VisibilityScope',
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.ScopeNotUnderscore',
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.ScopeNotUnderscore',
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.NotUnderscore',
		    'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.NotUnderscore',
		);
		// if trait supported
		if ($php54) {
			array_push(
				$expected, 
				'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.VisibilityScope',
				'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.ScopeNotUnderscore',
				'FuelPHP.NamingConventions.UnderscoredWithScopeFunctionName.ScopeNotUnderscore'
			);
		}
		
		$i = 0;
		foreach($sources as $source)
		{
			$this->assertEquals($expected[$i], (string)$source[0]);
			$i++;
		}
		//
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getWellFormedTestFile('functionname'),
			$ruleset
		);
		$this->assertEquals(0, $test['errors']);
	}
	
	/**
	 * check variable names are concise and use underscore format
	 * 
	 * @coversNothing
	 */
	public function testConciseUnderscoredVariableName()
	{
		$ruleset  = Helper::instance()->getTestRuleset('variablename');
		$test     = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getErrorTestFile('variablename'),
			$ruleset
		);
		$php54    = version_compare(PHP_VERSION, '5.4.0', '>=');
		$errors   = $php54 ? 6 : 4;
		$warnings = $php54 ? 3 : 2;
		$this->assertEquals($errors+$warnings, $test['errors']);
		$expected_errors = array(
		    'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.NotUnderscore',
		    'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.NotUnderscore',
		    'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.NotUnderscore',
		    'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.NotUnderscore',
		);
		$expected_warnings = array(
		    'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.VariableNameTooLong',
		    'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.VariableNameTooLong',		    
		);
		// if trait supported
		if ($php54) {
			array_push(
				$expected_errors, 
				'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.NotUnderscore',
				'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.NotUnderscore'
			);
			array_push(
				$expected_warnings, 
				'FuelPHP.NamingConventions.ConciseUnderscoredVariableName.VariableNameTooLong'
			);
		}
		// loop on errors
		$sources = $test['xml']->xpath('//error/@source');
		$i       = 0;
		foreach($sources as $source)
		{
			$this->assertEquals($expected_errors[$i], (string)$source[0]);
			$i++;
		}
		// loop on warnings
		$sources = $test['xml']->xpath('//warning/@source');
		$i       = 0;
		foreach($sources as $source)
		{
			$this->assertEquals($expected_warnings[$i], (string)$source[0]);
			$i++;
		}
		//
		$test    = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getWellFormedTestFile('variablename'),
			$ruleset
		);
		$this->assertEquals(0, $test['errors']);
	}
}