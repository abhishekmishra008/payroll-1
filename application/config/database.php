<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

//SERVER DATABASE
$db['default'] = array(
    'dsn' => '',
    'hostname' => '127.0.0.1',
    'username' => 'root',
    'password' => '',
    'database' => 'payroll',
    'dbdriver' => 'mysqli',
    'port'     => 3307, 
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);


$active_group1 = 'db2';
$query_builder = TRUE;
$db['db2'] = array(
    'dsn' => '',
    'hostname' => '127.0.0.1',
    'username' => 'root',
    'password' => '',
    'database' => 'rmt',
    'dbdriver' => 'mysqli',
    'port'     => 3307, 
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE,
);

