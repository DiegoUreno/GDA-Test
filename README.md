
# ¿Cómo instalar el servicio?

Se debe tener instalada la ultima version de Php (8.2.10) así como Composer 2.4.3 y MySQL 8.
Previo a la instalación del servicio se debe tener una base de datos creada en un servicio local llamada "mydb", los datos de acceso deberán ser proporcionados en el archivo .env.

### Comandos a correr
- Correr el siguiente comando:
  - composer update
- Dentro del directorio del proyecto correr:
  - php artisan migrate
- Se han agregado un par de seeders para poblar las tablas Communes y Regions, si así se desea se debe correr los siguientes comandos:
  - php artisan db:seed --class=RegionsTableSeeder
  - php artisan db:seed --class=CommunesTableSeeder
- Para finalizar se debe ingresar el comando:
  - php artisan serve


# Definición de servicios

El sistema cuenta con 3 servicios correspondientes a una API con arquitectura REST.

Para lograr acceder a los 3 servicios se debe pasar por un breve protocolo de autentificación en el que quien decida usar el sistema deberá registrarse mediante una peticion POST a la siguiente ruta: "http://localhost:8000/api/register" ej:
```json
POST http://localhost:8000/api/register

{
    "name": "user1",
    "email": "usuario@example.com",
    "password": "123456789"
}
```
Si el registro de usuario es exitoso se mostrará el siguiente mensaje:
```json
{
	"success": true,
	"message": "Usuario registrado con éxito"
}
```
De no ser exitoso se mostrará un JSON con el Success en False y mostrará el motivo del error:

```json
{
	"success": false,
	"message": "Fallo la validación de datos",
	"errors": {
		"email": [
			"The email has already been taken."
		]
	}
}
```

Posterior a esto se deberá validar las credenciales mediante una peticion POST en la siguiente ruta: "http://localhost:8000/api/login" 
```json
POST http://localhost:8000/api/login
{
    "email": "usuario@example.com",
    "password": "123456789"
}
```
De ser exitoso el inicio de sesión se mostrará un mensaje de success en True y un token generado por el servidor con las caracteristicas requeridas:
```json
{
	"success": true,
	"token": "a806a9349f96fabaa904753883f4fa4237d686c3"
}
```
Si las credenciales son incorrectas se mostrará el siguiente mensaje: 
```json
{
	"success": false,
	"message": "Credenciales incorrectas"
}
```

Al iniciar sesión se podrá acceder a los tres servicios los cuales son:
- Registro de Customers
- Visualizacion de customers mediante su email o DNI
- Borrado lógico del Customer

Para acceder a los 3 servicios se debe proveer el token de autentificación que se otorga en el Login, se debe adjuntar como Bearer token en la peticion, con el token y su prefijo Bearer.

Para acceder al primer servicio se debe realizar una petición POST  a la siguiente ruta: "http://localhost:8000/api/customers".

```json 
POST http://localhost:8000/api/customers
Authentication: Bearer {token}
{
    "dni": "1234578",
    "id_reg": 1,
    "id_com": 1,
    "email": "nuevocliente@example.com",
    "name": "Juan",
    "last_name": "Pérez",
    "address": "Calle Principal 123"
}
```
En caso de tener error de validación se mostrará el siguiente mensaje:
```json 
{
	"success": false,
	"message": "Fallo la validación de datos",
	"errors": {
		"dni": [
			"The dni has already been taken."
		],
		"email": [
			"The email has already been taken."
		]
	}
}
```

Para el siguiente servicio se deberá mandar una petición GET a la siguiente ruta: "http://localhost:8000/api/customers/{identificador}" donde identificador podrá ser el dni o el email.
```json 
GET http://localhost:8000/api/customers/nuevocliente@example.com
Authentication: Bearer {token}
```

Deberá arrojar un JSON con esta estructura

```json 
{
	"success": true,
	"customer": {
		"name": "Juan",
		"last_name": "Pérez",
		"address": "Calle Principal 123",
		"description_region": "Chile",
		"description_commune": "Santiago"
	}
}
```
Por ultimo, el tercer servicio es un borrado logico de customers, lo que significa que se borrará de las consultas más no de la base de datos como tal, para ello se debe mandar una peticion DELETE a la siguiente ruta: "http://localhost:8000/api/customers/{dni}"


```json 
DELETE http://localhost:8000/api/customers/1245789
Authentication: Bearer {token}
```
De realizarse satisfactoriamente la respuesta será un JSON que se verá de esta manera: 

```json 
{
	"success": true,
	"message": "Customer borrado con éxito"
}
```


## LOG

El sistema cuenta con un servicio de captura de logs tanto de entrada como de salida, la captura de logs de salida se verá afectada de así desearse mediante la variable de entorno.

```env
APP_DEBUG=
```

Al activarse el log de salida se registrará pero de ponerse en False se desactivará el log de salida. 

