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
     *
     * Checks if Create Action is Available( used to stop recreating accounts on refresh)
     * Intialises Account with Post Data
     * Validates the account information, Throws is something is wrong and handled appropriately
     * Saves the Account , Inserting into Database
     * Renders account Created Page
     * Sets action available false, so that if press refresh it will not create an identical entry
     */
    public function createAction()
    {
        if ($_SESSION['actionAvailable']) {
            try {
                $account = new AccountModel($_POST['username'],$_POST['firstname'],
                                            $_POST['lastname'],$_POST['password'],
                                            $_POST['address'], $_POST['email'], $_POST['phonenumber']);
                $account->validate();
                $account->save();
                $view = new View('accountCreated');
                echo $view->render();
                $_SESSION['actionAvailable'] = false;
            } catch (\UnexpectedValueException $e) {
                $_SESSION['emptyField'] = true;
                $view = new View('userJoinPage');
                echo $view->render();
            } catch (\LogicException $e) {
                $_SESSION['invalidInput'] = true;
                $view = new View('userJoinPage');
                echo $view->render();
            }
        }
    }
}
