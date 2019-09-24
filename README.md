# fhola - A Simple PHP framework

### Configuring your project

#### config/config.php

```php
<?php
//directory routing
$dir = array(
            "user" => "user", 
            "admin" => "admin",
            "folder3" => NULL,
            "folder4" => NULL,
            "folder5" => NULL
            );

// define base urls 
$base_url_local = '/localhost/myproject_locally/';
$base_url = 'http://myonlineproject.com/';

// alternative base urls
$alt_base_url_local = NULL;
$alt_base_url = NULL;

// define root dir
$root_dir_local = 'myproject_locally';
$root_dir = 'myonlineproject.com';
?>
```
### More on the configuration - config.php


### config/sess.php

```php
$user_type = array(
                "default" => array(
                            // always set default "dir" to NULL unless you know what you're doing
                            "dir" => NULL,
                            "landing" => NULL,
                            "header" => "header",
                            "footer" => "footer",
                            "header_script" => FALSE
                            ),
                "1" => array(
                            "dir" => NULL,
                            "landing" => NULL,
                            "header" => NULL,
                            "footer" => NULL,
                            "header_script" => FALSE
                            ),
                "2" => array(
                            "dir" => NULL,
                            "landing" => NULL,
                            "header" => NULL,
                            "footer" => NULL,
                            "header_script" => FALSE
                            )
```
### More on sess.php


### Db Configuration

```php
// local testing
$lcl_db_host = 'localhost';
$lcl_db_name = 'test';
$lcl_db_username = 'root';
$lcl_db_password = NULL;
    
// online
$db_host = 'localhost';
$db_name = 'some_online_db_name';
$db_username = 'some_online_db_username';
$db_password = 'some_online_db_password';
```

### Creating a Controller in fhola
```php
<?php
Class MyHomePage extends fh_ctrl{
  function index(){
    global $data;
    
    $data['title'] = $this->title('My Home Page');
  }
}
```
### Dive into fhola custom controllers

Custom controllers are created in the 'root_dir/controllers' directory.

### Creating a View in fhola

Every view requires a controller in fhola with the same name as the view.
so if we have a view 'myhomepage' we need to also create a controller called 'MyHomePage'
views are created in the 'root_dir/view' directory.
