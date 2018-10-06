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
class PaymentController extends Controller
{
    public function indexAction()
    {
        $user = new AccountModel();
        $id = $user->findID($_SESSION['username']);

        $accounts = $user->getAccounts($id);

        $view = new View('paymentPage');
        echo $view->addData('accounts', $accounts)->render();
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
                $transaction->makeTransfer($toAccountID, $fromAccountID);

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
