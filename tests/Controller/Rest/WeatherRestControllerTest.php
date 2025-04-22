<?php

namespace App\Tests\Controller\Rest;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherRestControllerTest extends WebTestCase
{
    public function testGetWeather(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/weather', ['city' => 'Kyiv']);

        self::assertResponseIsSuccessful();
    }

    public function testGetWeatherBadCity(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/weather', ['city' => 'fsafsafsaf']);

        self::assertResponseStatusCodeSame(400);
    }

    public function testGetWeatherWithoutParams(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/weather');

        self::assertResponseStatusCodeSame(404);
    }
}
