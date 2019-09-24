<?php
// fhola

include_once('db.php');

class select extends db{
    public $result;
    protected $query;
    
    public function fetchResult(){
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }
    
    public function rowCount(){
        return $this->query->rowCount();
    }
    
    public function iniParamvalues($where){
        if($where == NULL){
            $return = NULL;
        }
        else{
            $i = 1;
            $array_value = count($where);
            $not_equal = NULL;

            // init bind param where
            $bind_param_where = '';
            // allowed clauses
            $clauses = array("LIKE");
            foreach($where as $key => $value){
                // check for last array element and append sql "and" keyword
                // check where clause extension
                $clause = explode(" ", $value);
                $not_equal = (strpos($value, "!") !== FALSE) ? "!" : NULL;
                
                if(strpos($key, "or=") !== FALSE){
                    $joiner = " OR ";
                    $key = str_replace("or= ", NULL, $key);
                }
                else{
                    $joiner = " AND ";
                }
                
                
                if(count($clause) > 1){
                    $clause = $clause[0];
                    $clause = (in_array($clause, $clauses)) ? $clause : "=";
                    $query_append = ($i == $array_value) ? '' : $joiner;
                    $bind_param_where .= $key. " $clause ? ".$query_append;  
                }
                else{
                    $query_append = ($i == $array_value) ? '' : $joiner;
                    $bind_param_where .= $key. " $not_equal= ? ".$query_append;   
                }
                $i++;
            }
            
            
            if(strpos($bind_param_where," OR ") !== FALSE){
                // convert where parameter to array
                $bind_param_where = explode(" AND ",$bind_param_where);
                // get last element index;
                $last_el_or = count($bind_param_where)-1;
                
                // loop through each array element and check before the
                for($i=0; $i<=$last_el_or;$i++){
                    // check for string with AND clause
                    if(strpos($bind_param_where[$i]," OR ") !== FALSE){
                        // convert string to array
                        $string_with_or = explode(" OR ",$bind_param_where[$i]);
                        // get the last 'and' element
                        $last_el = count($string_with_or)-1;
                        $string_with_or[0] = "(".$string_with_or[0];
                        $string_with_or[$last_el] = $string_with_or[$last_el].")";
                        
                        // push back to bind_param_where array after construction
                        $bind_param_where[$i] = implode(" OR ", $string_with_or);
                    }
                }
                
                $bind_param_where = implode(" AND ",$bind_param_where);
            }

            $return = "WHERE $bind_param_where";
        }
        return $return;
    }
    
    protected function cusBindparam($where){
        if(is_array($where)){
            $i = 1;
            foreach($where as $key => &$value){
                if(strpos($value, "LIKE") !== FALSE){
                    $value = "%".str_replace("LIKE ", NULL, $value)."%";
                }
                else if(strpos($value, "!") !== FALSE){
                    $value = str_replace("!",NULL,$value);
                }
                $this->query->bindParam($i, $value);
                $i++;
            }
        }
    }
    
    
    public function selectData($columns, $table_name, $where, $sorttype, $limit, $inner_column_append = NULL){
        // initialize param values
        $bind_param_where = $this->iniParamvalues($where);

        // redefine bind_param_where var
        $bind_param_where = $bind_param_where;
        

        // check where param
        $clause_app = ($where == NULL) ? 'WHERE' : 'AND ';
        $inner_column_append = ($inner_column_append == NULL) ? NULL : "$clause_app $inner_column_append";
        $sql = "SELECT $columns FROM $table_name $bind_param_where $inner_column_append $sorttype $limit";
        
        $this->query = $this->conn->prepare($sql);

        // bind param
        $this->cusBindparam($where);

        // execute query
        return $this->query->execute();
        $this->conn = NULL; 
    }
    
    public function selectJoin($columns, $table1, $table2, $where, $sorttype, $limit, $inner_column_append = NULL){
        // initialize param values
        $bind_param_where = $this->iniParamvalues($where);

        // redefine bind_param_where var
        $bind_param_where = $bind_param_where;

        // check where param
        $clause_app = ($where == NULL) ? 'WHERE' : 'AND ';
        $inner_column_append = ($inner_column_append == NULL) ? NULL : "$clause_app $inner_column_append";
        $sql = "SELECT $columns FROM $table1 INNER JOIN $table2 $bind_param_where $inner_column_append $sorttype $limit";
        $this->query = $this->conn->prepare($sql);

        // bind param
        $this->cusBindparam($where);

        // execute query
        return $this->query->execute();
        $this->conn = NULL; 
    }
    
    public function selectReverseData($columns, $sub_column, $table_name, $where, $sub_sort, $sub_limit, $sorttype){
        // initialize param values
        $bind_param_where = $this->iniParamvalues($where);

        // redefine bind_param_where var
        $sql = "SELECT $columns FROM (SELECT $sub_column FROM $table_name $bind_param_where $sub_sort $sub_limit) AS rev $sorttype";
        $this->query = $this->conn->prepare($sql);

        // bind param
        $this->cusBindparam($where);

        // execute query
        return $this->query->execute();
        $this->conn = NULL; 
    }

    public function selectNoDup($column, $table_name, $where, $sorttype, $limit){
        // initialize param values
        $bind_param_where = $this->iniParamvalues($where);

        // redefine bind_param_where var
        $sql = "SELECT DISTINCT $olumn FROM $table_name $bind_param_where $sortype $limit";
        $this->query = $this->conn->prepare($sql);

        // bind param
        $this->cusBindparam($where);

        // execute query
        return $this->query->execute();
        $this->conn = NULL; 
    }
    
    public function countRows($table_name, $where=NULL, $inner_column_append = NULL, $column = NULL){
        // initialize param values
        $bind_param_where = $this->iniParamvalues($where);
        
        // check where param
        $clause_app = ($where == NULL) ? 'WHERE' : 'AND ';

        $inner_column_append = ($inner_column_append == NULL) ? NULL : "$clause_app $inner_column_append";
        // redefine bind_param_where var
        $column = $column == NULL ? "*" : $column;
        $sql = "SELECT COUNT($column) FROM $table_name $bind_param_where $inner_column_append";
        $this->query = $this->conn->prepare($sql);

        // bind param
        $this->cusBindparam($where);

        // execute query
        $this->query->execute();

        return $this->query->fetchColumn();
        $this->conn = NULL; 
    }
}
