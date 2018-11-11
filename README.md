# Proveedor de identidad de la Universidad de Córdoba para OAuth 2.0 Client


Este paquete provee soporte para autenticación OAuth 2.0 para el paquete [league/oauth2-client](https://github.com/thephpleague/oauth2-client) de PHP.

## Antes de empezar

Para poder usar este paquete debe solicitar credenciales de acceso al [Servicio de Informática](https://www.uco.es/servicios/informatica/) de la Universidad de Córdoba.

## Instalación

Para instalar esta librería, use composer:

```
composer require informaticauco/oauth2-uco
```

## Uso

El uso es similar a cualquier proveedor de `league/oauth2-client`, pero usando `Uco\OAuth2\Client\Provider\Uco` como proveedor de servicio.

## Código de ejemplo

```php
<?php

$provider = new Uco\OAuth2\Client\Provider\Uco([
    'clientId'          => '{client-id}',
    'clientSecret'      => '{client-secret}',
    'redirectUri'       => 'https://servidor/redirect',
]);

// Si no tenemos código de autorización, solicitamos uno
if (!isset($_GET['code'])) {

    // Usamos el proveedor de la UCO para conseguir la URL de autorización
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Leemos el código de estado de la petición y lo guardamos en la sesión
    $_SESSION['oauth2state'] = $provider->getState();

    // Redireccionamos al usuario al sistema de autenticación de la universidad
    header('Location: ' . $authorizationUrl);
    exit;

// Comprobamos que el código de estado concuerda para evitar ataques CSRF
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');
// El código es válido
} else {

    try {

        // Intentamos obtener un token de acceso con el código de autorización.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo $accessToken->getToken() . "\n";
        echo $accessToken->getRefreshToken() . "\n";
        echo $accessToken->getExpires() . "\n";
        echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

        // Con el access token podemos acceder a los datos del usuario
        $resourceOwner = $provider->getResourceOwner($accessToken);
        echo "¡Hola {$resourceOwner->getId()}!";

        // El usuario está logueado, podemos usar $token o crear nuestras
        // propias variables de sesión.
        
        // Redireccionamos al usuario a la página de la aplicación
        header('Location: /');
        exit;

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Si ocurre un fallo al acceder a los datos del usuario
        exit($e->getMessage());

    }
}
```

## Refrescar un token (opcional)

Si es necesario acceder a una API y refrescar el token de acceso, se puede usar un código similar a este:

```php
<?php

$provider = new Uco\OAuth2\Client\Provider\Uco([
    'clientId'          => '{client-id}',
    'clientSecret'      => '{client-secret}',
    'redirectUri'       => 'https://servidor/redirect',
]);

$existingAccessToken = getAccessTokenFromYourDataStore(); // $token del código anterior

if ($existingAccessToken->hasExpired()) {
    $newAccessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $existingAccessToken->getRefreshToken()
    ]);
}
```
