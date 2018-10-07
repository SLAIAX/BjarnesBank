<?php

namespace agilman\a2\controller;
session_start();

use agilman\a2\model\AccountModel;
use agilman\a2\model\BankAccountModel;
use agilman\a2\model\TransactionModel;
use agilman\a2\view\View;
use http\Exception\InvalidArgumentException;

class BankAccountController extends Controller
{
    public function indexAction()
    {
        $view = new View('bankAccountCreatePage');
        echo $view->render();
    }

    public function createBankAccount()
    {
        if ($_SESSION['actionAvailable']) {
            try {
                $id = $_SESSION['id'];
                $account = new BankAccountModel(NULL,$id);
                $account->setAccountName($_POST['AccountName']);
                $account->setType($_POST['AccountType']);
                $account->validate();
                $account->save();
                $this->redirect('homePage');
                $_SESSION['actionAvailable'] = false;
            } catch (\UnexpectedValueException $e) {
                $_SESSION['emptyField'] = true;
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            } catch (\LogicException $e) {
                $_SESSION['invalidInput'] = true;
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            } catch (\Exception $e) {
                $view = new View('bankAccountCreatePage');
                echo $view->render();
            }
        }
    }

    public function closeBankAccount()
    {
        try {
            $bank = new BankAccountModel();
            $accountName = $_POST['accountClose'];
            $userid = $_SESSION['id'];
            $id = $bank->findID($accountName, $userid);
            $bank->deleteAccount($id);
            $view = new View('deletionComplete');
            echo $view->render();
        } catch (\Exception $e) {
            $this->redirect('closeAccountPage');
        }
    }
}
