<?php
// fhola
include_once 'db.php';

class altar extends db{
    protected $query;
    
    protected function iniParamvalues($data, $sql_append_clause, $sql_clause=NULL){
        if($data == NULL){
            $return = NULL;
        }
        else{
            $i = 1;
            $array_value = count($data);

            // init bind param where
            $bind_param_data = '';
            foreach($data as $key => $value){
                // check for last array element and append sql "and" keyword

                $query_append = ($i == $array_value) ? NULL : " $sql_append_clause ";
                $bind_param_data .= $key. ' = :'.$key.$query_append;
                $i++;
            }

            $return = "$sql_clause $bind_param_data";
        }

        return $return;
    }
    
    protected function cusBindparam($data){
        if($data != NULL){
            foreach($data as $key => &$value){
                $this->query->bindParam(':'.$key, $value);
            }
        }
        else{
            return false;
        }
    }
    
    public function updateTable($table_name, $cols_vals, $where, $extra_set_column = NULL){
        // set cols and vals param values
        $set_param_cols_vals = $this->iniParamvalues($cols_vals, ",");
        // check for extra set_column
        $extra_set = ($extra_set_column !== NULL) ? $extra_set_column : NULL ;
        if($extra_set_column !== NULL){
            if($cols_vals !== NULL){
                $extra_set_column = ",$extra_set_column";  
            }
            else{
                $extra_set_column = $extra_set_column;  
            }   
        }
        // set where cols param values
        $set_param_where = $this->iniParamvalues($where, "and", "WHERE");

        $sql = "UPDATE $table_name SET $set_param_cols_vals $extra_set_column $set_param_where";

        // prepare sql statement
        $this->query = $this->conn->prepare($sql);

        // bind cols_vals param values
        $this->cusBindparam($cols_vals);

        // bind where param values
        $this->cusBindparam($where);

        // execute query
        $execute = $this->query->execute();

        if(!$execute){
            return false;
        }
        else{
            return true;
        }
    }
    
    
    public function deleteData($table_name, $where){
        // set where cols param values
        $set_param_where = $this->iniParamvalues($where, "and", "WHERE");

        $sql = "DELETE FROM $table_name $set_param_where";

        // prepare sql statement
        $this->query = $this->conn->prepare($sql);

        // bind param where values
        $this->cusBindparam($where);

        // execute query
        $execute = $this->query->execute();

        if(!$execute){
            return false;
        }
        else{
            return true;
        }
    }
}
// fhola