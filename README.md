WeatherController.php - конкроллер с 2мя экшенами, для отображения страницы с погодой и вывода логов

WeatherRestController.php - рест контроллер с 1м АПИ экшеном для получения данных про погоду

WeatherService.php - сервис, в который я вынес логику, для преобразования данных со стороннего АПИ под свое и логирования

WeatherControllerTest.php - тесты для WeatherController.php

WeatherRestControllerTest.php - тесты для WeatherRestController.php

config/routes.yaml - роуты

var/log/weather.log - логи

.env[WEATHER_API_KEY] - сюда вынес ключ апи погоды