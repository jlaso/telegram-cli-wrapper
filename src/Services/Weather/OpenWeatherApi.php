<?php

namespace TelegramCliWrapper\Services\Weather;

use TelegramCliWrapper\Config;

class OpenWeatherApi
{
    /** @var mixed  */
    protected $weatherInfo;

    public function __construct()
    {
        $cacheData = __DIR__ . '/../../../data/weather-cached-data.json';

        if (!file_exists($cacheData) || (filemtime($cacheData) < (intval(date("U")) - 15*60*60))) {
            $config = Config::getInstance()->get('weather');
            $city = $config['city'];
            $country = $config['country'];
            $appid = $config['appid'];
            $info = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q={$city},{$country}&APPID={$appid}");
            file_put_contents($cacheData, $info);
        }

        $this->weatherInfo = json_decode(file_get_contents($cacheData), true);
    }

    /**
     * @return array
     */
    public function getWeatherInfo()
    {
        return $this->weatherInfo;
    }

    public function getWeatherInfoAsString()
    {
        if (!is_array($this->weatherInfo)){
            return "Seems that openWeathermap is not configured correctly";
        }

        return sprintf(
            "In %s (%s) weather is %s - %s\nWind speed is %s.\nTemperature right now is %dºC\nHumidity is %s",
            $this->weatherInfo['name'],
            $this->weatherInfo['sys']['country'],
            $this->weatherInfo['weather'][0]['main'],
            $this->weatherInfo['weather'][0]['description'],
            $this->weatherInfo['wind']['speed'],
            round($this->weatherInfo['main']['temp'] - 273, 0),
            $this->weatherInfo['main']['humidity']
        );
    }


}