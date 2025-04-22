<?php

namespace App\Service;

use DateMalformedStringException;
use DateTime;
use JsonException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\Attribute\Required;

class WeatherService
{
    #[Required]
    public KernelInterface $kernel;

    /**
     * @throws DateMalformedStringException
     * @throws JsonException
     */
    public function transformData(string $data): array
    {
        $transformJsonData = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        $locationData = $transformJsonData['location'];
        $weatherData = $transformJsonData['current'];

        return [
            'city' => $locationData['name'],
            'country' => $locationData['country'],
            'temperature' => $weatherData['temp_c'],
            'condition' => $weatherData['condition']['text'],
            'humidity' => $weatherData['humidity'],
            'windSpeed' => $weatherData['wind_kph'],
            'lastUpdated' => (new DateTime($weatherData['last_updated']))->format('d-m-Y H:i:s')
        ];
    }

    public function storeWeatherInfo(array $data): void
    {
        $formattedData = 'City: '.$data['city'].' Country: '.$data['country'].' Temperature: '.$data['temperature'].' Condition: '.$data['condition'].' Last updated: '.$data['lastUpdated'].PHP_EOL;
        $file = fopen($this->kernel->getLogDir().'/weather.log', 'ab');
        fwrite($file, $formattedData);
        fclose($file);
    }
}