<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: TestHelper.php 20166 2010-01-09 19:00:17Z bkarwin $
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

/*
 * Set error reporting to the level to which Zend Framework code must comply.
 */
error_reporting( E_ALL | E_STRICT );

/*
 * Determine the root, library, and tests directories of the framework
 * distribution.
 */
$zfBranchRoot                = realpath(dirname(__FILE__) . '/../');
$zfBranchLibrary             = $zfBranchRoot . '/library';
$zfBranchTests               = $zfBranchRoot . '/tests';

$zfStandardIncubatorRoot     = realpath($zfBranchRoot . '/../../zf-incubator');
$zfStandardIncubatorLibrary  = $zfStandardIncubatorRoot . '/library';
$zfStandardIncubatorTests    = $zfStandardIncubatorRoot . '/tests';

$zfStandardTrunkRoot         = realpath($zfBranchRoot . '/../../zf-trunk');
$zfStandardTrunkLibrary      = $zfStandardTrunkRoot . '/library';
$zfStandardTrunkTests        = $zfStandardTrunkRoot . '/tests';

/*
 * Prepend the Zend Framework library/ and tests/ directories to the
 * include_path. This allows the tests to run out of the box and helps prevent
 * loading other copies of the framework code and tests that would supersede
 * this copy.
 */
$path = array(
    $zfBranchLibrary,
    $zfStandardIncubatorLibrary,
    $zfStandardIncubatorTests,
    $zfStandardTrunkLibrary,
    $zfStandardTrunkTests,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

/*
 * Add Zend Framework library/ directory to the PHPUnit code coverage
 * whitelist. This has the effect that only production code source files appear
 * in the code coverage report and that all production code source files, even
 * those that are not covered by a test yet, are processed.
 */
if (defined('TESTS_GENERATE_REPORT') && TESTS_GENERATE_REPORT === true &&
    version_compare(PHPUnit_Runner_Version::id(), '3.1.6', '>=')) {
    PHPUnit_Util_Filter::addDirectoryToWhitelist($zfStandardTrunkLibrary);
    PHPUnit_Util_Filter::addDirectoryToWhitelist($zfStandardIncubatorLibrary);
    PHPUnit_Util_Filter::addDirectoryToFilter(sys_get_temp_dir());
    PHPUnit_Util_Filter::addDirectoryToFilter(dirname(__FILE__));
}

/*
 * Unset global variables that are no longer needed.
 */
unset($zfStandardIncubatorRoot, $zfStandardIncubatorLibrary, $zfStandardIncubatorTests,
    $zfStandardTrunkRoot, $zfStandardTrunkLibrary, $zfStandardTrunkTests, $path);
