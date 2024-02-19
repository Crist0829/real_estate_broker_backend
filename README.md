
## RM DREAMS - PRUEBA TÉCNICA - BACK END

[Ver Demo](https://real-state-broker-frontend.vercel.app)

Es una plataforma para la administración de inmuebles contiene (por ahora):

- Inicio de sesión (mediante tokens, cookies y sesiones).
- CRUD para los inmuebles.
- Administración de la cuenta y restablecimiento de la contraseña.
- Envío de correo (despchado por queues).
- Sistema de calificación por estrelas de los inmuebles.

## Requerimientos y documentación:
Todos los requerimientos iniciales están listados en el documento requerimientos.pdf (en documentos de este mismo repositorio)
así mismo está el documento documentación.pdf donde explico más detalladamente algunas funcionalidades y estructura.

### Instrucciones de instalación:

1)  Clona el repositorio:


```bash
   git clone https://github.com/Crist0829/real_estate_broker_backend.git
   cd /real_estate_broker_backend
```

2)  Instala las depencias de composer:


```bash
   git clone https://github.com/Crist0829/real_estate_broker_backend.git
   cd /real_estate_broker_backend
```

3)  Configuración del entorno:


```bash
   cp .env.example .env
```

4)  Creación de la key de la aplicación laravel: 

```bash
   php artisan key:generate
```

5)  Creación de las tablas en la base de datos: 

```bash
   php artisan migrate
```

Opcionalmente también puede registrar los roles de usuario y 3 usuarios iniciales (las credenciales están en el archivo app/database/seeders/UserSeeder.php)

```bash
   php artisan db:seed --class=RoleSeeder
   php artisan db:seed --class=UserSeeder
   php artisan db:seed --class=RoleUserSeeder
```

6)  Cree el acceso directo para almacenar los archivos


```bash
   php artisan storage:link
```

8)  Inicie el servidor

```bash
   php artisan serve
```








