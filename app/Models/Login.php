<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
	protected $table = 'logins';

	public $user_id;

    public $timestamps = false;

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