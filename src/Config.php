<?php

namespace TelegramCliWrapper;

class Config
{
    /** @var  Config */
    protected static $instance = null;
    /** @var  array */
    protected $config;

    /**
     * @return Config
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        $this->config = parse_ini_file(__DIR__ . '/../config/config.ini', true);
    }

    /**
     * @param $key
     * @return array
     */
    public function get($key)
    {
        return $this->config[$key];
    }
}