<?php
namespace agilman\a2\controller;

session_start();
use agilman\a2\view\View;
use agilman\a2\model\{AccountModel, BankAccountCollectionModel, BankAccountModel};

class HomeController extends Controller
{
    public function indexAction()       //Implement collection model here
    {
        if ($_SESSION["access"] == 1) {
            try {
                $id = $_SESSION['id'];
                $collection = new BankAccountCollectionModel($id);
                $accounts = $collection->getAccounts();
                $view = new View('homePage');
                echo $view->addData('accounts', $accounts)->render();
            } catch (\Exception $e){
                $this->redirect('loginPage');
            }
        }
    }

    public function aboutusIndexAction()
    {
        $view = new View('aboutUsPage');
        echo $view->render();
    }

    public function transactionIndexAction()
    {
        $view = new View('transactionPage');
        echo $view->render();
    }

    public function logOutAction()
    {
        session_unset();
        $this->redirect('loginPage');
    }

    public function closeAccountIndex()
    {
        try {
            $id = $_SESSION['id'];
            $collection = new BankAccountCollectionModel($id);
            $accounts = $collection->getAccounts();
            $view = new View('closeBankAccountPage');
            echo $view->addData('accounts', $accounts)->render();
        } catch (\Exception $e){
            $this->redirect('homePage');
        }
    }

    public function typeAccountIndex()
    {
        $view = new View('accountTypesPage');
        echo $view->render();
    }

    public function TransferIndexAction()
    {
        try {
            $id = $_SESSION['id'];
            $collectiona = new BankAccountCollectionModel($id);
            $collectionb = new BankAccountCollectionModel($id);
            $accountsa = $collectiona->getAccounts();
            $accountsb = $collectionb->getAccounts();
            $view = new View('transferPage');
            $view->addData('accountsa', $accountsa);
            echo $view->addData('accountsb', $accountsb)->render();
        } catch (\Exception $e){
            $this->redirect('homePage');
        }
    }

    public function PaymentIndexAction()
    {
        try {
            $id = $_SESSION['id'];
            $collection = new BankAccountCollectionModel($id);
            $accounts = $collection->getAccounts();
            $view = new View('paymentPage');
            echo $view->addData('accounts', $accounts)->render();
        } catch (\Exception $e){
            $this->redirect('homePage');
        }
    }

    public function UserJoinIndexAction()
    {
        $view = new View('userJoinPage');
        echo $view->render();
    }
}
