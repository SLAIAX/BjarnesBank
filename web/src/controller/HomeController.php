<?php
namespace agilman\a2\controller;

session_start();
use agilman\a2\view\View;
use agilman\a2\model\{AccountModel, AccountCollectionModel};
/**
 * Class HomeController
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class HomeController extends Controller
{
    /**
     * Account Index action
     */
    public function indexAction()       //Implement collection model here
    {
        if($_SESSION["access"] == 1) {
            $user = new AccountModel();
            $id = $user->findID($_SESSION['username']);

            $accounts = $user->getAccounts($id);

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
        $user = new AccountModel();
        $id = $user->findID($_SESSION['username']);
        $accounts = $user->getAccounts($id);
        $view = new View('closeBankAccountPage');
        echo $view->addData('accounts', $accounts)->render();
    }

    public function typeAccountIndex(){
        $view = new View('accountTypesPage');
        echo $view->render();
    }

    public function TransferIndexAction(){
        $user = new AccountModel();
        $id = $user->findID($_SESSION['username']);
        $accounts = $user->getAccounts($id);
        $view = new View('transferPage');
        echo $view->addData('accounts', $accounts)->render();
    }

    public function PaymentIndexAction()
    {
        $user = new AccountModel();
        $id = $user->findID($_SESSION['username']);
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
