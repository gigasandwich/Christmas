<?php

namespace app\models;

use PDO;

class CrudModel
{

    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function boomerang($query, $fetchMode)
    {
        $DBH = $this->db;
        $STH = $DBH->prepare($query);
        $STH->execute();

        $STH->setFetchMode($fetchMode);

        $results = array();
        while ($row = $STH->fetch()) {
            $results[] = $row;
        }
        $STH->closeCursor();
        return $results;
    }

    public function get_table($name) {
        $query = "SELECT * FROM christmas_$name";
        return $this->boomerang($query, PDO::FETCH_ASSOC);
    }

    public function get_table_columns($tableName) {
        $query = "DESCRIBE christmas_$tableName";
        $columns = [];
        $tableDescription = $this->boomerang($query, PDO::FETCH_ASSOC);
        /*
        +------------+---------+------+-----+---------+----------------+
        | Field      | Type    | Null | Key | Default | Extra          |
        +------------+---------+------+-----+---------+----------------+
        | cvd_id     | int(11) | NO   | PRI | NULL    | auto_increment |
        | vehicle_id | int(11) | NO   | MUL | NULL    |                |
        | driver_id  | int(11) | NO   | MUL | NULL    |                |
        | cvd_date   | date    | NO   |     | NULL    |                |
        +------------+---------+------+-----+---------+----------------+
        */
        foreach ($tableDescription as $row) {
            $columns[] = [
                'name' => $row['Field'],
                'type' => $row['Type'],
                'auto_increment' => strpos($row['Extra'], 'auto_increment') !== false
            ];
        }

        return $columns;
    }
    
    public function get_foreign_keys($tableName) {
        $query = "
            SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'christmas_$tableName' AND REFERENCED_TABLE_NAME IS NOT NULL";
            
            /*
            +-------------+-----------------------+------------------------+
            | COLUMN_NAME | REFERENCED_TABLE_NAME | REFERENCED_COLUMN_NAME |
            +-------------+-----------------------+------------------------+
            | vehicle_id  | christmas_vehicle   | vehicle_id             |
            | driver_id   | christmas_driver    | driver_id              |
            +-------------+-----------------------+------------------------+
            */
     
        return $this->boomerang($query, PDO::FETCH_ASSOC);
    }
    
    public function get_foreign_key_values($referencedColumn, $referencedTable, $columnToShow) {
        $query = "SELECT $referencedColumn AS id, $columnToShow AS name FROM $referencedTable";
        
        $STH = $this->db->prepare($query);
        $STH->execute();
        
        $values = [];
        while ($row = $STH->fetch(PDO::FETCH_ASSOC)) {
            $values[$row['id']] = $row['name'];
        }
        /*
        [
            1: 'Jean',
            5: 'Poyz'
        ]
        */
    
        return $values;
    }

    
    public function insert($tableName, $data) {
        $DBH = $this->db; 

        $columns = array_keys($data);

        $placeholders = array_map(function () {
            return "?";
        }, $columns);
    
        $query = "INSERT INTO christmas_$tableName (" . implode(", ", $columns) . ") 
                  VALUES (" . implode(", ", $placeholders) . ")";
    
        
        $STH = $DBH->prepare($query);
    
        try {
            $index = 1;  
            foreach ($data as $value) {
                $STH->bindValue($index, $value);
                $index++;  
            }
            $STH->execute();
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function update($tableName, $data, $id) {
        $DBH = $this->db;
        
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = ?";
        }

        $columnsMetaData = $this->get_table_columns($tableName);
        $firstColumnName = $columnsMetaData[0]['name']; // primary key

        $query = "UPDATE christmas_$tableName SET " . implode(", ", $setClause) . " WHERE $firstColumnName = ?";
        
        $STH = $DBH->prepare($query);
        
        $index = 1;
        foreach ($data as $value) {
            $STH->bindValue($index, $value);
            $index++;
        }
        $STH->bindValue($index, $id); 
        $STH->execute();
    }

    public function delete($tableName, $id) {

        $columnsMetaData = $this->get_table_columns($tableName);
        $firstColumnName = $columnsMetaData[0]['name']; // primary key

        $query = "DELETE FROM christmas_$tableName WHERE $firstColumnName = ?";
        $DBH = $this->db;
        $STH = $DBH->prepare($query);
        $STH->bindValue(1, $id);
        $STH->execute();

    }
    
    public function uploadPic($file){
        $folder = '/assets/gifts/img/';
        $file_name = basename($file['name']);
        $max_size = 5000000;
        $size = filesize($file['tmp_name']);
        $extensions = array('.png', '.gif', '.jpg', '.jpeg');
        $extension = strrchr($file_name, '.'); 
    
        // Check for valid file extension
        if (!in_array($extension, $extensions)) {
            echo 'You need to upload files of type: png/gif/jpg/jpeg';
            return false;
        }
    
        // Check for file size
        if ($size > $max_size) {
            echo 'File too big...';
            return false;
        }
    
        // Format the file name
        $file_name = strtr(
            $file_name,
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'
        );
        $file_name = preg_replace('/([^.a-z0-9]+)/i', '-', $file_name);
    
        // Attempt to move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $folder . $file_name)) {
            return true;
        } else {
            echo 'Failed to upload the file.';
            return false;
        }
   }
}

