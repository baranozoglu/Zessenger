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

$app->group('/auth', function ($group) {
	$group->get('/signup', AuthController::class . ':getSignUp');
	$group->post('/signup', AuthController::class . ':postSignUp');
	$group->get('/signin', AuthController::class . ':getSignIn');
	$group->post('/signin', AuthController::class . ':postSignIn');
})->add(new GuestMiddleware($container));

$app->group('/users', function ($group) {
    $group->get('/', UserController::class . ':getUsers');
    $group->delete('/{id}', UserController::class . ':delete');
});

$app->group('/messages', function ($group) {
    $group->get('/{sender_id}/{receiver_id}', MessageController::class . ':getMessagesBySenderAndReceiver');
    $group->post('', MessageController::class . ':addMessage');
    $group->put('', MessageController::class . ':addMessage');
    $group->delete('/{id}', MessageController::class . ':delete');
});

$app->group('/blocked-users', function ($group) {
    $group->get('/{user_id}', BlockedUserController::class . ':getBlockedUserByUserId');
    $group->post('', BlockedUserController::class . ':addBlockedUser');
    $group->put('', BlockedUserController::class . ':addBlockedUser');
    $group->delete('/{id}', BlockedUserController::class . ':delete');
});

$app->group('/favorite-messages', function ($group) {
    $group->get('/{user_id}', FavoriteMessageController::class . ':getFavoriteMessagesBySenderAndReceiver');
    $group->post('', FavoriteMessageController::class . ':addFavoriteMessage');
    $group->put('', FavoriteMessageController::class . ':addFavoriteMessage');
    $group->delete('/{id}', FavoriteMessageController::class . ':delete');
});

$app->group('/favorite-user-categories', function ($group) {
    $group->get('/{user_id}', FavoriteUserCategoryController::class . ':getFavoriteUserCategoriesByUserId');
    $group->post('', FavoriteUserCategoryController::class . ':addFavoriteUserCategory');
    $group->put('', FavoriteUserCategoryController::class . ':addFavoriteUserCategory');
    $group->delete('/{id}', FavoriteUserCategoryController::class . ':delete');
});

$app->group('/favorite-users', function ($group) {
    $group->get('/{user_id}', FavoriteUserController::class . ':getFavoriteUsersByUserId');
    $group->post('', FavoriteUserController::class . ':addFavoriteUser');
    $group->put('', FavoriteUserController::class . ':addFavoriteUser');
    $group->delete('/{id}', FavoriteUserController::class . ':delete');
});

$app->group('/logins', function ($group) {
    $group->get('/{user_id}', LoginController::class . ':getLoginsByUserId');
    $group->post('', LoginController::class . ':addLogin');
    $group->put('', LoginController::class . ':addLogin');
    $group->delete('/{id}', LoginController::class . ':delete');
});

$app->group('/auth', function ($group) {
	$group->get('/signout', AuthController::class . ':getSignOut');
	$group->get('/password/change', PasswordController::class . ':getChangePassword');
	$group->post('/password/change', PasswordController::class . ':postChangePassword');
})->add(new AuthMiddleware($container));



