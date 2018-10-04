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
class transferController extends Controller
{
    public function indexAction(){


        $user = new AccountModel();
        $id = $user->findID($_SESSION['username']);

        $accounts = $user->getAccounts($id);

        $view = new View('transferPage');
        echo $view->addData('accounts', $accounts)->render();
    }


    public function makeTransferAction()
    {

        if($_SESSION['actionAvailable']){

            $account = new bankAccountModel();
            $toAccountID = $account->findID($_POST['accountTo']);
            $fromAccountID = $account->findID($_POST['accountFrom']);
            $transaction = new transactionModel();
            if ($transaction->makeTransfer($toAccountID, $fromAccountID)) {
                $view = new View('transactionComplete');
                echo $view->render();
                $_SESSION['actionAvailable'] = False;
            } else {
                $this->redirect('transferPage');
            }
        }
    }
}
