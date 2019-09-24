<?php
// fhola 
function class_loader($path){
    // get dir files
    $filedir = scandir($path);
    
    // load methods from URI upon REQUESTS   
    $url = inst('url_helper',"helper");
    
    $index = empty(INDEX_PAGE) ? 'welcome' : INDEX_PAGE;

    // define class name
    if(isset($url->trimmed_uri[$url->basename_key+1])){
        // check session
        if(LOADER_OPT['dir'] != NULL){
            // check for session
            if(isset($url->trimmed_uri[$url->basename_key+2])){
                $class_name = $url->trimmed_uri[$url->basename_key+2];
            }
            else{
                $class_name = LOADER_OPT['landing'];   
            }
        }
        else{
            $class_name = $url->trimmed_uri[$url->basename_key+1];
        }
    }
    else{
        $class_name = $index;
    }
    
    // check if class exists
    if(file_exists("$path/$class_name.php")){
          if(!isset($error404)){
            // load header script
            if(LOADER_OPT['header_script']){
                $header = LOADER_OPT['header'];
                // check if file exists
                if(!file_exists("$path/$header.php")){
                    throw new Exception("header script class not found");
                }
                
                include_once "controller/".$header.".php";  

                $header_script = new $header;
                $header_script->index();
            }   
        }
        // load requested class
        load("$path/$class_name.php");
        // instantiate class
        $obj = new $class_name;
        
        if($url->count_array > $url->basename_key){
            // check for query url
            if($_GET){
                if($url->count_array > $url->basename_key){
                    if(isset($_SESSION[USER_SESSION])){
                        // check if there is a user directory
                        if(LOADER_OPT['dir'] != NULL){
                            if(isset($url->trimmed_uri[$url->basename_key+3])){
                                $method_name = $url->trimmed_uri[$url->basename_key+3];
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                            else{
                                $method_name = "index";
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                        }
                        else{
                            if(isset($url->trimmed_uri[$url->basename_key+2])){
                                $method_name = $url->trimmed_uri[$url->basename_key+2];
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                            else{
                                $method_name = "index";
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                        }
                    }
                    else{
                        if(isset($url->trimmed_uri[$url->basename_key+2])){
                            $method_name = $url->trimmed_uri[$url->basename_key+2];
                            // check if method exists in the required class
                            if(!method_exists($obj, $method_name)){
                                throw new Exception("Controller handler not found");
                            }
                        }
                        else{
                            $method_name = "index";
                            // check if method exists in the required class
                            if(!method_exists($obj, $method_name)){
                                throw new Exception("Controller handler not found");
                            }
                        }
                    }
                }
                else{
                    $basename = basename($_SERVER['REQUEST_URI']);
                    $basename = explode("?", $basename);

                    $method_name = $basename[0];   
                }
            }
            else{
                 if(isset($_SESSION[USER_SESSION])){
                        // check if there is a user directory
                        if(LOADER_OPT['dir'] != NULL){
                            if(isset($url->trimmed_uri[$url->basename_key+3])){
                                $method_name = $url->trimmed_uri[$url->basename_key+3];
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                            else{
                                $method_name = "index";
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                        }
                        else{
                            if(isset($url->trimmed_uri[$url->basename_key+2])){
                                $method_name = $url->trimmed_uri[$url->basename_key+2];
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                            else{
                                $method_name = "index";
                                // check if method exists in the required class
                                if(!method_exists($obj, $method_name)){
                                    throw new Exception("Controller handler not found");
                                }
                            }
                        }
                }
                else{
                    if(isset($url->trimmed_uri[$url->basename_key+2])){
                        $method_name = $url->trimmed_uri[$url->basename_key+2];
                        // check if method exists in the required class
                        if(!method_exists($obj, $method_name)){
                            throw new Exception("Controller handler not found");
                        }
                    }
                    else{
                        $method_name = "index";
                        // check if method exists in the required class
                        if(!method_exists($obj, $method_name)){
                            throw new Exception("Controller handler not found");
                        }
                    }
                }
            }
            
            // call class method
            if(!method_exists($obj, "index")){
                throw new Exception("Index controller handler is missing");
            }
            //$obj->index();
            //$obj->$method_name();
        }
        else{
            $method_name = "index";
        }
        if($method_name != 'index'){
            $obj->index();
            $obj->$method_name();
        }
        else{
            $obj->index();
        }
        
    }
}

/*
get url values after actual page and filters out query text.
*returns result in array
e.g category/heroes?page=1 "returns" category/heroes (without the '?page=1');
*/
function getval(){
    $url = inst("url_helper","helper");
    
    $tmp = $url->suf_pages;
    $str = NULL;
    $fin = array();
    
    for($i=0; $i<= count($tmp)-1; $i++){
        $append = ($i < count($tmp)-1) ? ',' : NULL;
        if(strpos($tmp[$i], "?") !== FALSE){
            $perma = explode("?", $tmp[$i]); 
            $str .= $perma[0].$append;
        }
        else{
            $str .= $tmp[$i].$append;
        }
    }
    
    $fin = explode(",", $str);
    
    return $fin;
}

function move_files($old_path, $new_path){
    if(!move_uploaded_file($old_path, $new_path)){
        throw new Exception("Unable to move file");
    }
    else{
        return TRUE;
    }
}

// fhola