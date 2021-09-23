<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    private $text;
    private $status_for_sender;
    private $status_for_receiver;
    private $user_id;
    private $messaged_user_id;
    private $isEdited;
    private $parent_message_id;
    private $file_id;

    protected $fillable = [
        'text',
        'status_for_sender',
        'status_for_receiver',
        'user_id',
        'messaged_user_id',
        'isEdited',
        'parent_message_id',
        'file_id'
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