<?php
namespace agilman\a2\model;


/**
 * Class AccountModel
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class AccountModel extends Model
{
    private $mID;
    private $mUserName;
    private $mFirstName;
    private $mLastName;
    private $mUserType;
    private $mAddress;
    private $mPassword;
    private $mDOB;
    private $mEmail;
    private $mPhone;

    /**
     * User constructor.
     * @param $mID
     * @param $mUserName
     * @param $mFirstName
     * @param $mLastName
     * @param $mUserType
     * @param $mAddress
     * @param $mPassword
     * @param $mDOB
     * @param $mEmail
     * @param $mPhone
     */
//    public function __construct($mID, $mUserName, $mFirstName, $mLastName, $mUserType, $mAddress, $mPassword, $mDOB, $mEmail, $mPhone)
//    {
//        $this->mID = $mID;
//        $this->mUserName = $mUserName;
//        $this->mFirstName = $mFirstName;
//        $this->mLastName = $mLastName;
//        $this->mUserType = $mUserType;
//        $this->mAddress = $mAddress;
//        $this->mPassword = $mPassword;
//        $this->mDOB = $mDOB;
//        $this->mEmail = $mEmail;
//        $this->mPhone = $mPhone;
//    }


    /**
     * @var string Account Name
     */

    /**
     * @return string Account Name
     */
    public function getName()
    {
        return $this->mFirstName;
    }

    /**
     * @param string $name Account name
     *
     * @return $this AccountModel
     */
    public function setName(string $name)
    {
        $this->mFirstName = $name;

        return $this;
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
     * @param mixed $Address
     */
    public function setAddress($Address)
    {
        $this->mAddress = $Address;
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
    public function getID()
    {
        return $this->mID;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->mUserName;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->mUserType;
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
        $name = $this->mFirstName ?? "NULL";
       // $this->mFirstName = $name;
        if (!isset($this->mID)) {
            // New account - Perform INSERT
            if (!$result = $this->db->query("INSERT INTO `user` VALUES (NULL,'$name', 'jim', 'admin', 'admin', 'address', 'st','st');")) {
                // throw new ...
            }
            $this->mID = $this->db->insert_id;
        } else {
            // saving existing account - perform UPDATE
            if (!$result = $this->db->query("UPDATE `user` SET `user` = '$name' WHERE `ID` = $this->mID;")) {
                // throw new ...
            }
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
