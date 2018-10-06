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
    public function indexAction()
    {
        $user = new AccountModel();
        $id = $user->findID($_SESSION['username']);

        $accounts = $user->getAccounts($id);

        $view = new View('transferPage');
        echo $view->addData('accounts', $accounts)->render();
    }

    public function makeTransferAction()
    {
        if($_SESSION['actionAvailable']){
            try {
                $account = new bankAccountModel();
                $user = new AccountModel();
                $id = $user->findID($_SESSION['username']);
                $toAccountID = $account->findID($_POST['accountTo'], $id);
                $fromAccountID = $account->findID($_POST['accountFrom'], $id);
                $transaction = new transactionModel();
                $transaction->makeTransfer($toAccountID, $fromAccountID);

                $view = new View('transactionComplete');
                echo $view->render();
                $_SESSION['actionAvailable'] = False;
            }catch(\UnexpectedValueException $e){
                $_SESSION['emptyField'] = True;
                $this->redirect('transferPage');
            }catch(\LogicException $e){
                $_SESSION['validTransaction'] = False;
                $this->redirect('transferPage');
            }
        }
    }
}
