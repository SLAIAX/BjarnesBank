<?php
namespace agilman\a2\controller;
session_start();

use agilman\a2\model\BankAccountModel;
use agilman\a2\model\AccountModel;
use agilman\a2\model\TransactionModel;
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
        $transMod = new TransactionModel();
        $transactions = $transMod->getTransactions($account);
        $view = new View('transactionPage');
        echo $view->addData('transactions', $transactions)->render();
    }

    public function makeTransferAction()
    {
        if($_SESSION['actionAvailable']){
            try {
                $account = new BankAccountModel();
                $id = $_SESSION['id'];
                $toAccountID = $account->findID($_POST['accountTo'], $id);
                $fromAccountID = $account->findID($_POST['accountFrom'], $id);
                $transaction = new TransactionModel();
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
                $account = new BankAccountModel();
                $toAccountID = $_POST['accountTo'];
             
                $id = $_SESSION['id'];
                $fromAccountID = $account->findID($_POST['accountFrom'], $id);
                $transaction = new TransactionModel();
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
