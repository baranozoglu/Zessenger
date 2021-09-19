<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'users';

    private $first_name;
    private $last_name;
    private $email;
    private $phone;
    private $username;
    private $password;
    private $photo_url;

    public $timestamps = false;

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'phone',
		'username',
		'password',
		'username',
		'photo_url',
	];

    /**
     * User constructor.
     * @param $first_name
     * @param $last_name
     * @param $username
     * @param $password
     */
/*    public function __construct($first_name, $last_name, $username, $password)
    {
        parent::__construct([$first_name, $last_name, $username, $password]);
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->password = $password;
    }*/

    public function setPassword($password)
	{
		$this->update([
			'password' => password_hash($password, PASSWORD_DEFAULT)
		]);
	}

	public function setFirstName($firstName)
	{
		$this->first_name = trim($firstName);
	}

	public function getFirstName()
	{
		return $this->first_name;
	}

	public function setLastName($lastName)
	{
		$this->last_name = trim($lastName);
	}

	public function getLastName()
	{
		return $this->last_name;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	public function getPhone()
	{
		return $this->phone;
	}

	public function setPhotoUrl($photo_url)
	{
		$this->photo_url = $photo_url;
	}

	public function getPhotoUrl()
	{
		return $this->photo_url;
	}

	public function getFullName()
	{
		return "$this->first_name $this->last_name";
	}

	public function getEmailVariables()
	{
		return [
			'full_name' => $this->getFullName(),
			'email' => $this->getEmail(),
		];
	}
}