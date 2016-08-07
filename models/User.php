<?php
use MongoDB\BSON\ObjectID;


/**
 * @property  type
 * @property  peers
 */
class User
{

    var $data;

    public static function find_by_id($id)
    {
        try {
            return new User(db()->users->findOne(['_id' => new ObjectID($id)]));
        } catch (Exception $e) {
            return null;
        }
    }

    public static function find_by($key, $val)
    {
        return new User(db()->users->findOne([$key => $val]));
    }

    public static function current()
    {
        global $current;
        return $current;
    }

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        return @$this->data->$key;
    }

    public function __set($key, $val)
    {
        $this->data = db()->users->findOneAndUpdate(['_id' => $this->_id], [$key => $val]);
    }

    public function isBlocked()
    {
        return $this->report && count($this->report) > 1;
    }

    public function avatar()
    {
        return 'img/IMG_9463.jpg';
    }

    public function peers()
    {
        $result = [];
        foreach ($this->peers as $peer)
            $result[] = User::find_by_id($peer);
        return $result;
    }

    public function get_messages($peer_id)
    {
        $peer = User::find_by_id($peer_id);

        $options = [
            '$sort' => [
                ['_id' => -1]
            ]
        ];

        $result = [];

        if ($peer->type != 'person') {

            $result = db()->dialogs->find(['to' => $peer_id], $options);

        } else {

            $result = db()->dialogs->find([
                '$or' => [
                    ['from' => $this->_id . '', 'to' => $peer_id],
                    ['to' => $this->_id . '', 'from' => $peer_id],
                ]
            ], $options);

        }

        return $result->toArray();
    }

    public function update($data, $options = [])
    {
        $this->data = db()->users->findOneAndUpdate(['_id' => $this->_id], $data, $options);
    }

    public function toArray()
    {
        return @[
            '_id' => $this->_id . '',
            'email' => $this->email,
            'password' => $this->password,
            'key' => $this->key,
        ];
    }

    public function generateOTP()
    {

        $binary_timestamp = pack('N*', 0) . pack('N*', 1470561544180);

        $my_key = $this->key;
        $my_key = base64_decode($my_key);

        $b = array();
        foreach (str_split($my_key) as $c)
            $b[] = sprintf("%08b", ord($c));

        $string = implode(array_map("chr", $b));
        $hash = hash_hmac('sha1', $binary_timestamp, $my_key, true);

        $offset = ord($hash[19]) & 0xf;

        $OTP = (
                ((ord($hash[$offset + 0]) & 0x7f) << 24) |
                ((ord($hash[$offset + 1]) & 0xff) << 16) |
                ((ord($hash[$offset + 2]) & 0xff) << 8) |
                (ord($hash[$offset + 3]) & 0xff)
            ) % pow(10, 6);
        return $OTP;
    }

    public function base32_decode($b32)
    {
        $lut = array("A" => 0, "B" => 1,
            "C" => 2, "D" => 3,
            "E" => 4, "F" => 5,
            "G" => 6, "H" => 7,
            "I" => 8, "J" => 9,
            "K" => 10, "L" => 11,
            "M" => 12, "N" => 13,
            "O" => 14, "P" => 15,
            "Q" => 16, "R" => 17,
            "S" => 18, "T" => 19,
            "U" => 20, "V" => 21,
            "W" => 22, "X" => 23,
            "Y" => 24, "Z" => 25,
            "2" => 26, "3" => 27,
            "4" => 28, "5" => 29,
            "6" => 30, "7" => 31
        );

        $b32 = strtoupper($b32);
        $l = strlen($b32);
        $n = 0;
        $j = 0;
        $binary = "";

        for ($i = 0; $i < $l; $i++) {

            $n = $n << 5;
            $n = $n + $lut[$b32[$i]];
            $j = $j + 5;

            if ($j >= 8) {
                $j = $j - 8;
                $binary .= chr(($n & (0xFF << $j)) >> $j);
            }
        }

        return $binary;
    }

    public function get_timestamp()
    {

        return time();
    }

//    public function makeSecretKey(){
//
//        echo 'in method';
//        $key='1234';
//        return $key;
//
//    }


}