<?php
namespace agilman\a2\model;

class LoginModel extends Model
{
    private $mUserName;
    private $mPassword;

    /**
     * LoginModel constructor.
     * @param $mUserName
     * @param $mPassword
     */
    public function __construct($mUserName, $mPassword)
    {
        $this->mUserName = $mUserName;
        $this->mPassword = $mPassword;
        parent::__construct();
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





    public function validateLogin()
    {
        $userName = $this->getUserName();
        if ($userName == "") {
            throw new \UnexpectedValueException();
        }
        if (!$result = $this->db->query("SELECT * FROM `user` WHERE `Username` = '$userName' ;")) {
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();

        if ($result['Password'] == $this->getPassword()) {
            $_SESSION['id'] = $result['ID'];
            return true;
        }
        return false;
    }
}
