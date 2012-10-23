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

namespace evidev\fuelphp\phpcs\tests\resources\testfiles;

/**
 * empty body
 * 
 * curly brackets should be on the same line
 */
class ClassDeclarationError_1
{
	
}

interface InterfaceDeclarationError_1
{
	
}

/**
 * opening curly brackets must be on a new line
 * both opening and closing curly brackets must be aligned with the declarative word
 */
class ClassDeclarationError_2 {
	public function __construct()
	{
		
	}
}

class ClassDeclarationError_3
	{
		public function __construct()
		{

		}
	}
	
class ClassDeclarationError_4
{
		public function __construct()
		{

		}
	}

interface InterfaceDeclarationError_2 {
	public function init();
}

interface InterfaceDeclarationError_3
	{
		public function init();
	}
	
interface InterfaceDeclarationError_4
{
		public function init();
	}
