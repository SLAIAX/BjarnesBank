<?php
use PHPRouter\RouteCollection;
use PHPRouter\Router;
use PHPRouter\Route;

$collection = new RouteCollection();

// example of using a redirect to another route
$collection->attachRoute(
    new Route(
        '/',
        array(
            '_controller' => 'agilman\a2\controller\LoginController::indexAction',
            'methods' => 'GET',
            'name' => 'loginPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/transactions',
        array(
            '_controller' => 'agilman\a2\controller\AccountController::viewTransactions',
            'methods' => 'POST',
            'name' => 'viewTransactions'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/userJoinPage',
        array(
            '_controller' => 'agilman\a2\controller\UserCreateController::indexAction',
            'methods' => 'GET',
            'name' => 'userJoinPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/home',
        array(
            '_controller' => 'agilman\a2\controller\HomeController::indexAction',
            'methods' => 'GET',
            'name' => 'homePage'
        )
    )
);


$collection->attachRoute(
    new Route(
        '/Transactions',
        array(
            '_controller' => 'agilman\a2\controller\HomeController::transactionAction',
            'methods' => 'GET',
            'name' => 'transaction'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/logOut',
        array(
            '_controller' => 'agilman\a2\controller\HomeController::logOutAction',
            'methods' => 'GET',
            'name' => 'logOut'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/loginPage/validate/',
        array(
            '_controller' => 'agilman\a2\controller\LoginController::validateAction',
            'methods' => 'POST',
            'name' => 'validateLogin'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/account/',
        array(
        '_controller' => 'agilman\a2\controller\AccountController::indexAction',
        'methods' => 'GET',
        'name' => 'accountIndex'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/account/create/',
        array(
        '_controller' => 'agilman\a2\controller\AccountController::createAction',
        'methods' => 'POST',
        'name' => 'accountCreate'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/account/delete/:id',
        array(
        '_controller' => 'agilman\a2\controller\AccountController::deleteAction',
        'methods' => 'GET',
        'name' => 'accountDelete'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/account/update/:id',
        array(
        '_controller' => 'agilman\a2\controller\AccountController::updateAction',
        'methods' => 'GET',
        'name' => 'accountUpdate'
        )
    )
);

$router = new Router($collection);
$router->setBasePath('/');
