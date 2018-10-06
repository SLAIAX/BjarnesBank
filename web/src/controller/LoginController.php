<?php


namespace agilman\a2\controller;
session_start();

use agilman\a2\model\LoginModel;
use agilman\a2\view\View;

/**
 * Class HomeController
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class LoginController extends Controller
{
    /**
     * Account Index action
     */
    public function indexAction()
    {
        $view = new View('loginPage');
        echo $view->render();
    }


    /**
     * Account Create action
     */
    public function validateAction()
    {
       $_SESSION["access"] = 0;
       $username = $_POST['username'];
       $password = $_POST['password'];
       try {
           $login = new LoginModel($username, $password);
           $flag = $login->validateLogin();
           if ($flag) {
               $_SESSION['username'] = $username;
               $_SESSION["access"] = 1;
               
               $this->redirect('homePage');
           } else {
               unset($login);
               $_SESSION["access"] = 2;
               $this->redirect('loginPage');
           }
       } catch (\Exception $e){
           $this->redirect('loginPage');
       }
       unset($password);
       unset($username);
    }
}
