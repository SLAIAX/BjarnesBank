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
    public function indexAction()
    {
        if($_SESSION["access"] == 1) {
            $user = new AccountModel();
            $id = $user->findID($_SESSION['username']);

            $accounts = $user->getAccounts($id);




            $view = new View('homePage');
            echo $view->addData('accounts', $accounts)->render();
        }
    }

    public function transactionAction(){


        $view = new View('transactionPage');
        echo $view->render();
    }

    public function logOutAction(){

        session_unset();
        $this->redirect('loginPage');
    }

    //Take to page to input account information
    // eg name, type, init other vals to 0, link to user
    public function createAccountAction(){

    }

    // delete account from table and deallocate
    public function closeAccountAction(){

    }


    // takes to page where trans can be made between 2 accounts
    // drop down for each side
    // input description
    // validate trans is possible
    public function makeTransactionAction(){


    }




}
