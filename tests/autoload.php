<?php
// Composer load
require_once __DIR__ . '/../vendor/autoload.php';
// Testing framework
require_once __DIR__ . '/framework/class.model.php';
require_once __DIR__ . '/framework/wpdb.php';
require_once __DIR__ . '/framework/wp.php';
// Init
$GLOBALS['wpdb'] = new WPDB;