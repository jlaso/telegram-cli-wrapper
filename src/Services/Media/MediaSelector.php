<?php

namespace TelegramCliWrapper\Services\Media;

class MediaSelector
{

    const MIN = 0;
    const MAX = 15;

    public static function getRandomPicture()
    {
        return __DIR__ . '/images/' . rand(self::MIN, self::MAX) . '.jpg';
    }

}