<?php
use MongoDB\BSON\ObjectID;
use OTPHP\TOTP;


/**
 * @property string _id
 * @property string email
 * @property string password
 * @property string seed
 */
class User
{
    var $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function find_by($key, $val)
    {
        $user = db()->users->findOne([$key => $val]);
        if ($user != null)
            return new User($user);
        else return null;
    }

    public function __get($key)
    {
        return @$this->data->$key;
    }

    public function __set($key, $val)
    {
        $data[$key]=$val;
        $this->data = db()->users->findOneAndUpdate(['_id' => $this->_id], ['$set' => [$key => $val]]);
    }

    public function otp()
    {
        $totp = new TOTP('', \Base32\Base32::encode($this->seed), 60, 'sha1', 6);
        return $totp;
    }

}