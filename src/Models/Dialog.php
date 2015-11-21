<?php

namespace TelegramCliWrapper\Models;


class Dialog extends BasicObject
{

    public $event;
    public $id;
    /** @var  Flags */
    public $flags;
    public $service;
    public $out;
    /** @var  User */
    public $from;
    /** @var  string */
    public $text;
    /** @var  User */
    public $to;
    public $unread;
    public $date;

    /**
     * Transform an stdClass Object returned by history into a HistoryItem
     * @param $item
     */
    public function __construct($item)
    {
        parent::__construct($item);
        $this->flags = new Flags($item->flags);
        $this->from = new User($item->from);
        $this->to = new User($item->to);
    }

    public static function getTitles()
    {
        return sprintf(
            "|%-10s|%-10s|%-10s|%-10s|%-20s|%-20s|%-30s|%-10s|%s|",
            "id",
            "service",
            "event",
            "flags",
            "from",
            "to",
            "text",
            "date",
            "unread"
        );
    }

    public function __toString()
    {
        return sprintf(
            "|%-10d|%-10s|%-10s|%-10s|%-20s|%-20s|%-30s|%-10s|%s|",
            $this->id,
            $this->service,
            $this->event,
            $this->flags,
            $this->from->print_name,
            $this->to->print_name,
            $this->text,
            date("y.m.d-H:j:s", $this->date),
            $this->unread
        );
    }


    /**
     * Converts an array returned by history into a Dialog[]
     * @param array $items
     * @return Dialog[]
     */
    public static function fromArray($items = array())
    {
        $result = array();
        if ($items && count($items)) {
            foreach ($items as $item) {
                $result[] = new Dialog($item);
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


}