<?php
class news extends fhola_controller{   
    public function index(){
        global $data;
        
        $data['title'] = "This is the news page";
        $data['welcome_text'] = "Welcome to Fhola v 0.002";
        $data['small_text'] = "CoDe iN biTs";   
    }
    
    public function hello(){
        print 'hello';
    }
}