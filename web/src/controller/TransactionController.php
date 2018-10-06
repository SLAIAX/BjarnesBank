<?php
namespace agilman\a2\controller;
session_start();

use agilman\a2\model\bankAccountModel;
use agilman\a2\model\AccountModel;
use agilman\a2\model\transactionModel;
use agilman\a2\view\View;
use const Grpc\CALL_ERROR_TOO_MANY_OPERATIONS;

/**
 * Class AccountController
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class TransactionController extends Controller
{


    public function viewTransactions()
    {
        $account = $_POST['account'];
        $transMod = new transactionModel();
        $transactions = $transMod->getTransactions($account);
        $view = new View('transactionPage');
        echo $view->addData('transactions', $transactions)->render();
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
                $transaction->validateTransfer($toAccountID, $fromAccountID);
                $transaction->makeTransfer();
                $transaction->save();

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




    public function makePaymentAction()
    {
        if ($_SESSION['actionAvailable']) {
            try {
                $account = new bankAccountModel();
                $toAccountID = $_POST['accountTo'];
                $user = new AccountModel();
                $id = $user->findID($_SESSION['username']);
                $fromAccountID = $account->findID($_POST['accountFrom'], $id);
                $transaction = new transactionModel();
                $transaction->validateTransfer($toAccountID, $fromAccountID);
                $transaction->makeTransfer();
                $transaction->save();

                $view = new View('transactionComplete');
                echo $view->render();
                $_SESSION['actionAvailable'] = False;
            } catch (\UnexpectedValueException $e) {
                $_SESSION['emptyField'] = True;
                $this->redirect('paymentPage');
            } catch (\LogicException $e) {
                $_SESSION['validTransaction'] = False;
                $this->redirect('paymentPage');
            }
        }
    }
}
