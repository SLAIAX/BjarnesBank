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
     * AccountModel constructor.
     * @param $mUserName
     * @param $mFirstName
     * @param $mLastName
     * @param $mAddress
     * @param $mPassword
     * @param $mEmail
     * @param $mPhone
     */
    public function __construct($mUserName, $mFirstName, $mLastName, $mPassword, $mAddress, $mEmail, $mPhone)
    {
        $this->mUserName = $mUserName;
        $this->mFirstName = $mFirstName;
        $this->mLastName = $mLastName;
        $this->mAddress = $mAddress;
        $this->mPassword = $mPassword;
        $this->mEmail = $mEmail;
        $this->mPhone = $mPhone;
    }


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



    public function save()
    {
        $firstname = $this->getFirstName();
        $address = $this->getAddress();
        $email = $this->getEmail();
        $phonenumber = $this->getPhone();
        $lastname = $this->getLastName();
        $password = $this->getPassword();
        $username = $this->getUserName();
        if (!isset($this->mID)) {
            if (!$result = $this->db->query("INSERT INTO `user` VALUES (NULL, '$firstname', '$lastname', '$username', '$password', '$address', '$email','$phonenumber');")) {
                throw new \mysqli_sql_exception();
            }
            $this->mID = $this->db->insert_id;
        } else {
//            if (!$result = $this->db->query("UPDATE `user` SET `user` = '' WHERE `ID` = $this->mID;")) {
//                throw new \mysqli_sql_exception();
//            }
        }
    }

    public function validate(){
        $username = $this->getUserName();
        if(!$this->getFirstName() || !$this->getLastName() || !$username || !$this->getPassword()){
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
