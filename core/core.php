<?php
// fhola

// check if haptic session
//Check to see if our "haptic_timer" session
if(isset($_SESSION['haptic_timer'])){
    $expire_time = time() - $_SESSION['haptic_timer'];
    if($expire_time > 3){
        unset($_SESSION['haptic_timer']);
        if(isset($_SESSION['haptic'])){
            unset($_SESSION['haptic']);
        }
    }
}
else{
    $_SESSION['haptic_timer'] = time();
}

global $routes;
$routes = array();

// load config files
autoLoad("config", "user_graph.php");

// load lib
autoLoad("core/lib", "fns.php");

// db helper
load("engine/helpers/db_helper");

// load loaders
load("engine/helpers/url_helper");
autoLoad("engine/loaders");

// load default engine controller
autoLoad("engine/controller");

// load controllers classes
try{
    class_loader("controller");
}
catch(Exception $e){
    htmlOut("warning", $e->getMessage(), "text-center");
    exit;
}

// view loader
$loader = new loader;

$loader->loader();

$loader->routes();

// fhola