<?php
namespace agilman\a2\model;

class AccountModel extends Model
{
    private $mID;
    private $mUserName;
    private $mFirstName;
    private $mLastName;
    private $mAddress;
    private $mPassword;
    private $mEmail;
    private $mPhone;

    public function findID($username)
    {
        if (!$result = $this->db->query("SELECT ID FROM `user` WHERE `Username` = '$username';")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();
        return $result['ID'];
    }

    public function getAccounts($id){
        if (!$result = $this->db->query("SELECT * FROM `account` WHERE `UserID` = '$id';")) {
            throw new \mysqli_sql_exception();
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

    public function load($id)
    {
        if (!$result = $this->db->query("SELECT * FROM `user` WHERE `ID` = $id;")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();
        $this->mFirstName = $result['name'];
        $this->mID = $id;
        return $this;
    }

    public function save($firstname, $lastname, $username, $password)
    {
        $name = $this->mFirstName ?? "NULL";
        $address = $_POST['address'];
        $email = $_POST['email'];
        $phonenumber = $_POST['phonenumber'];
        if (!isset($this->mID)) {
            if (!$result = $this->db->query("INSERT INTO `user` VALUES (NULL, '$firstname', '$lastname', '$username', '$password', '$address', '$email','$phonenumber');")) {
                throw new \mysqli_sql_exception();
            }
            $this->mID = $this->db->insert_id;
        } else {
            if (!$result = $this->db->query("UPDATE `user` SET `user` = '$name' WHERE `ID` = $this->mID;")) {
                throw new \mysqli_sql_exception();
            }
        }
    }

    public function validate($firstname ,$lastname ,$username, $password){
        if(!$firstname || !$lastname || !$username || !$password){
            throw new \UnexpectedValueException();
        }
        if ($result = $this->db->query("SELECT * FROM `user` WHERE `Username` = '$username';")) {
            $accounts = $result->fetch_assoc();
            if($accounts['Username'] != ""){
                throw new \LogicException();
            }
        }
    }
}
