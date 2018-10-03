<?php
namespace agilman\a2\controller;

use agilman\a2\model\bankAccountModel;
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
        $account = new bankAccountModel();
        $account->save();
        $this->redirect('homePage');
    }
}
