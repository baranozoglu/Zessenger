<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
	protected $table = 'blacklist';

	private $user_id;

    private $blocked_user_id;

	protected $fillable = [
		'user_id',
		'blocked_user_id',
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

    /**
     * @return mixed
     */
    public function getBlockedUserId()
    {
        return $this->blocked_user_id;
    }

    /**
     * @param mixed $blocked_user_id
     */
    public function setBlockedUserId($blocked_user_id): void
    {
        $this->blocked_user_id = $blocked_user_id;
    }


}