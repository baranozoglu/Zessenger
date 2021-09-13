<?php
use App\Controllers\Auth\AuthController;
use App\Controllers\Auth\PasswordController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\BlockedUserController;
use App\Controllers\FavoriteUserController;
use App\Controllers\FavoriteUserCategoryController;
use App\Controllers\FavoriteMessageController;
use App\Controllers\LoginController;
use App\Controllers\MessageController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get('/', HomeController::class . ':index');

$app->get('/users', UserController::class . ':getUsers');

$app->group('/auth', function ($group) {
	$group->get('/signup', AuthController::class . ':getSignUp');
	$group->post('/signup', AuthController::class . ':postSignUp');
	$group->get('/signin', AuthController::class . ':getSignIn');
	$group->post('/signin', AuthController::class . ':postSignIn');
})->add(new GuestMiddleware($container));

$app->group('/messages', function ($group) {
    $group->get('', MessageController::class . ':getMessagesBySenderAndReceiver');
    $group->post('/add', MessageController::class . ':addMessage');
    $group->post('/update', MessageController::class . ':addMessage');
});

$app->group('/blocked-users', function ($group) {
    $group->get('', BlockedUserController::class . ':getBlockedUserByUserId');
    $group->post('/add', BlockedUserController::class . ':addBlockedUser');
    $group->post('/update', BlockedUserController::class . ':addBlockedUser');
});

$app->group('/favorite-messages', function ($group) {
    $group->get('', FavoriteMessageController::class . ':getFavoriteMessagesBySenderAndReceiver');
    $group->post('/add', FavoriteMessageController::class . ':addFavoriteMessage');
    $group->post('/update', FavoriteMessageController::class . ':addFavoriteMessage');
    //$group->delete('/delete', FavoriteMessageController::class . ':deleteFavoriteMessage');
});

$app->group('/favorite-user-categories', function ($group) {
    $group->get('', FavoriteUserCategoryController::class . ':getFavoriteUserCategoriesByUserId');
    $group->post('/add', FavoriteUserCategoryController::class . ':addFavoriteUserCategory');
    $group->post('/update', FavoriteUserCategoryController::class . ':addFavoriteUserCategory');
    //$group->delete('/delete', FavoriteUserCategoryController::class . ':deleteFavoriteUserCategory');
});

$app->group('/favorite-users', function ($group) {
    $group->get('', FavoriteUserController::class . ':getFavoriteUsersByUserId');
    $group->post('/add', FavoriteUserController::class . ':addFavoriteUser');
    $group->post('/update', FavoriteUserController::class . ':addFavoriteUser');
});

$app->group('/logins', function ($group) {
    $group->get('', LoginController::class . ':getLoginsByUserId');
    $group->post('/add', LoginController::class . ':addLogin');
    $group->post('/update', LoginController::class . ':addLogin');
});

$app->group('/auth', function ($group) {
	$group->get('/signout', AuthController::class . ':getSignOut');
	$group->get('/password/change', PasswordController::class . ':getChangePassword');
	$group->post('/password/change', PasswordController::class . ':postChangePassword');
})->add(new AuthMiddleware($container));



