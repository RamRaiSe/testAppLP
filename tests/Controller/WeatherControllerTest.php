<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('#weather-info');
    }

    public function testLogs(): void
    {
        $client = static::createClient();
        $client->request('GET', '/__logs');

        self::assertResponseIsSuccessful();
    }
}
