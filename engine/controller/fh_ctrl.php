<?php
// LIGHT 
class fh_ctrl{
    protected $data = array(),
                $title;
    
    protected function title($title_data){
        if(!isset($title_data)){
            throw new Exception("Too few arguments for title");
        }
        return (!empty($this->title)) ? $this->title : $title_data;
    }
    
    protected function createUser_graph($string){
        global $user_graph;
        $i = 1;
        $count = count(USER_GRAPH);
        $keys_col = NULL;

        foreach(USER_GRAPH as $key => $value){
            // get keys
            $append = ($i < $count) ? ',' : NULL;
            $keys_col .= $key.$append;
            $i++;
        }

        $keys = createStr_array($keys_col);
        $new_keys = createStr_array($string);

        $user_graph = array_combine($keys, $new_keys);
        
        load("config/user_graph");

        if(!$user_graph){
            throw new Exception("Could not create user graph");
        }

        return $user_graph;
    }
    
    protected function sess($sess_name,$sess_value){
        $_SESSION[$sess_name] = $sess_value;
    }
    
    protected function remove_sess($sess_name,$sess_value=NULL){
        if($sess_value !== NULL){
            unset($_SESSION[$sess_name][$sess_value]);   
        }
        else{
            unset($_SESSION[$sess_name]);
        }
    }
    
    protected function update_sess($sess_name,$value,$key=NULL){
        if($key !== NULL){
            $_SESSION[$sess_name][$key] = $value;
        }
        else{
            $_SESSION[$sess_name] = $value;  
        }
    }
    
    protected function user_session($sess_value){
        $_SESSION[USER_SESSION] = $sess_value;      
    }
    
    protected function session_value($value){
        return $_SESSION[USER_SESSION]["$value"];
    }
    
    protected function unset_user_session(){
        unset($_SESSION[USER_SESSION]);
    }
    
    // form fields controllers
    protected function check_fields($data,$err_msg = NULL){
        // check for empty field
        if(isempty($data)){
            if($err_msg == NULL){
                $err_msg = "Empty fields detected";
            }
            throw new Exception($err_msg);
        }
    }
    
    protected function post($name){
        if(isset($_POST["$name"])){
            return $_POST["$name"];
        }
        else{
            return FALSE;
        }
    }
    
    protected function get($name){
        if(isset($_GET["$name"])){
            return $_GET["$name"];
        }
        else{
            return FALSE;
        }
    }
    
    protected function check_email($email,$err_msg = NULL){
        // check email
        if(!check_email($email)){
            if($err_msg == NULL){
                $err_msg = "your e-mail is invalid";
            }
            throw new Exception($err_msg);
        }
    }
    
    protected function isint($field, $err_msg){
        // check if value is integer
        if(!is_numeric($field)){
            throw new Exception($err_msg);
        }
    }
    
    protected function confirm_pass($pass1, $pass2){
        // check if password match
        if($pass1 != $pass2){
            throw new Exception("We detected a password mismatch");
        }
    }
    // end form controllers
    
    // cart controllers
    protected function addTocart($product_id, $duplicate = TRUE){

        // check if product has been added to cart
        if(USE_SHOPPING_CART == TRUE){
            // if product is not in array
            if(!isset($_SESSION[FHOLA_CART][$product_id])){
                $_SESSION[FHOLA_CART][$product_id] = 1;
                return true;
            }
            
            // check if cart product is duplicate
            if($duplicate == TRUE){
                // if product is in array
                if(isset($_SESSION[FHOLA_CART][$product_id])){
                    $_SESSION[FHOLA_CART][$product_id]++;
                    return true;
                }
            }
            else{
                throw new Exception("Item is already in cart");
            }
        }
        else{
            throw new Exception("Fhola shopping cart is disabled");
        }
    }
    
    public static function cart_counter(){
        if(USE_SHOPPING_CART == TRUE){
            $total_items = 0;
            foreach($_SESSION[FHOLA_CART] as $key => $value){
                $total_items += $value;
            }   
            return $total_items;  
        }
        else{
            throw new Exception("Fhola shopping cart is disabled");
        }
    }
    
    public function itemQty($item_id){
        if(USE_SHOPPING_CART == TRUE){
            if(isset($_SESSION[FHOLA_CART][$item_id])){
                return $_SESSION[FHOLA_CART][$item_id];    
            }
        }
        else{
            throw new Exception("Fhola shopping cart is disabled");
        }
    }
    
    public function cart_array(){
        if(USE_SHOPPING_CART == TRUE){
            return $_SESSION[FHOLA_CART];
        }
        else{
            throw new Exception("Fhola shopping cart is disabled");
        }
    }
    
    protected function remove_cart_item($item){
        if(USE_SHOPPING_CART == TRUE){
            // check if cart item
            if(isset($_SESSION[FHOLA_CART][$item])){
                unset($_SESSION[FHOLA_CART][$item]);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            throw new Exception("Fhola shopping cart is disabled");
        }
    }
    
    protected function addQty($product_id,$qty){
        if(USE_SHOPPING_CART == TRUE){
            if($qty <= 0){
                unset($_SESSION[FHOLA_CART][$product_id]);
            }
            else{
                $_SESSION[FHOLA_CART][$product_id] = $qty;   
            }
        }
        else{
            throw new Exception("Fhola shopping cart is disabled");
        }
    }
    
    protected function emptycart(){
        $_SESSION[FHOLA_CART] = array();
    }
    
    protected function ajax(){
        exit;
    }
    
    // end cart controllers
    
    protected function remote_request($param = array()){
        if(isset($param['url']) && isset($param['fields'])){
            if(empty($param['url']) || empty($param['fields'])){
                throw new Exception("remote_request contains empty parameter values");
            }   
        }
        else{
            throw new Exception("Incomplete parameter count for remove_request");
        }

        // build the urlencoded data
        $postvars = http_build_query($param['fields']);

        // open connection
        $ch = curl_init();

        // set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $param['url']);
        curl_setopt($ch, CURLOPT_POST, count($param['fields']));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);

        // execute post
        $result = curl_exec($ch);

        // close connection
        curl_close($ch);
    }
}
// LIGHT