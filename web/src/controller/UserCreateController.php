<?php
namespace agilman\a2\controller;
use agilman\a2\view\View;

/**
 * Class HomeController
 *
 * @package agilman/a2
 * @author  Andrew Gilman <a.gilman@massey.ac.nz>
 */
class UserCreateController extends Controller
{
    /**
     * Account Index action
     */
    public function indexAction()
    {
        $view = new View('userJoinPage');
        echo $view->render();
    }


    /**
     * Account Create action
     */
    public function validateAction()
    {

    }


}
