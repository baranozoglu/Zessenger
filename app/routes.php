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
use App\Controllers\FileController;
use App\Middleware\AuthMiddleware;

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->group('/auth', function ($group) {
	$group->get('/signup', AuthController::class . ':getSignUp');
	$group->post('/signup', AuthController::class . ':postSignUp');
	$group->get('/signin', AuthController::class . ':getSignIn');
	$group->post('/signin', AuthController::class . ':postSignIn');
});

$app->group('/users', function ($group) {
    $group->get('', UserController::class . ':getUsers');
    $group->delete('/{id}', UserController::class . ':delete');
});

$app->group('/messages', function ($group) {
    $group->get('', MessageController::class . ':getMessages');
    $group->post('', MessageController::class . ':addMessage');
    $group->put('', MessageController::class . ':addMessage');
    $group->delete('/{id}', MessageController::class . ':delete');
})->add(new AuthMiddleware($container));

$app->group('/blocked-users', function ($group) {
    $group->get('', BlockedUserController::class . ':getBlockedUserByUserId');
    $group->post('', BlockedUserController::class . ':addBlockedUser');
    $group->put('', BlockedUserController::class . ':addBlockedUser');
    $group->delete('/{id}', BlockedUserController::class . ':delete');
})->add(new AuthMiddleware($container));

$app->group('/favorite-messages', function ($group) {
    $group->get('', FavoriteMessageController::class . ':getFavoriteMessagesBySenderAndReceiver');
    $group->post('', FavoriteMessageController::class . ':addFavoriteMessage');
    $group->put('', FavoriteMessageController::class . ':addFavoriteMessage');
    $group->delete('/{id}', FavoriteMessageController::class . ':delete');
})->add(new AuthMiddleware($container));

$app->group('/favorite-user-categories', function ($group) {
    $group->get('', FavoriteUserCategoryController::class . ':getFavoriteUserCategoriesByUserId');
    $group->post('', FavoriteUserCategoryController::class . ':addFavoriteUserCategory');
    $group->put('', FavoriteUserCategoryController::class . ':addFavoriteUserCategory');
    $group->delete('/{id}', FavoriteUserCategoryController::class . ':delete');
})->add(new AuthMiddleware($container));

$app->group('/favorite-users', function ($group) {
    $group->get('', FavoriteUserController::class . ':getFavoriteUsersByUserId');
    $group->post('', FavoriteUserController::class . ':addFavoriteUser');
    $group->put('', FavoriteUserController::class . ':addFavoriteUser');
    $group->delete('/{id}', FavoriteUserController::class . ':delete');
})->add(new AuthMiddleware($container));

$app->group('/logins', function ($group) {
    $group->get('/{user_id}', LoginController::class . ':getLoginsByUserId');
    $group->post('', LoginController::class . ':addLogin');
    $group->put('', LoginController::class . ':addLogin');
    $group->delete('/{id}', LoginController::class . ':delete');
});

$app->group('/files', function ($group) {
    $group->get('', FileController::class . ':getFile');
    $group->post('', FileController::class . ':fileUpload');
})->add(new AuthMiddleware($container));

$app->group('/auth', function ($group) {
	$group->get('/signout', AuthController::class . ':getSignOut');
	$group->get('/password/change', PasswordController::class . ':getChangePassword');
	$group->post('/password/change', PasswordController::class . ':postChangePassword');
})->add(new AuthMiddleware($container));



