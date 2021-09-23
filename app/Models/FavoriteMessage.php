<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMessage extends Model
{
	protected $table = 'favorite_messages';

	private $user_id;

    private $owner_id;

    private $messaged_user_id;

    private $message_id;

	protected $fillable = [
		'message_id',
        'user_id',
        'owner_id',
		'messaged_user_id',
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
    public function getOwnerId()
    {
        return $this->owner_id;
    }

    /**
     * @param mixed $owner_id
     */
    public function setOwnerId($owner_id): void
    {
        $this->owner_id = $owner_id;
    }

    /**
     * @return mixed
     */
    public function getMessagedUserId()
    {
        return $this->messaged_user_id;
    }

    /**
     * @param mixed $messaged_user_id
     */
    public function setMessagedUserId($messaged_user_id): void
    {
        $this->messaged_user_id = $messaged_user_id;
    }

    /**
     * @return mixed
     */
    public function getMessageId()
    {
        return $this->message_id;
    }

    /**
     * @param mixed $message_id
     */
    public function setMessageId($message_id): void
    {
        $this->message_id = $message_id;
    }

}