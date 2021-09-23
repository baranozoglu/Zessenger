<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
	protected $table = 'logins';

    private $user_id;

    private $connection_id;

    protected $fillable = [
		'user_id',
		'connection_id',
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
    public function getConnectionId()
    {
        return $this->connection_id;
    }

    /**
     * @param mixed $connection_id
     */
    public function setConnectionId($connection_id): void
    {
        $this->connection_id = $connection_id;
    }

}