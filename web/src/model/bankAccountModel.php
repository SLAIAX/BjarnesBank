<?php
namespace agilman\a2\model;
session_start();

/**
 * Class AccountModel
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class bankAccountModel extends Model
{
    private $mID;
    private $mAccountName;
    private $mType;
    private $mBalance;



    /**
     * @return mixed
     */
    public function getMID()
    {
        return $this->mID;
    }



    /**
     * @return mixed
     */
    public function getMAccountName()
    {
        return $this->mAccountName;
    }

    /**
     * @param mixed $mAccountName
     */
    public function setMAccountName($mAccountName)
    {
        $this->mAccountName = $mAccountName;
    }

    /**
     * @return mixed
     */
    public function getMType()
    {
        return $this->mType;
    }

    /**
     * @param mixed $mType
     */
    public function setMType($mType)
    {
        $this->mType = $mType;
    }

    /**
     * @return mixed
     */
    public function getMBalance()
    {
        return $this->mBalance;
    }

    /**
     * @param mixed $mBalance
     */
    public function setMBalance($mBalance)
    {
        $this->mBalance = $mBalance;
    }





    /**
     * @return mixed
     */
    public function findID($accountName, $id)
    {
        if (!$result = $this->db->query("SELECT `AccountID` FROM `account` WHERE `AccountName` = '$accountName' and `UserID` = '$id';")) {
          //  return 0;

        }
        $result = $result->fetch_assoc();
        //$result = $result->fetch_assoc();
        return $result['AccountID'];
    }


    /**
     * @return mixed
     */
    public function findUserID($username)
    {
        if (!$result = $this->db->query("SELECT `ID` FROM `user` WHERE `Username` = '$username';")) {
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
            $temp[$i][3] = $accounts['AccountID'];
            echo $temp[$i][3];
            echo $temp[$i][0];
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
    public function save($id)
    {

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

    public function validate($accname, $ID){
        if(!$accname){
            throw new \UnexpectedValueException();
            //throw incomplete data
        }
        if ($result = $this->db->query("SELECT * FROM `account` WHERE `AccountName` = '$accname' and `UserID` = '$ID'; ")) {
            // throw new ACCOUNT ALREADY EXISTS
            throw new \LogicException();
        }

    }

    public function deleteAccount($id){
        if (!$result = $this->db->query("DELETE FROM `account` WHERE `AccountID` = '$id';")) {
            //throw new ...
        }
        if (!$result = $this->db->query("DELETE FROM `transactions` WHERE `AccountID` = '$id';")) {
            //throw new ...
        }
    }
}
