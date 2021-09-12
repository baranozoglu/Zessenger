<?php
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\PasswordController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\BlacklistController;
use App\Controllers\FavoriteUserController;
use App\Controllers\FavoriteUserCategoryController;
use App\Controllers\FavoriteMessageController;
use App\Controllers\LoginController;
use App\Controllers\MessageController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', HomeController::class . ':index')->setName('home');

$app->get('/users', UserController::class . ':getUsers')->setName("getAllUsers");

$app->group('', function ($group) {
	$group->get('/auth/signup', AuthController::class . ':getSignUp')->setName('auth.signup');
	$group->post('/auth/signup', AuthController::class . ':postSignUp');
	$group->get('/auth/signin', AuthController::class . ':getSignIn')->setName('auth.signin');
	$group->post('/auth/signin', AuthController::class . ':postSignIn');
})->add(new GuestMiddleware($container));

$app->group('', function ($group) {
    $group->get('/getMessages', MessageController::class . ':getMessagesBySenderAndReceiver')->setName('getMessages');
    $group->post('/sendMessage', MessageController::class . ':addMessage')->setName('sendMessages');
});

$app->group('', function ($group) {
    $group->get('/getBlacklistByUserId', BlacklistController::class . ':getBlacklistByUserId')->setName('getBlacklistByUserId');
    $group->post('/addUserToBlacklist', BlacklistController::class . ':addUserToBlacklist')->setName('addUserToBlacklist');
});

$app->group('', function ($group) {
    $group->get('/getFavoriteMessagesBySenderAndReceiver', FavoriteMessageController::class . ':getFavoriteMessagesBySenderAndReceiver')->setName('getFavoriteMessagesBySenderAndReceiver');
    $group->post('/addFavoriteMessage', FavoriteMessageController::class . ':addFavoriteMessage')->setName('addFavoriteMessage');
});

$app->group('', function ($group) {
    $group->get('/getFavoriteUserCategoriesByUserId', FavoriteUserCategoryController::class . ':getFavoriteUserCategoriesByUserId')->setName('getFavoriteUserCategoriesByUserId');
    $group->post('/addFavoriteUserCategory', FavoriteUserCategoryController::class . ':addFavoriteUserCategory')->setName('addFavoriteUserCategory');
});

$app->group('', function ($group) {
    $group->get('/getFavoriteUsersByUserId', FavoriteUserController::class . ':getFavoriteUsersByUserId')->setName('getFavoriteUsersByUserId');
    $group->get('/getFavoriteUsersByCategoryId', FavoriteUserController::class . ':getFavoriteUsersByCategoryId')->setName('getFavoriteUsersByCategoryId');
    $group->post('/addFavoriteUser', FavoriteUserController::class . ':addFavoriteUser')->setName('addFavoriteUser');
});

$app->group('', function ($group) {
    $group->get('/getLoginsByUserId', LoginController::class . ':getLoginsByUserId')->setName('getLoginsByUserId');
    $group->post('/addLogin', LoginController::class . ':addLogin')->setName('addLogin');
});

$app->group('', function ($group) {
	$group->get('/auth/signout', AuthController::class . ':getSignOut')->setName('auth.signout');
	$group->get('/auth/password/change', PasswordController::class . ':getChangePassword')->setName('auth.password.change');
	$group->post('/auth/password/change', PasswordController::class . ':postChangePassword');
})->add(new AuthMiddleware($container));



