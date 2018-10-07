<?php
namespace agilman\a2\controller;

session_start();
use agilman\a2\view\View;
use agilman\a2\model\{AccountModel, BankAccountCollectionModel, BankAccountModel};

class HomeController extends Controller
{
    public function indexAction()       //Implement collection model here
    {
        if($_SESSION["access"] == 1) {
            $id = $_SESSION['id'];
            $collection = new BankAccountCollectionModel($id);
            $accounts = $collection->getAccounts();
            $view = new View('homePage');
            echo $view->addData('accounts', $accounts)->render();
        }
    }

    public function aboutusIndexAction(){
        $view = new View('aboutUsPage');
        echo $view->render();
    }

    public function transactionIndexAction(){
        $view = new View('transactionPage');
        echo $view->render();
    }

    public function logOutAction(){
        session_unset();
        $this->redirect('loginPage');
    }

    public function closeAccountIndex(){
        $user = new BankAccountModel();
        $id = $_SESSION['id'];
        $accounts = $user->getAccounts($id);
        $view = new View('closeBankAccountPage');
        echo $view->addData('accounts', $accounts)->render();
    }

    public function typeAccountIndex(){
        $view = new View('accountTypesPage');
        echo $view->render();
    }

    public function TransferIndexAction(){
        $user = new BankAccountModel();
        $id = $_SESSION['id'];
        $accounts = $user->getAccounts($id);
        $view = new View('transferPage');
        echo $view->addData('accounts', $accounts)->render();
    }

    public function PaymentIndexAction()
    {
        $user = new BankAccountModel();
        $id = $_SESSION['id'];
        $accounts = $user->getAccounts($id);
        $view = new View('paymentPage');
        echo $view->addData('accounts', $accounts)->render();
    }

    public function UserJoinIndexAction()
    {
        $view = new View('userJoinPage');
        echo $view->render();
    }
}
