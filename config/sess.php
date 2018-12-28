<?php
// GOD IS LIGHT

// altar array elements to project elements
$user_type = array(
                "default" => array(
                            // always set default "dir" to NULL unless you know what you're doing
                            "dir" => NULL,
                            "landing" => NULL,
                            "header" => "header",
                            "footer" => "footer"
                            ),
                "1" => array(
                            "dir" => "",
                            "landing" => "",
                            "header" => "",
                            "footer" => ""
                            ),
                "2" => array(
                            "dir" => "",
                            "landing" => "",
                            "header" => "",
                            "footer" => ""
                            ),
                "3" => array(
                            "dir" => "",
                            "landing" => "",
                            "header" => "",
                            "footer" => ""
                            ),
    
                "4" => array(
                            "dir" => NULL,
                            "landing" => NULL,
                            "header" => NULL,
                            "footer" => NULL
                            ),
                "5" => array(
                            "dir" => NULL,
                            "landing" => NULL,
                            "header" => NULL,
                            "footer" => NULL
                            ),
            );

$log_sess_name = "user_graph";

// change value if project requires user sessions !important
define("USER_SESSION", $log_sess_name);

// define user graphs to expect
// array can be populated
$user_graph = array(
                "role" => NULL,
                "name" => NULL,
                "email" => NULL,
                "user_id" => NULL
            );

define("USER_GRAPH", $user_graph);


if(!isset($_SESSION[USER_SESSION])){
    define("LOADER_OPT", $user_type["default"]);
}
else{
    //check user type and directory
    $sess_user_type = $_SESSION[USER_SESSION]['role'];
    $user_type_dir = $user_type[$sess_user_type]['dir'];
    
    //check url
    $url = explode("/", $_SERVER['REQUEST_URI']);
    if(!in_array($user_type_dir, $url)){
        define("LOADER_OPT", $user_type['default']);
        define("LOADER_OPT_LOGGED", $user_type[$sess_user_type]);
    }
    else{
        define("LOADER_OPT", $user_type[$sess_user_type]);
    }
}

// GOD IS LIGHT