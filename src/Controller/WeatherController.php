<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\Attribute\Required;

class WeatherController extends AbstractController
{
    #[Required]
    public KernelInterface $kernel;

    public function indexAction(): Response
    {
        return $this->render('weather/index.html.twig');
    }

    public function logsAction(): Response
    {
        $logFilePath = $this->kernel->getLogDir().'/weather.log';

        if (!file_exists($logFilePath)) {
            throw $this->createNotFoundException('Log file "weather.log" not found.');
        }

        return new BinaryFileResponse($logFilePath);
    }
}