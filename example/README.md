# Proyecto de ejemplo

> Proyecto de ejemplo para iniciar sesión con los usuarios corporativos

## Instalación de dependencias

Dentro de este directorio ejecute:

    composer install

## Configuración del entorno

Copie la plantilla de configuración a un nuevo fichero:

    cp .env.dist .env

Edite el archivo `.env` y rellene las variables con los datos que le haya
facilitado el Servicio de Informática. La URL de redirección debe
proporcionarla Vd. conforme a los requerimientos de su aplicación.

## Arranque del proyecto

Inicie la aplicación web con el servidor interno de PHP:

    php -S localhost:8000
    
Y abra la siguiente dirección: [http://localhost:8000](http://localhost:8000).

Si la dirección de redirección es correcta, la aplicación debe mostrar todo el
proceso de autenticación.


 