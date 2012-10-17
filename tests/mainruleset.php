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
		$test = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getErrorTestFile('closingtag')
		);		
		$this->assertEquals(1, $test['errors']);
		$source = $test['xml']->xpath('//error/@source');
		$this->assertEquals('Zend.Files.ClosingTag.NotAllowed', (string)$source[0]);
		//
		$test = Helper::instance()->runPHPCS_CLI(
			Helper::instance()->getWellFormedTestFile('closingtag')
		);		
		$this->assertEquals(0, $test['errors']);
	}
	
	

}