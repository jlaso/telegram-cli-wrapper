<?php

namespace TelegramCliWrapper\Models;


class Flags
{
    const BASIC = 256;
    const UNREAD = 1;

    protected $data;

    /**
     * Status constructor.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function isUnread()
    {
        return ($this->data & self::UNREAD) > 0;
    }

    public function isRead()
    {
        return !$this->isUnread();
    }


    function __toString()
    {
        return "[" . $this->data . "]" .
            ($this->isUnread() ? "Unread" : "Read") .
            "";
    }


}