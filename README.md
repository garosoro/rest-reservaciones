# Rest-Reservaciones

Rest-Reservaciones es un sistema web básico cuya función es crear reservar de mesas para restaurantes.

## Features

- **Control de comensales**: Añadir, editar y eliminar datos de un comensal.
- **Control de mesas**: Añadir, editar y eliminar datos de una mesa.
- **Control de reservas**: Crear, editar y eliminar datos de una reserva.

---

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: Vue 3 with Vuetify
- **Base de Datos**: MySQL
- **Otras Herramientas**:
    - Inertia.js para integrar Vue 3 y Laravel en una sola aplicacion
    - Axios para llamadas a API
    - Laravel Factories y Seeders para información de pruebas

---

## Instalación

1. Clona el repositorio:

    ```bash
    git clone https://github.com/your-username/rest-reservaciones.git
    cd rest-reservaciones
    ```

2. Instala el backend y las dependencias:

    ```bash
    composer install
    ```

3. Instala las dependencias para el frontend:

    ```bash
    npm install
    ```

4. Crea el archivo de entorno:

    ```bash
    cp .env.example .env
    ```

    Actualiza el `.env` con los datos relacionados a tu instalación.

5. Crea y vincula la base de datos:

    Si deseas usar sqlite, puedes pasar al paso 6.

    Verifica que tengas la extenxion de PHP correspondiente activa en tu instalacion de PHP.

    Si deseas usar mysql, cambia las siguientes claves en el archivo .env

    ```bash
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1 (La direccion de tu host)
        DB_PORT=3306 (El puerto donde ejecuta el servicio de mysql)
        DB_DATABASE=Your-db-name
        DB_USERNAME=your-db-username
        DB_PASSWORD=yout-db-passord
    ```

6. Ejecuta la creacion de la tablas y la informacion de prueba:

    ```bash
    php artisan migrate --seed
    ```

7. Ejecuta el frontend:

    ```bash
    npm run dev
    ```

8. Ejecuta el backend:

    ```bash
    php artisan serve
    ```

9. Tu aplicacion estara disponible en `http://localhost:8000`.

---

## Uso

1. **Control de comensales**:

    - Corre a la seccion comensales para añadir, eliminar y editar información de comensales.

2. **Gestion de mesas**:

    - Corre a la seccion de mesas para crear, editar y eliminar.

3. **Gestion de reservar**:
    - Corre a la seccion de reservas para crear y eliminar reservas.

---

## Demo

Para ver una demostración, puedes entrar al siguente link:
https://rest-reservaciones-main-k4zlsg.laravel.cloud/

Accesos:
Usuario: admin@admin.com
Contraseña: password

Para una guia de uso, se recomienda ver el siguiete tutorial:
https://www.loom.com/share/8208a43303134309830e62e5a3e5156f?sid=957526b2-57f5-4c1b-83bd-df342a60f5de
