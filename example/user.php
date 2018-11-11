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
$template = $config['template'];

/*
 * Comprobamos que tenemos token, en caso contrario volvemos a home.
 */
if (!empty($_SESSION['token'])) {
    $token = unserialize($_SESSION['token']);
}
if (empty($token)) {
    header('Location: /');
    exit;
}

try {
    /*
     * OAuth Fase 3
     *
     * Con este token podemos consultar los datos del usuario.
     * El token tiene caducidad. Si se quiere renovar consultar la documentación
     * de OAuth2 Client.
     */
    /** @var \Uco\OAuth2\Client\Provider\UcoResourceOwner $userDetails */
    $userDetails = $provider->getResourceOwner($token);
} catch (Exception $e) {
    exit('El error a leer los datos: '.$e->getMessage());
}

$values = array_map(function ($key, $value) {
    return ['key' => $key, 'value' => $value];
}, array_keys($userDetails->toArray()), $userDetails->toArray());

// Renderizamos la página
echo $template->render('user', ['id' => $userDetails->getId(), 'values' => $values]);
