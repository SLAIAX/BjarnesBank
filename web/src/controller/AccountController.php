<?php
namespace agilman\a2\controller;
session_start();

use agilman\a2\model\{AccountModel, AccountCollectionModel};
use agilman\a2\view\View;

/**
 * Class AccountController
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class AccountController extends Controller
{
    /**
     * Account Create action
     */
    public function createAction()
    {
        if($_SESSION['actionAvailable']) {
            try {
                $account = new AccountModel();

                $firstname = $_POST['firstname'];

                $lastname = $_POST['lastname'];
                $username = $_POST['username'];
                $password = $_POST['password'];



                $account->validate($firstname, $lastname, $username, $password);
                $account->save($firstname, $lastname, $username, $password);
                $view = new View('accountCreated');
                echo $view->render();
                $_SESSION['actionAvailable'] = False;
            } catch (\UnexpectedValueException $e){
                $_SESSION['emptyField'] = True;
                $view = new View('userJoinPage');
                echo $view->render();
            } catch (\LogicException $e) {
                $_SESSION['invalidInput'] = True;
                $view = new View('userJoinPage');
                echo $view->render();
            }
        }
    }
}
