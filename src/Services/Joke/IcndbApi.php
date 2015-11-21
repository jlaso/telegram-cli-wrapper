<?php

namespace TelegramCliWrapper\Services\Joke;

class IcndbApi
{
    const URL = "http://api.icndb.com/jokes/random?limitTo=[nerdy,explicit]";

    public static function getAJoke()
    {
        $result = json_decode(file_get_contents(self::URL), true);

        if (isset($result['type']) && ($result['type'] == "success")) {
            return $result['value']['joke'];
        }

        return "I'm so sorry. I'm so sad to tell you something funny!";
    }

}