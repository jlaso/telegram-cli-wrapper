<?php

namespace TelegramCliWrapper\Models;


class BasicObject
{
    public function __construct($item)
    {
        foreach (get_object_vars($item) as $key => $value) {
            if (!is_object($value)) {
                $this->{$key} = $value;
            }
        }
    }
}