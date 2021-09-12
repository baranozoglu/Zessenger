<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    public $text;
    public $status_for_sender;
    public $status_for_receiver;
    public $sender_id;
    public $receiver_id;
    public $timestamps = false;

    protected $fillable = [
        'text',
        'status_for_sender',
        'status_for_receiver',
        'sender_id',
        'receiver_id',
    ];

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getStatusForSender()
    {
        return $this->status_for_sender;
    }

    /**
     * @param mixed $status_for_sender
     */
    public function setStatusForSender($status_for_sender): void
    {
        $this->status_for_sender = $status_for_sender;
    }

    /**
     * @return mixed
     */
    public function getStatusForReceiver()
    {
        return $this->status_for_receiver;
    }

    /**
     * @param mixed $status_for_receiver
     */
    public function setStatusForReceiver($status_for_receiver): void
    {
        $this->status_for_receiver = $status_for_receiver;
    }

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


}