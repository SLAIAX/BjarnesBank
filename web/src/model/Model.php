<?php
namespace agilman\a2\model;

use mysqli;

/**
 * Class Model
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class Model
{
    protected $db;

    // is this the best place for these constants?
    const DB_HOST = 'mysql';
    const DB_USER = 'root';
    const DB_PASS = 'root';
    const DB_NAME = 'a2';

    public function __construct()
    {
        $this->db = new mysqli(
            Model::DB_HOST,
            Model::DB_USER,
            Model::DB_PASS
            //Model::DB_NAME
        );

        if (!$this->db) {
            // can't connect to MYSQL???
            // handle it...
            // e.g. throw new someException($this->db->connect_error, $this->db->connect_errno);
        }
        //----------------------------------------------------------------------------
        // This is to make our life easier
        // Create your database and populate it with sample data
        $this->db->query("CREATE DATABASE IF NOT EXISTS " . Model::DB_NAME . ";");

        if (!$this->db->select_db(Model::DB_NAME)) {
            // somethings not right.. handle it
            error_log("Mysql database not available!", 0);
        }

        $result = $this->db->query("SHOW TABLES LIKE 'user';");

        if ($result->num_rows == 0) {
            // table doesn't exist
            // create it and populate with sample data

            $result = $this->db->query(

                "CREATE TABLE `user` (
                    `ID` INT(8) unsigned NOT NULL AUTO_INCREMENT , 
            `First Name` VARCHAR(256) DEFAULT NULL , 
            `Last Name` VARCHAR(256) DEFAULT NULL , 
            `Username` VARCHAR(256) DEFAULT NULL , 
            `Password` VARCHAR(256) DEFAULT NULL , 
            `Address` VARCHAR(256) DEFAULT NULL , 
            `Email` VARCHAR(256) DEFAULT NULL , 
            `Phone` VARCHAR(256) DEFAULT NULL , 
            PRIMARY KEY (`ID`));"
            );

            if (!$result) {
                // handle appropriately
                error_log("Failed creating table account", 0);
            }

        }
//        if (!$this->db->query("INSERT INTO `user` VALUES (NULL,'Bob', 'jim', 'address', 'st','st');")) {
//            // handle appropriately
//            echo $this->db->error;
//            error_log("Failed creating sample data!", 0);
//        }
        //----------------------------------------------------------------------------
    }
}
