<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	protected $table = 'files';

    private $filename;

    private $user_id;

    private $messaged_user_id;

	protected $fillable = [
		'filename',
		'user_id',
		'messaged_user_id',
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



}