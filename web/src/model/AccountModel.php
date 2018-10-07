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

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->mUserName;
    }

    /**
     * @param mixed $mUserName
     */
    public function setUserName($mUserName)
    {
        $this->mUserName = $mUserName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->mFirstName;
    }

    /**
     * @param mixed $mFirstName
     */
    public function setFirstName($mFirstName)
    {
        $this->mFirstName = $mFirstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->mLastName;
    }

    /**
     * @param mixed $mLastName
     */
    public function setLastName($mLastName)
    {
        $this->mLastName = $mLastName;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->mAddress;
    }

    /**
     * @param mixed $mAddress
     */
    public function setAddress($mAddress)
    {
        $this->mAddress = $mAddress;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->mPassword;
    }

    /**
     * @param mixed $mPassword
     */
    public function setPassword($mPassword)
    {
        $this->mPassword = $mPassword;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->mEmail;
    }

    /**
     * @param mixed $mEmail
     */
    public function setEmail($mEmail)
    {
        $this->mEmail = $mEmail;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->mPhone;
    }

    /**
     * @param mixed $mPhone
     */
    public function setPhone($mPhone)
    {
        $this->mPhone = $mPhone;
    }

    /**
     * @return mixed
     */
    public function getMID()
    {
        return $this->mID;
    }

    /**
     * @param mixed $mID
     */
    public function setMID($mID)
    {
        $this->mID = $mID;
    }



    public function findID($username)
    {
        if (!$result = $this->db->query("SELECT ID FROM `user` WHERE `Username` = '$username';")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();
        return $result['ID'];
    }

    public function load($id)
    {
        if (!$result = $this->db->query("SELECT * FROM `user` WHERE `ID` = $id;")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();
        $this->setFirstName($result['FirstName']);
        $this->setID($id);
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
