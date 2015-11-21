<?php

namespace TelegramCliWrapper\Models;


abstract class BasicObject
{
    public function __construct($item = null)
    {
        if (!$item) {
            return;
        }
        foreach (get_object_vars($item) as $key => $value) {
            if (!is_object($value)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @return string
     */
    abstract public function getId();

}