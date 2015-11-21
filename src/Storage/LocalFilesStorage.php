<?php

namespace TelegramCliWrapper\Storage;

use TelegramCliWrapper\Models\BasicObject;
use TelegramCliWrapper\Models\User;

class LocalFilesStorage implements StorageInterface
{
    protected $path;
    protected $table;

    /**
     * LocalFilesStorage constructor.
     * @param string $table
     */
    public function __construct($table)
    {
        $this->table = $table;
        $this->path = __DIR__ . '/../../data/' . $table . '/';
        @mkdir($this->path, 0777, true);
    }

    /**
     * @param BasicObject $obj
     * @return bool
     */
    public function save(BasicObject $obj)
    {
        file_put_contents(
            $this->path . $obj->getId() . '.db',
            serialize($obj)
        );
    }

    /**
     * @param string $id
     * @return BasicObject
     */
    public function getById($id)
    {
        $fileName = $this->path .  $id . '.db';
        if (file_exists($fileName)) {
            $data = file_get_contents($fileName);

            return unserialize($data);
        }

        return null;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function remove($id)
    {
        $fileName = $this->path .  $id . '.db';
        if (file_exists($fileName)) {
            unlink($fileName);

            return true;
        }

        return false;
    }


}