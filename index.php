<?php
// fhola 
ob_start();

session_start();

// include core library functions
include_once 'core/lib/fns.php';

// load custom scripts
load('custom/fns.php');

// include core libs
load('core/core.php');

ob_end_flush();

// fhola
?>