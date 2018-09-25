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
            if (!$result = $this->db->query("SELECT `Password` FROM `user` WHERE `Username` = $this->username ;")) {
                // throw new ...
                throw new \Exception();
            }
            if($result === $this->password){
                return true;

                unset($password);
                unset($result);
            }
        }catch(\Exception $e){
            unset($password);
        }
        return false;

    }
}
