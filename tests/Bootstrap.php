<?php
/**
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/marc-mabe/EntityCoder/blob/master/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@marc-bennewitz.de so I can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2010-2011 Marc Bennewitz
 * @license    New BSD License
 */

/*
 * Set error reporting
 */
error_reporting( E_ALL | E_STRICT );

/*
 * Setup autoloading
 */
spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');
    $nsParts = explode('\\', $class);
    
    if ($nsParts[0] != 'EntityCoder') {
        return false;
    }
    
    if (substr($nsParts[0], -5) == 'Tests') {
        $dir = __DIR__;
    } else {
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'library';
    }
    
    return include_once($dir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $nsParts) . '.php');
}, true, true);
