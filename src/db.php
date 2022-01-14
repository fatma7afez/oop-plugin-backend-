<?php

namespace  Plugin\Oopback ;

class db
{

    private $connection;

    private  $sql;

    public function __construct()
    {
        $this->connection = mysqli_connect("localhost", "root", "", "lmsdbs");
    }

    

    public function select($table)
    {
        $this->sql = "SELECT * FROM `$table`";
        return $this;
    }

    public function where($column, $operator, $value)
    {
        $this->sql .= " WHERE `$column` $operator $value";
        return $this;
    }

    public function selectJoinColum($table,$data){
        $row ='';
        foreach($data as $tab => $col){
           $row .= "`$tab`.`$col`,";
        }
        $row = trim($row,",");
        $this->sql = "SELECT $row FROM `$table`";
        print_r($this);
        return $this;
    }
    
    public function selectJoin($relTable,$primColum){
        $this->sql .= " INNER JOIN $relTable ON $relTable . $primColum";
        return $this;
    }

    public function commandColumn($table,$forColumn){
        $this->sql .=" = $table . $forColumn";
        print_r($this);
        return $this;  
    }

    public function first()
    {
        $query =  mysqli_query($this->connection, $this->sql);
        if (is_object($query)) {
            return mysqli_fetch_assoc($query);
        }
        $this->showErrors();
    }

    public function rows()
    {
        $query =  mysqli_query($this->connection, $this->sql);
        // print_r($query);die;
        if (is_object($query)) {
            while ($row = mysqli_fetch_assoc($query)) {
                $data[] = $row;
            }
            return $data;
        }
        $this->showErrors();
    
    }

    public function insert($table, $data)
    {
        $rowcolumn = '';
        $rowvalue = '';
        foreach ($data as $column => $value) {
            $rowcolumn .=  "`$column`,";
            $rowvalue .= " '$value',";
        }
        $rowcolumn = rtrim($rowcolumn, ',');
        $rowvalue = rtrim($rowvalue, ',');

        $this->sql = "INSERT INTO `$table` ($rowcolumn) VALUES ($rowvalue)";

        return $this;
    }



    public function update($table, $data)
    {
        $row = '';
        foreach ($data as $column => $value) {
            $row .=  "`$column` = '$value',";
        }
        $row = rtrim($row, ',');

        $this->sql .= "UPDATE `$table` SET $row ";
        return $this;
    }

    public function delete($table)
    {
        $this->sql = "DELETE FROM `$table` ";
        return $this;
    }
    public function  excute()
    {
        mysqli_query($this->connection, $this->sql);
        return (mysqli_affected_rows($this->connection) == 1) ? mysqli_affected_rows($this->connection) : $this->showErrors();
    }

    public function __destruct()
    {
        mysqli_close($this->connection);
    }

    public function showErrors()
    {
        # code...
        $errors =  mysqli_error_list($this->connection);
        $showError  = "<ul>";
        foreach ($errors as $error) {
            $showError .= "<li style='color:red'>" . $error["error"] . "</li>";
        }
        $showError .= "</ul>";

        echo $showError;
    }
}
