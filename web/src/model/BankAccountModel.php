<?php
namespace agilman\a2\model;
session_start();

class BankAccountModel extends Model
{
    private $mID;
    private $mAccountName;
    private $mType;
    private $mBalance;

    public function findID($accountName, $id)
    {
        if (!$result = $this->db->query("SELECT `AccountID` FROM `account` WHERE `AccountName` = '$accountName' and `UserID` = '$id';")) {
          throw new \UnexpectedValueException();
        }
        $result = $result->fetch_assoc();
        return $result['AccountID'];
    }

    public function getAccounts($id){
        if (!$result = $this->db->query("SELECT * FROM `account` WHERE `UserID` = '$id';")) {
            throw new \Exception(); //MOVE TO COLLECTION MODEL
        }
        $i = 0;
        while($accounts = $result->fetch_assoc()){
            $accountData[$i][0] = $accounts['AccountType'];
            $accountData[$i][1] = $accounts['AccountName'];
            $accountData[$i][2] = $accounts['Balance'];
            $accountData[$i][3] = $accounts['AccountID'];
            $i++;
        }
        return $accountData;
    }

    public function save($id)
    {
        $AccountName = $_POST['AccountName'];
        $AccountType = $_POST['AccountType'];
        if (!$result = $this->db->query("INSERT INTO `account` VALUES (NULL, '$id', '$AccountType', 0, '$AccountName');")) {
            throw new \mysqli_sql_exception();
        }
    }

    public function validate($accname, $ID){
        if(!$accname){
            throw new \UnexpectedValueException();
        }
        if ($result = $this->db->query("SELECT * FROM `account` WHERE `AccountName` = '$accname' and `UserID` = '$ID';")) {
            $accounts = $result->fetch_assoc();
            if($accounts['AccountName'] != ""){
                throw new \LogicException();
            }
        }
    }

    public function deleteAccount($id){
        if (!$result = $this->db->query("DELETE FROM `account` WHERE `AccountID` = '$id';")) {
            throw new \mysqli_sql_exception();
        }
        if (!$result = $this->db->query("DELETE FROM `transactions` WHERE `AccountID` = '$id';")) {
            throw new \mysqli_sql_exception();
        }
    }
}
