<?php
namespace agilman\a2\controller;


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
        $this->redirect('accountIndex');
    }


    /**
     * Account Create action
     */
    public function validateAction()
    {
        $flag = false;
       $username = $_POST['name'];
       $password = $_POST['password'];

       if(!isset($username) || !isset($password)){
           error_log("error something went wrong");
           echo " empty fields" ;// add somthing to display in html
       }

        $login = new LoginModel($username, $password);
        $flag = $login->validateLogin();


        if($flag) {
            $view = new View('accountCreated'); // send to profile page
            echo $view->render();
        }else{
            unset($login);
        }
    }
}
