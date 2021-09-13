<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteUser extends Model
{
	protected $table = 'favorite_users';

    private $nickname;

    private $user_id;

    private $favorite_user_id;

    private $favorite_user_category_id;

	protected $fillable = [
		'nickname',
		'user_id',
		'favorite_user_id',
		'favorite_user_category_id',
	];

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getFavoriteUserId()
    {
        return $this->favorite_user_id;
    }

    /**
     * @param mixed $favorite_user_id
     */
    public function setFavoriteUserId($favorite_user_id): void
    {
        $this->favorite_user_id = $favorite_user_id;
    }

    /**
     * @return mixed
     */
    public function getFavoriteUserCategoryId()
    {
        return $this->favorite_user_category_id;
    }

    /**
     * @param mixed $favorite_user_category_id
     */
    public function setFavoriteUserCategoryId($favorite_user_category_id): void
    {
        $this->favorite_user_category_id = $favorite_user_category_id;
    }


}