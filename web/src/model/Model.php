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

        $result = $this->db->query("SHOW TABLES LIKE 'account';");

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
                error_log("Failed creating table user", 0);
            }


            $result = $this->db->query("CREATE TABLE `transactions` (
                    `TransID` INT(8) unsigned NOT NULL AUTO_INCREMENT , 
                    `FromAccountID` INT(8) unsigned NOT NULL,
            `Description` VARCHAR(256) DEFAULT NULL , 
            `DateOfTrans` DATE DEFAULT NULL , 
            `MoneyIn` DECIMAL(16) DEFAULT NULL , 
            `MoneyOut` DECIMAL(16) DEFAULT NULL , 
            `Balance` DECIMAL(16) DEFAULT NULL ,
            `ToAccountID` int(8) UNSIGNED NOT NULL,
            PRIMARY KEY (`TransID`));"
            );
            if (!$result) {
                // handle appropriately
                error_log("Failed creating table transaction", 0);
            }
            $result = $this->db->query("CREATE TABLE `account` (
                    `AccountID` INT(8) unsigned NOT NULL AUTO_INCREMENT , 
                    `UserID` INT(8) unsigned DEFAULT NULL , 
            `AccountType` VARCHAR(256) DEFAULT NULL , 
            `Balance` DECIMAL(16) DEFAULT NULL , 
            `AccountName` VARCHAR(256) DEFAULT NULL ,
            PRIMARY KEY (`AccountID`));"
            );
            if (!$result) {
                // handle appropriately
                error_log("Failed creating table account", 0);
            }

            if(!$this->db->query("INSERT INTO `user` (`ID`, `First Name`, `Last Name`, `Username`, `Password`, `Address`, `Email`, `Phone`) VALUES
            (NULL, 'Bob', 'jim', 'admin', 'Admin1234', '17 Alnack Place', 'admin@admin.co', '02298719235'),
            (NULL, 'Andrew', 'Gilman', 'AGilman', 'AGilman1', '124 Sunset Avenue', 'a.gilman@massey.ac.nz', '4451234'),
            (NULL, 'Jordan', 'Drumm', 'SLAIAX', 'football22', '16 ', 'jordan@gmail.com', '094730000'),
            (NULL, 'Zane', 'Lamb', 'Zane', 'ZaneLamb2', '12 Time Avenue', 'lanelamb@gmail.com', '0421483'),
            (NULL, 'Sam', 'Arnet', 'Sammy', 'Sammy', '9 Pleasantville ave', 'sam@gmail.com', '1027493');")){
                echo $this->db->error;
                error_log("Failed creating sample data!", 0);
            }

            if(!$this->db->query("INSERT INTO `account` (`AccountID`, `UserID`, `AccountType`, `Balance`, `AccountName`) VALUES
          (NULL, 1, 'Ready-Saver', '6780', 'Test'),
          (NULL, 1, 'Select', '18955', 'Spendings'),
          (NULL, 2, 'Serious-Saver', '152287', 'My Savings'),
          (NULL, 2, 'Select', '7612', 'Holiday'),
          (NULL, 2, 'Jump-Start', '1200', 'Spendings'),
          (NULL, 2, 'Freedom', '876', 'Bills'),
          (NULL, 2, 'Ready-Saver', '50', 'ForBob');")){
                echo $this->db->error;
                error_log("Failed creating sample data!", 0);
            }

            if(!$this->db->query("INSERT INTO `transactions` (`TransID`, `FromAccountID`, `Description`, `DateOfTrans`, `MoneyIn`, `MoneyOut`, `Balance`, `ToAccountID`) VALUES
          (NULL, 4, 'Heating', '2018-10-06', '0', '100', '149900', 7),
          (NULL, 4, 'Heating', '2018-10-06', '100', '0', '663', 7),
          (NULL, 3, 'Dinner', '2018-10-06', '0', '56', '18880', 5),
          (NULL, 3, 'Dinner', '2018-10-06', '56', '0', '7612', 5),
          (NULL, 1, 'Wage', '2018-09-29', '0', '1600', '8400', 4),
          (NULL, 1, 'Wage', '2018-09-29', '1600', '0', '151500', 4),
          (NULL, 1, 'Wage', '2018-10-06', '0', '1600', '6800', 4),
          (NULL, 1, 'Wage', '2018-10-06', '1600', '0', '153100', 4),
          (NULL, 3, 'Core i5 7600K', '2018-10-06', '0', '400', '18480', 2),
          (NULL, 3, 'Core i5 7600K', '2018-10-06', '400', '0', '424', 2),
          (NULL, 4, 'Top up', '2018-10-06', '0', '500', '152600', 3),
          (NULL, 4, 'Top up', '2018-10-06', '500', '0', '18980', 3),
          (NULL, 4, 'Gas', '2018-10-06', '0', '213', '152387', 7),
          (NULL, 4, 'Gas', '2018-10-06', '213', '0', '876', 7),
          (NULL, 4, 'Allowance', '2018-10-06', '0', '100', '152287', 6),
          (NULL, 4, 'Allowance', '2018-10-06', '100', '0', '1300', 6),
          (NULL, 6, 'Dinner', '2018-10-06', '0', '100', '1200', 2),
          (NULL, 6, 'Dinner', '2018-10-06', '100', '0', '524', 2),
          (NULL, 3, 'Drinks', '2018-10-06', '0', '25', '18955', 2),
          (NULL, 3, 'Drinks', '2018-10-06', '25', '0', '569', 2);")){
                echo $this->db->error;
                error_log("Failed creating sample data!", 0);
            }
        }
    }
}
