<?php

namespace agilman\a2\controller;
session_start();
use agilman\a2\model\bankAccountModel;
use agilman\a2\model\AccountModel;
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


    public function viewTransactions()
    {
        $account = $_POST['account'];
        $transMod = new transactionModel();
        $transactions = $transMod->getTransactions($account);
        $view = new View('transactionPage');
        echo $view->addData('transactions', $transactions)->render();
    }

    public function createBankAccount()
    {
        if($_SESSION['actionAvailable']) {
            try {

                $accountname = $_POST['AccountName'];
                $account = new bankAccountModel();
                $id = $account->findUserID($_SESSION['username']);
                $account->validate($accountname, $id);
                $account->save($id);
                $this->redirect('homePage');
                $_SESSION['actionAvailable'] = False;

            } catch (\UnexpectedValueException $e) {
                // display and redirect
                $_SESSION['emptyField'] = True;
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            } catch (\Exception $e){
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            }
        }
    }

    public function closeBankAccount(){
        $bank = new bankAccountModel();
        $accountName = $_POST['accountClose'];
        $id = $bank->findID($accountName);
        $bank->deleteAccount($id);
        $view = new View('deletionComplete');
        echo $view->render();
    }

    public function closeAccountIndex(){
        $user = new AccountModel();
        $id = $user->findID($_SESSION['username']);
        $accounts = $user->getAccounts($id);
        $view = new View('closeBankAccountPage');
        echo $view->addData('accounts', $accounts)->render();
    }

    public function typeAccountIndex(){
        $view = new View('accountTypesPage');
        echo $view->render();
    }
}
