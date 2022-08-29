# club-management-docker

Aplicación creada para demostrar habilidades en el proceso de selección BACK END LaLiga Tech

## Comenzando 🚀

Para obtener una copia del proyecto ejecuta:

```
$ git clone https://github.com/agasdev/club-management-docker.git
```


### Instalación 🔧

Para la ejecución del proyecto será necesario tener instalado tando **docker**, como **docker-compose**


```
$ cd club-management-docker

$ make build 

$ make prepare

$ make start

$ make ssh-be

$ sf doctrine:database:create

$ sf doctrine:migrations:migrate
```

Para acceder a la aplicación vía navegador, accede a:
```
http://localhost:8000/
```

Para acceder a la base de datos, accede a:
```
http://localhost:8081/
```

Para acceder a mailhog, accede a:
```
http://localhost:8025/
```
*Desde aquí se podrá comprobar en elvío de emails*

## Ejecutando las pruebas ⚙️

```
$ make test
```

### Ejecutar los tests con "coverage"

*Este comando creara un directorio en el proyecto  con el nombre de "coverage", se puede acceder a el y abrir con el 
navegador los archivos index.html y dashboard.html*
```
$ make test-coverage
```

## Recursos 📚

En el directorio *.resources* se encuentra: 
* Un dump de la base de datos (*club-management.sql*) con pocos ejeplos
* Una coleccion de Postman (*club-manager-docker.postman_collection.json*) con todos los endpoints disponibles y sus datos en formato json, cabe destacar que son los 
datos utilizados para crear el dump, por lo que será necesario cambiar algunos datos para que los endpoints no devuelvan
errores.

## Construido con 🛠️

* [Symfony 5](https://symfony.com/doc/current/index.html) - El framework
* PHP 8.1

---