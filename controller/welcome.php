<?php
class welcome extends fh_ctrl{   
    public function index(){
        global $data;
        
        $data['title'] = $this->title("Welcome To fhola");
        $data['welcome_text'] = "Welcome to Fhola v 0.002";
        $data['small_text'] = "coDe iN biTs";   
    }
    
    public function hello(){
        print 'hello';
    }
}