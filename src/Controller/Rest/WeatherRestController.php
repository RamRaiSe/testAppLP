<?php

namespace App\Controller\Rest;

use App\Service\WeatherService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Service\Attribute\Required;

class WeatherRestController extends AbstractController
{
    #[Required]
    public ContainerBagInterface $containerBag;
    #[Required]
    public HttpClientInterface $httpClient;
    #[Required]
    public WeatherService $weatherService;

    /**
     * @throws NotFoundExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \JsonException
     * @throws \DateMalformedStringException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    #[Route('/weather', name: 'api_weather', methods: ['GET'])]
    public function getWeatherAction(Request $request)
    {
        $weatherApiKey = $this->containerBag->get('weather_api_key');

        $city = $request->query->get('city');

        if (!$city) {
            return new JsonResponse(['error' => 'City not found!'], Response::HTTP_NOT_FOUND);
        }

        try {
            $response = $this->httpClient->request('GET', 'https://api.weatherapi.com/v1/current.json',
                [
                    'query' => [
                        'key' => $weatherApiKey,
                        'q' => $city
                    ]
                ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            return new JsonResponse(['error' => 'City not found!'], $response->getStatusCode());
        }

        $transformData = $this->weatherService->transformData($response->getContent());

        $this->weatherService->storeWeatherInfo($transformData);

        return new JsonResponse($transformData);
    }
}