# Challenge AIVO
Challenge - AIVO


## Descripción
Resolución del challenge propuesto, utilizando el framework Slim3

## Requerimientos
En mi computadora tengo Linux Ubuntu y PHP 5.6.
Sugiero utilizar esa versión de PHP ya que no comprobé como se comporta con otra versión mas moderna.  

## Instalación 

 Posicionado en el directorio raiz del proyecto, ejecutar

    php composer.phar install

En el mismo directorio, crear un archivo **.env** . En este archivo se deben confgurar las credenciales de Spotify.  

    SPOTIFY_CLIENT_ID=aaaaaaaaaaaaaaaaaa
    SPOTIFY_CLIENT_SECRET=bbbbbbbbbbbbbbbbbbbb

## Ejecutar
Posicionado en el subdirectorio *public*

    php -S localhost:8080
Con esto se levanta un servidor de desarrollo, que escucha en el puerto 8080.

## Rutas de API
### Ejemplos
Request:


    http://localhost:8080/api/v1/albums?q=patricio%20rey
 Respuesta: 
 ```json
 [
      {
        "name": "Momo Sampler",
        "released": "2000-06-29",
        "tracks": 11,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b2730d375318082e5537bf13249d",
          "width": 640
        }
      },
      {
        "name": "Último Bondi a Finisterre",
        "released": "1998-11-18",
        "tracks": 10,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b273baf227de76a645e5eea10c92",
          "width": 640
        }
      },
      {
        "name": "Luzbelito",
        "released": "1996-10-07",
        "tracks": 11,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b273b746ff7b1232a2e242f8b439",
          "width": 640
        }
      },
      {
        "name": "Cordero Atado",
        "released": "1993-04-12",
        "tracks": 12,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b2733da5671d0d7236acdc43c1d8",
          "width": 640
        }
      },
      {
        "name": "Lobo Suelto",
        "released": "1993-04-12",
        "tracks": 13,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b273074392d2ae6b119d67289def",
          "width": 640
        }
      },
      {
        "name": "En Directo",
        "released": "1992-12-14",
        "tracks": 12,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b273b5dec152072fc86f5b3370a2",
          "width": 640
        }
      },
      {
        "name": "La Mosca y la Sopa",
        "released": "1991-09-27",
        "tracks": 10,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b2736b26c788ddc971ca6176a650",
          "width": 640
        }
      },
      {
        "name": "¡Bang! ¡Bang!... Estás Liquidado",
        "released": "1989-07-04",
        "tracks": 9,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b273eb35b5a4084fb41414b60d5b",
          "width": 640
        }
      },
      {
        "name": "Un Baión para el Ojo Idiota",
        "released": "1988-09-05",
        "tracks": 8,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b2736c8e6627c4284ce37835a9e5",
          "width": 640
        }
      },
      {
        "name": "Oktubre",
        "released": "1986-01-10",
        "tracks": 9,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b273c7c1ffa44473871a6f004786",
          "width": 640
        }
      },
      {
        "name": "Gulp!",
        "released": "1985-04-22",
        "tracks": 11,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b2737e2966b7a98d7c3723e53b21",
          "width": 640
        }
      },
      {
        "name": "Tatuado en la Sien (En Vivo)",
        "released": "2020-08-25",
        "tracks": 9,
        "cover": {
          "height": 640,
          "url": "https://i.scdn.co/image/ab67616d0000b27346e16bbb819c308b42abb19a",
          "width": 640
        }
      }
    ]
```

## Explicación
En el directorio *public* se encuentra el archivo index.php
En el mismo se inicializa la app y se especifica como debe responder a los request de la ruta 
`api/v1/albums?q=[algo] .`
Nunca habia utilizado Slim, asi que lo realizé de acuerdo a lo que pude ver en la documentación y cosas que encontré en linea. 
Entiendo que se puede organizar mejor el codigo, como por ejemplo tener un archivo por cada controller y no tener un archivo index.php gigante, pero dado el alcance del ejercicio no me pareció necesario hacer eso ahora.
En cambio si desacople lo respectivo a la comunicacion con la API de Spotify, si bien puede no ser necesario, me pareció bueno para hacer algo prolijo. 
Me costó hacer que se autocargara la clase.
 
Por otro lado, la API de Spotify proporciona un endpoint que nos permite hacer busquedas en general por artistas, albums, canciones:
`https://api.spotify.com/v1/search  `
lo que hice fue utilizar esta API para buscar al artista que viene en el request, de esta respuesta me quedo con el primer artista en esa lista.
Una vez obtenido el artista, puedo buscar toda la discografia con su IDARTIST.
Para esto, debo realizar un segundo request a otro endpoint:
`https://api.spotify.com/v1/artists/[IDARTIST]/albums`

Con este response de Spotify armo el response requerido por el challenge. 




