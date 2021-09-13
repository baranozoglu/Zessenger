<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
	protected $table = 'logins';

    private $user_id;

	protected $fillable = [
		'user_id',
	];

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