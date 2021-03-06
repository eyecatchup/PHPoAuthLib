<?php
/**
 * Bootstrap the library.
 *
 * PHP version 5.4
 *
 * @category   OAuth
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
namespace OAuth;

require_once __DIR__ . '/Common/AutoLoader.php';

$autoloader = new \OAuth\Common\AutoLoader(__NAMESPACE__, dirname(__DIR__));

$autoloader->register();