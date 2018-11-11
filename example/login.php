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

$config = require __DIR__.'/config.php';
/** @var \Uco\OAuth2\Client\Provider\Uco $provider */
$provider = $config['provider'];

if (!empty($_GET['error'])) {
    // Se ha devuelto un error, probablemente permiso denegado
    exit('Error: '.htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));
}

if (empty($_GET['code'])) {
    /*
     * OAuth Fase 1
     *
     * Si aún no tenemos un código de autorización, pedimos uno
     */
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;
}

if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    /*
     * Se comprueba que el estado que nos ha devuelto el servidor coincide con el que tenemos guardado.
     * En caso de que no cancelamos por probable ataque CSRF.
     */
    unset($_SESSION['oauth2state']);
    exit('Invalid state');
}

/*
 * OAuth Fase 2
 *
 * Tenemos un código de autorización, intentamos solicitar un token de acceso.
 * Si hay algún error, el método devolverá una excepción
 */
$token = $provider->getAccessToken('authorization_code', [
    'code' => $_GET['code'],
]);
$_SESSION['token'] = serialize($token);

// En este paso ya tendríamos un token de acceso, nos corresponde volver a la página
// que corresponda una vez terminado el proceso de logueado
header('Location: /user.php');
