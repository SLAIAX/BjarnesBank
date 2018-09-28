<?php
namespace agilman\a2\model;

/**
 * Class AccountCollectionModel
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
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

    /**
     * Get account collection
     *
     * @return \Generator|AccountModel[] Accounts
     */
    public function validateLogin()
    {

        try {
            $usernameTest = $this->db->query("SELECT `Username` FROM `user` WHERE `Username` = '$this->username' ;");
            $username = array_column($usernameTest->fetch_all(),0);
            if($username[0] == ""){
                throw new \Exception();
            }


            if (!$result = $this->db->query("SELECT `Password` FROM `user` WHERE `Username` = '$this->username' ;")) {
                throw new \Exception();

            }
           $temp = array_column($result->fetch_all(),0);

            if($temp[0] == $this->password){

                unset($password);
                unset($result);


                return true;
            }
        }catch(\Exception $e){
            unset($password);
        }
        return false;

    }
}
