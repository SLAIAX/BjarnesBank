<?php
namespace agilman\a2\model;

class LoginModel extends Model
{
    private $username;
    private $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        parent::__construct();
    }

    public function validateLogin()
    {
        if($this->username == ""){
            throw new \UnexpectedValueException();
        }
        if(!$result = $this->db->query("SELECT * FROM `user` WHERE `Username` = '$this->username' ;")){
            throw new \mysqli_sql_exception();
        }
        $result = $result->fetch_assoc();

        if($result['Password'] == $this->password){
            $_SESSION['id'] = $result['ID'];
            unset($password);
            unset($result);
            return true;
        }
        return false;
    }
}
