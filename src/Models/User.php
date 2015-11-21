<?php


namespace TelegramCliWrapper\Models;


class User extends BasicObject
{
    public $id;
    public $print_name;
    public $type;
    public $flags;
    public $last_name;
    public $first_name;
    public $phone;
    // the code to log in
    public $code;
    // indicates that the user has used the code to log in
    public $logged;

    public static function getTitles()
    {
        return sprintf(
            "|%-10s|%-5s|%-5s|%-20s|%-20s|%-30s|",
            "id",
            "type",
            "flags",
            "first name",
            "last name",
            "print name"
        );
    }

    public function __toString()
    {
        return sprintf(
            "|%-10d|%-5s|%-5d|%-20s|%-20s|%-30s|",
            $this->id,
            $this->type,
            $this->flags,
            $this->first_name,
            $this->last_name,
            $this->print_name
        );
    }


    /**
     * Converts an array returned by dialog_list into a User[]
     *
     * @param array $items
     * @return User[]
     */
    public static function fromArray($items = array())
    {
        $result = array();
        if ($items && count($items)) {
            foreach ($items as $item) {
                $result[] = new User($item);
            }
        }

        return $result;
    }

    /**
     * @param string $phone
     * @param string $firstName
     * @param string $lastName
     * @return User
     */
    public static function createUser($phone, $firstName, $lastName)
    {
        $user = new static();
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->phone = $phone;

        return $user;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->phone;
    }


}