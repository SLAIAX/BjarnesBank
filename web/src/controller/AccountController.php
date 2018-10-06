<?php
namespace agilman\a2\controller;
session_start();

use agilman\a2\model\{AccountModel, AccountCollectionModel};
use agilman\a2\view\View;

/**
 * Class AccountController
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class AccountController extends Controller
{
    /**
     * Account Index action
     */
    public function indexAction()
    {
        $collection = new AccountCollectionModel();
        $accounts = $collection->getAccounts();
        $view = new View('accountIndex');
        echo $view->addData('accounts', $accounts)->render();
    }
    /**
     * Account Create action
     */
    public function createAction()
    {
        if($_SESSION['actionAvailable']) {
            try {
                $firstname = $_POST['firstname'];
                $lastname = $_POST['lastname'];
                $username = $_POST['username'];
                $password = $_POST['password'];


                $account = new AccountModel();
                $account->validate($firstname, $lastname, $username, $password);
                $account->save($firstname, $lastname, $username, $password);
                $view = new View('accountCreated');
                echo $view->render();
                $_SESSION['actionAvailable'] = False;
            } catch (\UnexpectedValueException $e){
                $_SESSION['emptyField'] = True;
                $view = new View('userJoinPage');
                echo $view->render();
            } catch (\LogicException $e) {
                $_SESSION['invalidInput'] = True;
                $view = new View('userJoinPage');
                echo $view->render();
            }
        }
    }

    /**
     * Account Delete action
     *
     * @param int $id Account id to be deleted
     */
    public function deleteAction($id)
    {
        (new AccountModel())->load($id)->delete();
        $view = new View('accountDeleted');
        echo $view->addData('accountId', $id)->render();
    }
    /**
     * Account Update action
     *
     * @param int $id Account id to be updated
     */
    public function updateAction($id)
    {
        $account = (new AccountModel())->load($id);
        $account->setName('Joe')->save(); // new name will come from Form data
    }

    public function viewTransactions()
    {
        $transactions = $_POST['account'];
        $trans = new transactionModel($transactions);
        $view = new View('transactionPage');
        echo $view->addData('tranz', $trans)->render();
    }
}
