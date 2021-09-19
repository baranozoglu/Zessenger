<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    private $text;
    private $status_for_sender;
    private $status_for_receiver;
    private $sender_id;
    private $receiver_id;
    private $isEdited;
    private $parent_message_id;
    private $file_id;

    protected $fillable = [
        'text',
        'status_for_sender',
        'status_for_receiver',
        'sender_id',
        'receiver_id',
        'isEdited',
        'parent_message_id',
        'file_id'
    ];

    /**
     * Message constructor.
     * @param $text
     * @param $sender_id
     * @param $receiver_id
     */
    public function __construct($text, $sender_id, $receiver_id)
    {
        $this->text = $text;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
    }

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

    /**
     * @return mixed
     */
    public function getIsEdited()
    {
        return $this->isEdited;
    }

    /**
     * @param mixed $isEdited
     */
    public function setIsEdited($isEdited): void
    {
        $this->isEdited = $isEdited;
    }

    /**
     * @return mixed
     */
    public function getParentMessageId()
    {
        return $this->parent_message_id;
    }

    /**
     * @param mixed $parent_message_id
     */
    public function setParentMessageId($parent_message_id): void
    {
        $this->parent_message_id = $parent_message_id;
    }

    /**
     * @return mixed
     */
    public function getFileId()
    {
        return $this->file_id;
    }

    /**
     * @param mixed $file_id
     */
    public function setFileId($file_id): void
    {
        $this->file_id = $file_id;
    }


}