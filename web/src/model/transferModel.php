<?php
namespace agilman\a2\model;
session_start();

/**
 * Class AccountModel
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class transferModel extends Model
{

    /**
     * @return mixed
     */
    public function findID($username)
    {
        if (!$result = $this->db->query("SELECT ID FROM `user` WHERE `Username` = '$username';")) {
          //  return 0;


        }
        $result = $result->fetch_assoc();
        //$result = $result->fetch_assoc();
        return $result['ID'];
    }






    public function getAccounts($id){
        if (!$result = $this->db->query("SELECT * FROM `account` WHERE `UserID` = '$id';")) {
            return 0;
        }

        $i = 0;
        while($accounts = $result->fetch_assoc()){
            $temp[$i][0] = $accounts['AccountType'];
            $temp[$i][1] = $accounts['AccountName'];
            $temp[$i][2] = $accounts['Balance'];
            $i++;

        }



      //  $this->N = $result->num_rows;
        return $temp;
    }








    /**
     * Loads account information from the database
     *
     * @param int $id Account ID
     *
     * @return $this AccountModel
     */
    public function load($id)
    {
        if (!$result = $this->db->query("SELECT * FROM `user` WHERE `ID` = $id;")) {
            // throw new ...
        }

        $result = $result->fetch_assoc();
        $this->mFirstName = $result['name'];
        $this->mID = $id;

        return $this;
    }

    /**
     * Saves account information to the database

     * @return $this AccountModel
     */
    public function save()
    {
        $id = (int)$this->findID($_SESSION['username']);
        $AccountName = $_POST['AccountName'];
        $AccountType = $_POST['AccountType'];
        if (!isset($this->mID)) {
            // New account - Perform INSERT

            if (!$result = $this->db->query("INSERT INTO `account` VALUES (NULL, '$id', '$AccountType', 0, '$AccountName');")) {
                // throw new ...
            }
            $this->mID = $this->db->insert_id;
        } else {
            // saving existing account - perform UPDATE
            //if (!$result = $this->db->query("UPDATE `account` SET `user` = '$name' WHERE `ID` = $this->mID;")) {
                // throw new ...
        //    }
        }
        return $this;
    }

    /**
     * Deletes account from the database

     * @return $this AccountModel
     */
    public function delete()
    {
        if (!$result = $this->db->query("DELETE FROM `user` WHERE `user`.`ID` = $this->mID;")) {
            //throw new ...
        }

        return $this;
    }
}
