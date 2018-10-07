<?php
namespace agilman\a2\controller;
session_start();

use agilman\a2\model\BankAccountModel;
use agilman\a2\model\AccountModel;
use agilman\a2\model\TransactionCollectionModel;
use agilman\a2\model\TransactionModel;
use agilman\a2\view\View;
use const Grpc\CALL_ERROR_TOO_MANY_OPERATIONS;

class TransactionController extends Controller
{
    public function viewTransactions()
    {
        try {
            $accountName = $_POST['account'];
            $userID = $_SESSION['id'];
            $bank = new BankAccountModel(NULL, $userID);
            $id = $bank->findID($accountName);
            unset($bank);
            $collection = new TransactionCollectionModel($id);
            $transactions = $collection->getTransactions();
            $view = new View('transactionPage');
            echo $view->addData('transactions', $transactions)->render();
        } catch (\Exception $e){
            $view = new View('transactionPage');
            echo $view->render();
        }
    }

    public function makeTransferAction()
    {
        if($_SESSION['actionAvailable']){
            try {
                $id = $_SESSION['id'];
                $account = new BankAccountModel(NULL, $id);
                $toAccountID = $account->findID($_POST['accountTo']);
                $fromAccountID = $account->findID($_POST['accountFrom']);
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
                $id = $_SESSION['id'];
                $account = new BankAccountModel(NULL, $id);
                $toAccountID = $_POST['accountTo'];


                $fromAccountID = $account->findID($_POST['accountFrom']);
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
