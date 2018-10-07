<?php
namespace agilman\a2\controller;
session_start();

use agilman\a2\model\BankAccountModel;
use agilman\a2\model\AccountModel;
use agilman\a2\model\TransactionCollectionModel;
use agilman\a2\model\TransactionModel;
use agilman\a2\view\View;
use const Grpc\CALL_ERROR_TOO_MANY_OPERATIONS;

/**
 * Class TransactionController
 * @package agilman\a2\controller
 */
class TransactionController extends Controller
{



    /**
     * View Transactions Action
     *
     * Get the Post Data
     * Initialise BankAccountModel
     * Find the Account Id with AccountName and Session UserID
     * Initialise Transaction Collection Model
     * Get a transaction Collection
     * Render Transaction Page and pass data to it
     *
     */
    public function transactionsIndexAction()
    {
        try {
            $accountName = $_POST['account'];

            $userID = $_SESSION['id'];
            $bank = new BankAccountModel(null, $userID);
            $id = $bank->findID($accountName);
            unset($bank);
            $collection = new TransactionCollectionModel($id);
            $transactions = $collection->getTransactions();
            $view = new View('transactionPage');
            echo $view->addData('transactions', $transactions)->render();
        } catch (\Exception $e) {
            $view = new View('transactionPage');
            echo $view->render();
        }
    }

    /**
     *
     * Make Transfer Action
     * (slightly different to payment)
     *
     * Checks If Session Action is Available ( to avoid double creates on refresh)
     * Initialise a bank Account Model
     * Find the account Ids associated with transfer using Post Data
     * Create a transaction model
     * Fill with post data and account Ids
     * Validate Transfer , Throw and handle appropriately if something goes wrong
     * Make Transfer, Perform the transfer
     * Save The transfer to DataBase
     * Render Transaction Complete Page
     *
     */
    public function makeTransferAction()
    {
        if ($_SESSION['actionAvailable']) {
            try {
                $id = $_SESSION['id'];
                $account = new BankAccountModel(null, $id);
                $toAccountID = $account->findID($_POST['accountTo']);
                $fromAccountID = $account->findID($_POST['accountFrom']);
                $transaction = new TransactionModel();
                $transaction->setAmount($_POST['amount']);
                $transaction->setDescription($_POST['description']);
                $transaction->setToAccountID($toAccountID);
                $transaction->setFromAccountID($fromAccountID);

                $transaction->validateTransfer();
                $transaction->makeTransfer();
                $transaction->save();

                $view = new View('transactionComplete');
                echo $view->render();
                $_SESSION['actionAvailable'] = false;
            } catch (\UnexpectedValueException $e) {
                $_SESSION['emptyField'] = true;
                $this->redirect('transferPage');
            } catch (\LogicException $e) {
                $_SESSION['validTransaction'] = false;
                $this->redirect('transferPage');
            }
        }
    }

    /**
     *
     * Make Payment Action
     * (slightly different to transfer)
     *
     * Checks If Session Action is Available ( to avoid double creates on refresh)
     * Initialise a bank Account Model
     * Find the account Ids associated with transfer using Post Data
     * Create a transaction model
     * Fill with post data and account Ids
     * Validate Transfer , Throw and handle appropriately if something goes wrong
     * Make Transfer, Perform the transfer
     * Save The transfer to DataBase
     * Render Payment Complete Page
     *
     */
    public function makePaymentAction()
    {
        if ($_SESSION['actionAvailable']) {
            try {
                $id = $_SESSION['id'];
                $account = new BankAccountModel(null, $id);
                $toAccountID = $_POST['accountTo'];


                $fromAccountID = $account->findID($_POST['accountFrom']);
                $transaction = new TransactionModel();
                $transaction->validateTransfer($toAccountID, $fromAccountID);
                $transaction->makeTransfer();
                $transaction->save();

                $view = new View('transactionComplete');
                echo $view->render();
                $_SESSION['actionAvailable'] = false;
            } catch (\UnexpectedValueException $e) {
                $_SESSION['emptyField'] = true;
                $this->redirect('paymentPage');
            } catch (\LogicException $e) {
                $_SESSION['validTransaction'] = false;
                $this->redirect('paymentPage');
            }
        }
    }
}
