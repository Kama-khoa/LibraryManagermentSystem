<?php
session_start();
require 'vendor/autoload.php';
require 'controllers/abstract/Controller.php';
require 'controllers/AuthorController.php';
require 'controllers/AuthController.php';
require 'controllers/BookController.php';
require 'controllers/BookConditionController.php';
require 'controllers/CategoryController.php';
require 'controllers/UserController.php';
require 'controllers/FineController.php';
require 'controllers/LoanController.php';
require 'controllers/PermissionController.php';
require 'controllers/PublisherController.php';
require 'controllers/ReservationController.php';
require 'controllers/RoleController.php';
require 'controllers/DefaultController.php';
require 'controllers/MemberController.php';
require 'controllers/StatisticsController.php';
require 'controllers/CartController.php';

$model = isset($_GET['model']) ? $_GET['model'] : 'default';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

$public_routes = [
    'default' => ['index'],
    'auth' => ['login', 'register', 'register_success']
];

$is_public_route = isset($public_routes[$model]) && in_array($action, $public_routes[$model]);

if (!isset($_SESSION['user_id']) && !$is_public_route) {
    header('Location: index.php?model=auth&action=login');
    exit();
}


switch ($model) {
    case 'statistic':
        $controller = new StatisticsController();
        break;
    case 'default':
        $controller = new DefaultController();
        break;
    case 'role':
        $controller = new RoleController();
        break;
    case 'permission':
        $controller = new PermissionController();
        break;
    case 'user':
        $controller = new UserController();
        break;
    case 'book':
        $controller = new BookController();
        break;
    case 'book_condition':
        $controller = new BookConditionController();
        break;
    case 'fine':
        $controller = new FineController();
        break;
    case 'reservation':
        $controller = new ReservationController();
        break;
    case 'auth':
        $controller = new AuthController();
        break;
    case 'author':
        $controller = new AuthorController();
        break;
    case 'loan':
        $controller = new LoanController();
        break;
    case 'publisher':
        $controller = new PublisherController();
        break;
    case 'category':
        $controller = new CategoryController();
        break;
    case 'member':
        $controller = new MemberController();
        break;
    case 'cart':
        $controller = new CartController();
         break;
    default:
        $controller = new DefaultController();
        break;
}

switch ($action) {
    case 'admin_dashboard':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller->admin_dashboard();
    case 'create':
        // if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
        //     header('Location: index.php?model=default&action=index');
        //     exit();
        // }
        $controller->create();
        break;
    case 'member':
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] == 3) {
            header('Location: index.php?model=default&action=index');
            exit();
        }
        $controller->member();
        break;
    case 'edit':
        $controller->edit($id);
        break;
    case 'delete':
        $controller->delete($id);
        break;
    case 'show':
        $controller->show($id);
        break;
    case 'change_password':
        $controller->change_password($id);
        break;
    case 'detail':
        $controller->show($id);
        break;
    case 'login':
        $controller->login();
        break;
    case 'register':
        $controller->register();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'register_success':
        $controller->register_success();
        break;
    case 'list':
        $controller->List($id);
        break;
    case 'loadmore':
        $controller->loadmore();
        break;
    case 'checkout':
         $controller->checkout();
        break;
    case 'handle_reservation':
        $controller->handle_reservation();
        break;
    case 'delete_book':
        $controller->delete_book($id);
        break;
    case 'fines':
        $controller->fines($id);
        break;
    case 'pay':
        $controller->pay();
        break;
    case 'member_create':
        $controller->member_create();
        break;
    case 'update_status':
        $controller->update_status($id);
        break;
    case 'export':
        $controller->export();

    case 'member_create':
        $controller->member_create();
        break;
    case 'cart_history':
        $controller-> history_borrow();
        break;   
    
        break;
    case 'search':
        $controller->search();
        break;
    default:
        $controller->index();
        break;
}
?>