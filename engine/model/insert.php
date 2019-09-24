<?php
// fhola
include_once 'db.php';

class insert extends db{
    protected $query;
    
    public function iniparamvalues($data){
        $i = 1;
        $array_value = count($data);

        // init bind param where
        $bind_param_data_key = NULL;
        $bind_param_data_value = NULL;
        foreach($data as $key => $value){
            // check for last array element and append sql "and" keyword

            $query_append = ($i < $array_value) ? ', ' : '';
            $bind_param_data_key .= $key.$query_append;
            $bind_param_data_value .= ':'.$key.$query_append;
            $i++;
        }
        
        $cols_vals = "($bind_param_data_key) values($bind_param_data_value)";
        return $cols_vals;
    }
    
     public function cusBindparam($data){
        foreach($data as $key => &$value){
            $this->query->bindParam(':'.$key, $value);
        }
    }
    
    public function insertData($table_name, $data){
        $bind_param_data = $this->iniparamvalues($data);
        
        $sql = "INSERT INTO $table_name $bind_param_data";
        $this->query = $this->conn->prepare($sql);
        
        
        // bind param
        $this->cusBindparam($data);
        
        // execute
        $execute = $this->query->execute();
        try{
            if(!$execute){
                throw new Exception("An error occurred while inserting into db");
            return false;
            }
            else{
                return true;
            }
            $this->conn = NULL; 
        }
        catch(Exception $e){
            print 'Error: '.$e->getMessage();
        }
        
    }
    
    public function lastid(){
        return $this->conn->lastInsertId();
    }
}
// fhola