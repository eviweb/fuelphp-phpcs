#!/bin/bash
#
# The MIT License
#
# Copyright 2012 Eric VILLARD <dev@eviweb.fr>.
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
# THE SOFTWARE.

me=`whoami`

if [[ $me != "root" ]]
then
	echo 'Need root privileges, try running : sudo ./install.sh'
	exit 1
fi

DIR=$(cd `dirname $0` && pwd)
PEAR_DIR=`pear config-get php_dir`
SYMLINK="$PEAR_DIR/PHP/CodeSniffer/Standards/FuelPHP"

if [[ ! -h $SYMLINK ]]
then
	ln -s $DIR/Standards/FuelPHP $SYMLINK
fi

PHP_CMD=`which php`
if [[ -z  `$PHP_CMD -r "print(ini_get('include_path'));" | grep $PEAR_DIR` ]]
then
    echo "The PEAR installation directory : $PEAR_DIR, seems to be not referenced in the default PHP include path."
    echo "You should check your php.ini."
fi
