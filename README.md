# Sistema de Gestión de Reservas de Recursos

Este proyecto implementa una API REST en Laravel para gestionar la reserva de recursos compartidos como salas de reuniones o equipos de oficina.

## Estructura y Diseño del Sistema

El sistema está construido siguiendo una arquitectura de capas con los siguientes componentes:

### Modelos
- `Resource`: Representa los recursos disponibles para reservar (salas, equipos)
- `Reservation`: Gestiona las reservas realizadas por los usuarios

### Repositorios
- `ResourceRepository`: Encapsula la lógica de acceso a datos para los recursos
- `ReservationRepository`: Encapsula la lógica de acceso a datos para las reservas

### Factorías
- `ReservationFactory`: Implementa la creación de diferentes tipos de reservas según el tipo de recurso

### Controladores
- `ResourceController`: Gestiona los endpoints relacionados con recursos
- `ReservationController`: Gestiona los endpoints relacionados con reservas

## Decisiones de Diseño

### Patrón Repository
Se implementó el patrón Repository para:
- Desacoplar la lógica de negocio del acceso a datos
- Facilitar la prueba unitaria de la lógica de negocio
- Permitir cambiar la fuente de datos sin afectar al resto de la aplicación
- Centralizar la lógica de consulta y manipulación de datos

### Patrón Factory
Se implementó el patrón Factory para:
- Crear diferentes tipos de reservas según el tipo de recurso
- Permitir extender fácilmente el sistema para nuevos tipos de recursos
- Centralizar la lógica de validación específica por tipo de recurso
- Simplificar la creación de objetos complejos

### Validación de Disponibilidad
La validación de disponibilidad se implementó en el `ResourceRepository` para:
- Garantizar que no se produzcan conflictos de horario
- Centralizar la lógica de verificación de disponibilidad
- Facilitar la reutilización de esta lógica en diferentes partes del sistema

## Seguridad
Se implementó autenticación básica por medio de tokens con la libreria sanctum, los endpoints se encuentran protegidos por medio del middleware `auth:sanctum`
- El usuario de prueba se encuentra en las semillas, por lo que debe ejecutar las semillas

Usuario de prueba:
```
email: 'test@example.com'
password: '12345678'
```

## Configuración e Instalación dev

### Requisitos
- Composer
- MySQL
- Laravel 12.x

### Pasos de Instalación

1. Clonar el repositorio
```bash
git clone https://github.com/jpaz932/reservation_management.git
cd resource-reservation-api
```

2. Instalar dependencias
```bash
composer install
```

3. Crear archivo con variables de entorno `.env` basado en el archivo `.env.example`

4. Configurar las variables de entorno para la base de datos. Ejemplo:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reservation_management
DB_USERNAME=root
DB_PASSWORD=
```

5. Ejecutar el siguiente comando para generar la clave de la aplicación.
```bash
php artisan key:generate
```

6. Ejecutar las migraciones para generar las tablas.
```bash
php artisan migrate
```

7. Ejecutar las semillas para algunos datos de pruebas.
```bash
php artisan db:seed
```

8. Para iniciar el servidor en desarrollo ejecutar
```bash
php artisan serve
```

## Pruebas
1. Ejecutar pruebas
```bash
php artisan test
```

## Documentación adicional
- En la carpeta del proyecto `/resources/documents/` se encuentra un archivo con la colección de postman que le servirá de ejemplo y guia para los endpoints.
- En la ruta `/api/documentation` se encuentra la documentación de los endpoints en `Swagger` para mayor claridad.