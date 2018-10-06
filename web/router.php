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
            '_controller' => 'agilman\a2\controller\TransactionController::viewTransactions',
            'methods' => 'POST',
            'name' => 'viewTransactions'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/payments',
        array(
            '_controller' => 'agilman\a2\controller\PaymentController::indexAction',
            'methods' => 'GET',
            'name' => 'paymentPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/payments/make',
        array(
            '_controller' => 'agilman\a2\controller\PaymentController::makePaymentAction',
            'methods' => 'POST',
            'name' => 'makepayment'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/transfers',
        array(
            '_controller' => 'agilman\a2\controller\TransferController::indexAction',
            'methods' => 'GET',
            'name' => 'transferPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/transfers/make',
        array(
            '_controller' => 'agilman\a2\controller\transferController::makeTransferAction',
            'methods' => 'POST',
            'name' => 'maketransfer'
        )
    )
);


$collection->attachRoute(
    new Route(
        '/bank/account/',
        array(
            '_controller' => 'agilman\a2\controller\BankAccountController::indexAction',
            'methods' => 'GET',
            'name' => 'bankAccount'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/bank/account/close',
        array(
            '_controller' => 'agilman\a2\controller\HomeController::closeAccountIndex',
            'methods' => 'GET',
            'name' => 'closeAccountPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/bank/account/closeAction',
        array(
            '_controller' => 'agilman\a2\controller\BankAccountController::closeBankAccount',
            'methods' => 'POST',
            'name' => 'bankAccountCloseAction'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/bank/account/create',
        array(
            '_controller' => 'agilman\a2\controller\BankAccountController::createBankAccount',
            'methods' => 'POST',
            'name' => 'bankAccountCreate'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/bank/account/type',
        array(
            '_controller' => 'agilman\a2\controller\HomeController::typeAccountIndex',
            'methods' => 'GET',
            'name' => 'typeAccountPage'
        )
    )
);

$collection->attachRoute(
    new Route(
        '/aboutus',
        array(
            '_controller' => 'agilman\a2\controller\HomeController::aboutusIndexAction',
            'methods' => 'GET',
            'name' => 'aboutus'
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
            '_controller' => 'agilman\a2\controller\HomeController::transactionIndexAction',
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
