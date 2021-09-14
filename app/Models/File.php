<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	protected $table = 'files';

    private $filename;

    private $sender_id;

    private $receiver_id;

	protected $fillable = [
		'filename',
		'sender_id',
		'receiver_id',
	];

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename): void
    {
        $this->filename = $filename;
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

}