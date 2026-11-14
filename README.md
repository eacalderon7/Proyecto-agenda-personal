# Agenda Personal de Contactos

Aplicación de Agenda personal desarrollada con PHP y MySQL. Permite registrar usuarios, iniciar sesión y administrar contactos personales mediante operaciones CRUD.

## Tecnologías utilizadas
- PHP 8.1 o superior.
- MySQL 5.7 o superior / MariaDB compatible.
- HTML5 y CSS3.
- PDO para la conexión y consultas a la base de datos.
- Apache mediante XAMPP.

## Requisitos
- XAMPP instalado.
- Apache y MySQL activos.
- Navegador web actualizado.
- Editor de código (recomendado: Visual Studio Code).

## Instalación en XAMPP
1. Copie la carpeta `agenda_personal` dentro de `C:\xampp\htdocs\`.
2. Abra el panel de XAMPP e inicie los servicios Apache y MySQL.
3. Ingrese a `http://localhost/phpmyadmin`.
4. Seleccione la pestaña **Importar** y cargue el archivo `database.sql`.
5. Revise `config/conexion.php`. En una instalación XAMPP estándar, el usuario suele ser `root` y la contraseña está vacía. Cambie estos datos si su instalación utiliza otros valores.
6. Abra `http://localhost/agenda_personal/`.
7. Registre un usuario e inicie sesión para comenzar a utilizar la agenda.

## Funcionalidades
- Registro de usuarios con validación de correo único.
- Contraseñas almacenadas mediante `password_hash()` y verificadas con `password_verify()`.
- Inicio y cierre de sesión.
- Panel privado con información de la cuenta.
- Crear, consultar, editar y eliminar contactos.
- Confirmación antes de eliminar un contacto.
- Restricción de acceso a contactos por propietario.

## Seguridad implementada
- Consultas preparadas PDO para reducir el riesgo de inyección SQL.
- Verificación de sesión en páginas privadas.
- Regeneración del identificador de sesión al iniciar sesión.
- Cookies de sesión `HttpOnly` y `SameSite=Lax`; atributo `Secure` cuando se utiliza HTTPS.
- Token CSRF en formularios que modifican información.
- Validación de datos en el servidor.
- Escape de salida HTML mediante `htmlspecialchars()`.
- Las operaciones de consulta, edición y eliminación verifican el `id_usuario` del contacto.

## Prueba de aislamiento entre usuarios
1. Registre dos cuentas diferentes.
2. Inicie sesión con la primera cuenta y agregue un contacto.
3. Cierre sesión e ingrese con la segunda cuenta.
4. Compruebe que el contacto de la primera cuenta no aparece.
5. Intente abrir la URL de edición usando el ID del contacto de la primera cuenta. La aplicación debe responder que no se encontró el contacto.
6. Compruebe que la segunda cuenta tampoco puede eliminarlo.

## Estructura
- `config/`: conexión a MySQL.
- `includes/`: autenticación, funciones y componentes comunes.
- `css/`: estilos de la aplicación.
- Archivos PHP de registro, inicio de sesión y CRUD.
- `database.sql`: creación de la base de datos y sus tablas.

## Nota para despliegue
Este proyecto está preparado para aprendizaje y ejecución local. Para producción se deben configurar credenciales fuera del código, HTTPS obligatorio, políticas de seguridad del servidor, copias de seguridad, registro de errores y controles adicionales de operación.
