<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteUserCategory extends Model
{
	protected $table = 'favorite_user_categories';

    private $user_id;

    private $name;

	protected $fillable = [
		'user_id',
		'name',
	];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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

}