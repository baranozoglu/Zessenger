<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMessage extends Model
{
	protected $table = 'favorite_messages';

    private $sender_id;

    private $receiver_id;

    private $message_id;

	protected $fillable = [
		'sender_id',
		'receiver_id',
		'message_id',
	];

    /**
     * @return mixed
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * @param mixed $sender_id
     */
    public function setSenderId($sender_id): void
    {
        $this->sender_id = $sender_id;
    }

    /**
     * @return mixed
     */
    public function getReceiverId()
    {
        return $this->receiver_id;
    }

    /**
     * @param mixed $receiver_id
     */
    public function setReceiverId($receiver_id): void
    {
        $this->receiver_id = $receiver_id;
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