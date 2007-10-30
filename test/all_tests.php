<?php

# Copyright (c)  2007 - Marcus Lunzenauer <mlunzena@uos.de>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.


# set error reporting
error_reporting(E_ALL);

# load required files
require_once dirname(__FILE__) . '/../vendor/simpletest/unit_tester.php';
require_once dirname(__FILE__) . '/../vendor/simpletest/reporter.php';
require_once dirname(__FILE__) . '/../vendor/simpletest/collector.php';


# collect all tests
$all =& new GroupTest('All tests');

$lib =& new GroupTest('lib/classes tests');
$lib->collect(dirname(__FILE__).'/lib/classes',
              new SimplePatternCollector('/test.php$/'));
$all->addTestCase($lib);

# use text reporter if cli
if (sizeof($_SERVER['argv']))
  $all->run(new TextReporter());

# use html reporter if cgi
else
  $all->run(new HtmlReporter());
