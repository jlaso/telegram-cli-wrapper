<?php

/**
 * @author: Joseluis Laso
 * based on the project https://github.com/zyberspace/php-telegram-cli-client
 */

namespace TelegramCliWrapper;

use TelegramCliWrapper\Models\Dialog;
use TelegramCliWrapper\Models\User;

class TelegramCliWrapper
{
    /** @var bool */
    protected $debug = false;
    protected $socket;
    /** @var string */
    protected $errorMessage = null;
    /** @var int */
    protected $errorCode = null;

    /** argument types */
    const PEER_ARG = 1;
    const MSG_ARG = 2;
    const PEER_LIST_ARG = 3;
    const TITLE_ARG = 4;
    const NAME_ARG = 5;
    const SURNAME_ARG = 6;
    const PHONE_ARG = 7;
    const NUMBER_ARG = 8;

    /**
     * TelegramCliWrapper constructor.
     */
    public function __construct($telegramSocket = "unix:///tmp/tg.sck", $debug = false)
    {
        $this->debug = $debug;
        $this->socket = stream_socket_client($telegramSocket);
        if (false === $this->socket) {
            throw new \Exception(sprintf('Could not connect to socket "%s"', $telegramSocket));
        }
        // need to get dialog list in order that the rest of functions work
        $this->dialog_list();
    }

    public function __destruct()
    {
        fclose($this->socket);
    }

    protected $map = array(
        'status_online' => array(),
        'status_offline' => array(),
        'contact_list' => array(),
        'get_self' => array(),
        'stats' => array(),
        'quit' => array(),
        'send_typing' => array(),
        //sends message to the peer
        'msg' => array(
            'peer' => self::PEER_ARG,
            'msg' => self::MSG_ARG,
        ),
        'del_contact' => array(
            'peer' => self::PEER_ARG,
        ),
        'delete_msg' => array(
            'msg_seqno' => self::MSG_ARG,
        ),
        'delete_history' => array(
            'peer' => self::PEER_ARG,
        ),
        'chat_delete_user' => array(
            'peer' => self::PEER_ARG,
        ),
        'mark_read' => array(
            'peer' => self::PEER_ARG,
        ),
        'dialog_list' => array(),
        'chat_info' => array(
            'peer' => self::PEER_ARG,
        ),
        'user_info' => array(
            'peer' => self::PEER_ARG,
        ),
        'block_user' => array(
            'peer' => self::PEER_ARG,
        ),
        'unblock_user' => array(
            'peer' => self::PEER_ARG,
        ),
        'broadcast' => array(
            'peer_list' => self::PEER_LIST_ARG,
            'msg' => self::MSG_ARG,
        ),
        'create_group_chat' => array(
            'title' => self::TITLE_ARG,
            'peer_list' => self::PEER_LIST_ARG,
        ),
        'rename_chat' => array(
            'peer' => self::PEER_ARG,
            'title' => self::TITLE_ARG,
        ),
        'set_profile_name' => array(
            'name' => self::NAME_ARG,
            'surname' => self::SURNAME_ARG,
        ),
        'add_contact' => array(
            'phone' => self::PHONE_ARG,
            'name' => self::NAME_ARG,
            'surname' => self::SURNAME_ARG,
        ),
        'chat_add_user' => array(
            'chat' => self::NAME_ARG,
            'user' => self::NAME_ARG,
            'num_of_msgs' => self::NUMBER_ARG,
        ),
        'rename_contact' => array(
            'peer' => self::PEER_ARG,
            'name' => self::NAME_ARG,
            'surname' => self::NAME_ARG,
        ),
        'history' => array(
            'peer' => self::PEER_ARG,
            'limit' => self::NUMBER_ARG,
        ),
    );

    /**
     * @param $name
     * @param array $args
     * @return bool|mixed
     * @throws \Exception
     */
    public function __call($name, $args = array())
    {
        $method = trim(strtolower($name));
        $this->debug("Executing command '%s(%s)'\n", $method, implode(",",$args));

        if (isset($this->map[$method])) {
            $methodDef = $this->map[$method];
            if (count($args) != count($methodDef)) {
                throw new \Exception(sprintf("wrong number of parameters passed to '%s'", $method));
            }
            $arguments = array();
            if (count($methodDef)) {
                foreach ($methodDef as $fld => $methodArg) {
                    $this->debug("'%s' => '%s'\n", $fld, $methodArg);
                    switch ($methodArg) {
                        case self::PEER_ARG:
                            $arguments[] = $this->escapePeer(array_shift($args));
                            break;
                        case self::NUMBER_ARG:
                            $arguments[] = intval(array_shift($args));
                            break;
                        case self::PHONE_ARG:
                            $phoneNumber = array_shift($args);
                            $processedPhoneNumber = preg_replace('%[^0-9]%', '', (string)$phoneNumber);
                            if (empty($processedPhoneNumber)) {
                                throw new \Exception("Number '%s' is not a real number", $phoneNumber);
                            }
                            $arguments[] = $processedPhoneNumber;
                            break;
                        case self::PEER_LIST_ARG:
                            $arguments[] = $this->formatPeerList(array_shift($args));
                            break;
                        case self::MSG_ARG:
                        case self::NAME_ARG:
                        case self::SURNAME_ARG:
                        case self::TITLE_ARG:
                            $arguments[] = $this->escapeStringArgument(array_shift($args));
                            break;
                    }
                }
            }
            switch ($method) {
                case "chat_delete_user":   // chat_delete_user needs to send twice the user name to be sure of the deletion
                    $a = $arguments[] = array_shift($arguments);
                    $arguments[] = $a;
                    break;
            }
            $result = $this->execCommand($method, $arguments);
            $this->debug("Result of '%s' is '%s'\n", $method, $result);

            return $result;
        } else {
            throw new \Exception(sprintf("Unrecognized '%s' invoked\n", $method));
        }
    }

    protected function debug()
    {
        if (!$this->debug) {
            return;
        }
        $args = func_get_args();
        if (count($args) === 1) {
            print $args[0];
            return;
        }
        $format = array_shift($args);
        vprintf($format, $args);
    }

    /**
     * @return string
     */
    protected function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return int
     */
    protected function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param $method
     * @param array $args
     * @return bool|mixed
     */
    protected function execCommand($method, $args = array())
    {
        $command = $method . ' ' . implode(' ', $args);
        $this->debug("::execCommand:: Executing command '%s'\n", $command);
        fwrite($this->socket, str_replace("\n", '\n', $command) . PHP_EOL);
        $answer = fgets($this->socket); //"ANSWER $bytes" or false if an error occurred
        if (is_string($answer)) {
            if (substr($answer, 0, 7) === 'ANSWER ') {
                $bytes = ((int)substr($answer, 7)) + 1; //+1 because the json-return seems to miss one byte
                if ($bytes > 0) {
                    $bytesRead = 0;
                    $jsonString = '';
                    //Run fread() till we have all the bytes we want
                    //(as fread() can only read a maximum of 8192 bytes from a read-buffered stream at once)
                    do {
                        $jsonString .= fread($this->socket, $bytes - $bytesRead);
                        $bytesRead = strlen($jsonString);
                    } while ($bytesRead < $bytes);
                    $json = json_decode($jsonString);
                    if (!isset($json->error)) {
                        //Reset error-message and error-code
                        $this->errorMessage = null;
                        $this->errorCode = null;
                        //For "status_online" and "status_offline"
                        if (isset($json->result) && $json->result === 'SUCCESS') {
                            return true;
                        }
                        //Return json-object
                        return $json;
                    } else {
                        $this->errorMessage = $json->error;
                        $this->errorCode = $json->error_code;
                    }
                }
            }
        }
        return false;
    }

    protected function escapeStringArgument($argument)
    {
        return '"' . addslashes($argument) . '"';
    }

    protected function escapePeer($peer)
    {
        return str_replace(' ', '_', $peer);
    }

    protected function formatPeerList(array $peerList)
    {
        return implode(' ', array_map(array($this, 'escapePeer'), $peerList));
    }

    protected function formatFileName($fileName)
    {
        return $this->escapeStringArgument(realpath($fileName));
    }

    /**
     * return the list of users with active dialogs
     *
     * @return User[]
     */
    public function getDialogList()
    {
        $dialogList = $this->dialog_list();

        return User::fromArray($dialogList);
    }

    /**
     * return the list of dialogs of the peer passed
     * recover messages mark it as read
     *
     * @param string $peer
     * @return Dialog[]
     */
    public function getHistory($peer, $numMsgs)
    {
        $history = $this->history($peer, $numMsgs);

        return Dialog::fromArray($history);
    }

    /**
     * @return User
     */
    public function whoAmI()
    {
        return new User($this->get_self());
    }

    /**
     * @param string $peer
     * @return User
     */
    public function getUserInfo($peer)
    {
        return new User($this->user_info($peer));
    }

    /**
     * @return User[]
     */
    public function getContactList()
    {
        return User::fromArray($this->contact_list());
    }

}