<?php

namespace TelegramCliWrapper\Storage;

use TelegramCliWrapper\Models\BasicObject;

interface StorageInterface
{
    /**
     * @param BasicObject $obj
     * @return bool
     */
    public function save(BasicObject $obj);
    /**
     * @param string $id
     * @return BasicObject
     */
    public function getById($id);
    /**
     * @param string $id
     * @return bool
     */
    public function remove($id);

}
