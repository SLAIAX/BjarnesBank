<?php

namespace agilman\a2\controller;
session_start();

use agilman\a2\model\AccountModel;
use agilman\a2\model\bankAccountModel;
use agilman\a2\model\transactionModel;
use agilman\a2\view\View;

/**
 * Class AccountController
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class BankAccountController extends Controller
{
    public function indexAction(){
        $view = new View('bankAccountCreatePage');
        echo $view->render();
    }


    public function createBankAccount()
    {
        if($_SESSION['actionAvailable']) {
            try {

                $accountname = $_POST['AccountName'];
                $account = new bankAccountModel();
                $user = new AccountModel();
                $id = $user->findID($_SESSION['username']);
               
                $account->validate($accountname, $id);
                $account->save($id);
               $this->redirect('homePage');
                $_SESSION['actionAvailable'] = False;

            } catch (\UnexpectedValueException $e) {
                $_SESSION['emptyField'] = True;
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            } catch (\LogicException $e){
                $_SESSION['invalidInput'] = True;
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            } catch (\Exception $e) {
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            }
        }
    }

    public function closeBankAccount(){
        $bank = new bankAccountModel();
        $accountName = $_POST['accountClose'];
        $user = new AccountModel();
        $userid = $user->findID($_SESSION['username']);
        $id = $bank->findID($accountName, $userid);
        $bank->deleteAccount($id);
        $view = new View('deletionComplete');
        echo $view->render();
    }
}
