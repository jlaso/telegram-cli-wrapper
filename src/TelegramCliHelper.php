<?php

namespace TelegramCliWrapper;

/**
 * Class TelegramCliHelper
 *
 * Allows to wrap the cli calls in a PHP class
 *
 * @package TelegramCliWrapper
 */
class TelegramCliHelper
{
    /** @var  array */
    protected $config;
    /** @var int */
    protected $pid = null;
    /** @var bool  */
    protected $debug = false;

    protected static $instance = null;

    /**
     * starts telegram-cli from the configuration file given on ../config/config.ini
     */
    protected function __construct()
    {
        $config = parse_ini_file(__DIR__ . "/../config/config.ini", true);
        $this->config = $config["cli"];
        $this->debug = isset($this->config['debug']) && $this->config['debug'];

        $cmd = sprintf("%s/bin/telegram-cli -k %s/tg-server.pub %s %s & echo $!", $this->config['path'], $this->config['path'], $this->config['params'], $this->config['socket']);
        if ($this->debug) {
            print "{$cmd}\n";
        }
        $lines = exec($cmd);
        $this->pid = intval($lines);
        if ($this->debug) {
            print "telegram-cli started with pid {$this->pid}\n";
        }
    }

    /**
     * @return TelegramCliHelper
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new TelegramCliHelper();
        }

        return self::$instance;
    }


    /**
     * returns the socket found on the configuration file to be used by TelegramCliWrapper easily
     *
     * @return string
     */
    public function getSocket()
    {
        return "unix://" . $this->config['socket'];
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * stops the telegram-cli process once the PHP program ends
     */
    function __destruct()
    {
        /**
         * have to called twice in order to kill all the telegram-cli instances created
         */
        exec("killall telegram-cli");
        exec("killall telegram-cli");
    }


}