<?php

namespace agilman\a2\controller;
session_start();

use agilman\a2\model\AccountModel;
use agilman\a2\model\BankAccountModel;
use agilman\a2\model\TransactionModel;
use agilman\a2\view\View;
use http\Exception\InvalidArgumentException;

/**
 * Class BankAccountController
 * @package agilman\a2\controller
 */
class BankAccountController extends Controller
{
    /**
     * Index Action
     *
     * Renders Bank Account Create Page
     */
    public function indexAction()
    {
        $view = new View('bankAccountCreatePage');
        echo $view->render();
    }

    /**
     *  Bank Account Create Action
     *
     * Checks if Create Action is Available( used to stop recreating accounts on refresh)
     * Intialises Bank Account with Post Data
     * Validates the bank account information, Throws is something is wrong and handled appropriately
     * Saves the Bank Account , Inserting into Database
     * Renders account Created Page
     * Sets action available false, so that if press refresh it will not create an identical entry
     */
    public function createAction()
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

    /**
     * Bank Account Delete Action
     *
     * Intialises a Bank Account
     * Finds Bank Account ID with User ID from Session
     * Sets Bank with AccountID
     * Deletes the Bank Account from DataBase
     * Renders Deletion Complete Page
     */
    public function deleteAction()
    {
        try {
            $userid = $_SESSION['id'];
            $bank = new BankAccountModel(NULL, $userid);
            $accountName = $_POST['accountClose'];
            $id = $bank->findID($accountName, $userid);
            $bank->setAccountID($id);
            $bank->deleteAccount();
            $view = new View('deletionComplete');
            echo $view->render();
        } catch (\Exception $e) {
            $this->redirect('closeAccountPage');
        }
    }
}
