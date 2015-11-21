<?php

namespace TelegramCliWrapper;

class Response
{
    public static function json($data = array())
    {
        header('Content-Type: application/json');
        echo json_encode($data, true);

        return 0;
    }

    public static function error($reason)
    {
        return self::json(array(
            'success' => false,
            'reason' => $reason,
        ));
    }

    public static function ok($data = array())
    {
        $data['success'] = true;

        return self::json($data);
    }
}