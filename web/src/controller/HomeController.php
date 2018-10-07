<?php
namespace agilman\a2\controller;

session_start();
use agilman\a2\view\View;
use agilman\a2\model\{AccountModel, BankAccountCollectionModel, BankAccountModel};

/**
 * Class HomeController
 * @package agilman\a2\controller
 */
class HomeController extends Controller
{
    /**
     * Home Controller Index Action
     *
     * Checks if User is Logged in
     * Creates BankAccountCollectionModel
     * GetsAccounts
     * Renders Home Page and passes Accounts to be displayed
     */
    public function indexAction()
    {
        if ($_SESSION["access"] == 1) {
            try {
                $id = $_SESSION['id'];
                $collection = new BankAccountCollectionModel($id);
                $accounts = $collection->getAccounts();
                $view = new View('homePage');
                echo $view->addData('accounts', $accounts)->render();
            } catch (\Exception $e) {
                $this->redirect('loginPage');
            }
        }
    }

    /**
     *  About Us Index Action
     *
     * Renders About Us Page
     */
    public function aboutUsIndexAction()
    {
        $view = new View('aboutUsPage');
        echo $view->render();
    }


    /**
     * Log Out Action
     *
     * Kill's the session
     * Redirects to Login Page
     */
    public function logOutAction()
    {
        session_unset();
        $this->redirect('loginPage');
    }

    /**
     * Close Account Index Action
     *
     * Intialise a Bank Account Collection Model
     * Get the Accounts
     * Render Close Account Page, and Pass accounts to be displayed
     */
    public function closeAccountIndexAction()
    {
        try {
            $id = $_SESSION['id'];
            $collection = new BankAccountCollectionModel($id);
            $accounts = $collection->getAccounts();
            $view = new View('closeBankAccountPage');
            echo $view->addData('accounts', $accounts)->render();
        } catch (\Exception $e) {
            $this->redirect('homePage');
        }
    }

    /**
     * Type Account Index Action
     *
     * Renders the Account Types Page
     */
    public function typeAccountIndexAction()
    {
        $view = new View('accountTypesPage');
        echo $view->render();
    }

    /**
     * transfer Index Action
     *
     * Intialises two Bank Account Collection Models
     * Creates 2 collections of Accounts
     * Renders TransferPage and Passes through accounts to be displayed
     */
    public function transferIndexAction()
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
        } catch (\Exception $e) {
            $this->redirect('homePage');
        }
    }

    /**
     * Payment Index Action
     *
     * Intialises one Bank Account Collection Models
     * Creates 1 collection of Accounts
     * Renders PaymentPage and Passes through accounts to be displayed
     */
    public function paymentIndexAction()
    {
        try {
            $id = $_SESSION['id'];
            $collection = new BankAccountCollectionModel($id);
            $accounts = $collection->getAccounts();
            $view = new View('paymentPage');
            echo $view->addData('accounts', $accounts)->render();
        } catch (\Exception $e) {
            $this->redirect('homePage');
        }
    }

    /**
     * User Join Index Action
     *
     *  Renders User Join Page
     */
    public function userJoinIndexAction()
    {
        $view = new View('userJoinPage');
        echo $view->render();
    }
}
