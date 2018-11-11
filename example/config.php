<?php

/*
 * This file is part of the `informaticauco/oauth2-client`.
 *
 * Copyright (C) 2018 by Sergio Gómez <sergio@uco.es>
 *
 * This code was developed by Universidad de Córdoba (UCO https://www.uco.es)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Dotenv\Dotenv;
use Uco\OAuth2\Client\Provider\Uco;

require_once __DIR__.'/../vendor/autoload.php';

// Carga la configuración
$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

session_start();

// Crea el provider de la Universidad para OAuth2
// El provider es necesario para poder realizar todas las fase de autenticación
$provider = new Uco([
    'clientId' => getenv('UCO_CLIENT_ID'),
    'clientSecret' => getenv('UCO_CLIENT_SECRET'),
    'redirectUri' => getenv('UCO_REDIRECT_URI'),
]);

// Crea el sistema de plantilla para este ejemplo
$template = new Mustache_Engine([
    'loader' => new Mustache_Loader_FilesystemLoader(__DIR__.'/views/', ['extension' => 'html']),
]);

// Seguridad
header('Content-Type', 'text/plain');

return [
    'provider' => $provider,
    'template' => $template,
];
