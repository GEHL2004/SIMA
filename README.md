# Sistema Integral de Médicos de Aragua (SIMA)

[![Licencia](https://img.shields.io/badge/Licencia-MIT-red.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/PHP-8.0+-4f5b93.svg)](https://www.php.net/)
[![XAMPP](https://img.shields.io/badge/XAMPP-8.0+-FB7A24.svg)](https://www.apachefriends.org/)

## 📋 Descripción

**SIMA** (Sistema Integral de Médicos de Aragua) es una aplicación web desarrollada en PHP nativo con arquitectura MVC personalizada, diseñada para la gestión integral de profesionales médicos en el estado Aragua, Venezuela.

El sistema permite administrar de manera eficiente el registro, actualización y consulta de información de médicos, incluyendo sus especialidades, subespecialidades, datos de contacto, formación académica, documentos, deportivos y mucho más.

## ✨ Características Principales

### Gestión de Médicos
- Registro completo de profesionales médicos
- Edición y actualización de información personal y profesional
- Eliminación lógica (cambio de estado) de registros
- Carga masiva de médicos mediante archivos Excel
- Visualización de perfiles individuales con todos los detalles
- Gestión de especialidades y subespecialidades
- Control de grados académicos
- Registro de cursos, diplomados y formación continua
- Asociación con disciplinas deportivas

### Catálogos del Sistema
- **Categorías**: Clasificación de médicos por categorías
- **Tipos de Práctica**: Modalidades de ejercicio profesional
- **Sistemas Corporales**: Áreas de especialización médica
- **Especialidades**: Especialidades médicas principales
- **Subespecialidades**: Subespecialidades vinculadas a especialidades
- **Deportes**: Disciplinas deportivas para medicina deportiva

### Sistema de Usuarios y Permisos
- Autenticación segura con sesiones PHP
- Sistema de permisos granular por niveles:
  - **Super Administrador**: Acceso completo al sistema
  - **Administrador**: Gestión de usuarios y configuraciones
  - **Coordinador**: Supervisión y coordinación
  - **Secretario**: Funciones de registro y consulta
- Control de acceso a nivel de:
  - Menú de navegación
  - Botones de interfaz
  - Controladores (backend)
  - Auditoría de intentos no autorizados

### Módulos de Mantenimiento
- **Gestión de Usuarios**: Creación, edición, eliminación de usuarios
- **Auditoría**: Registro completo de acciones realizadas en el sistema
- **Municipios y Parroquias**: Gestión de divisiones territoriales de Venezuela, Estado Aragua
- **Servicios de Base de Datos**: 
  - Backup manual de la base de datos
  - Restauración desde backup
  - Restauración a estado de fábrica
  - Monitor de estado de la base de datos

### Sistema de Reportes
- Reportes individuales de médicos (PDF)
- Reportes por municipio (PDF)
- Reportes por parroquia (PDF)
- Reportes por especialidad (PDF)
- Reportes por subespecialidad (PDF)
- Reportes por grado académico (PDF)
- Reportes por estado (PDF)
- Reportes por disciplina deportiva (PDF)

### Características Adicionales
- Interfaz responsiva con Bootstrap 5
- Tema claro/oscuro
- Gráficos estadísticos en el dashboard
- Alertas y notificaciones
- Manejo de imágenes y documentos
- Validaciones robustas en frontend y backend

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8.0+**: Lenguaje de programación del lado del servidor
- **MySQL/MariaDB**: Sistema de gestión de base de datos

### Dependencias (Composer)
- `vlucas/phpdotenv`: Gestión de variables de entorno
- `masterminds/html5`: Procesador HTML5
- `dompdf/dompdf`: Generación de documentos PDF
- `phpoffice/phpspreadsheet`: Manipulación de archivos Excel

### Frontend
- **Bootstrap 5**: Framework CSS para diseño responsivo
- **jQuery**: Biblioteca JavaScript para interacciones
- **DataTables**: Tablas interactivas con paginación y búsqueda
- **Chart.js / ApexCharts**: Gráficos y visualizaciones
- **SweetAlert2**: Ventanas modales atractivas
- **Font Awesome**: Iconos vectoriales

## 📁 Estructura del Proyecto

```
SIMA/
├── app/
│   ├── Config/           # Configuraciones del sistema
│   │   ├── Conexion.php         # Conexión a base de datos
│   │   ├── PermisosHelper.php   # Sistema de permisos
│   │   └── SecurityHelper.php   # Utilidades de seguridad
│   ├── Controllers/      # Controladores MVC
│   │   ├── AuthController.php
│   │   ├── CategoriasController.php
│   │   ├── EspecialidadesController.php
│   │   ├── HomeController.php
│   │   ├── MedicosController.php
│   │   ├── MedicosReportesController.php
│   │   ├── SubEspecialidadesController.php
│   │   ├── TiposPracticaController.php
│   │   └── mantenimientos/
│   │       ├── AuditoriaController.php
│   │       ├── DeportesController.php
│   │       ├── MunicipiosController.php
│   │       ├── ServiciosBaseDeDatosController.php
│   │       └── UsuariosController.php
│   └── Models/           # Modelos MVC
│       ├── Medicos.php
│       ├── Especialidades.php
│       ├── SubEspecialidades.php
│       └── mantenimientos/
├── public/
│   ├── assets/
│   │   ├── css/          # Estilos CSS
│   │   ├── js/           # Scripts JavaScript
│   │   ├── images/       # Imágenes del sistema
│   │   ├── json/         # Archivos JSON
│   │   └── webfonts/     # Fuentes web
│   └── views/            # Vistas del sistema
├── index.php             # Punto de entrada principal
├── composer.json         # Dependencias Composer
├── .env.example          # Ejemplo de variables de entorno
└── .htaccess             # Configuración Apache
```

## ⚙️ Requisitos del Sistema

- **Servidor Web**: Apache (XAMPP, WAMP, MAMP o similar)
- **PHP**: Versión 8.0 o superior
- **MySQL/MariaDB**: Versión 5.7 o superior
- **Composer**: Para gestión de dependencias PHP
- **Extensiones PHP requeridas**:
  - PDO
  - mysqli
  - fileinfo
  - mbstring
  - xml
  - zip
  - gd

## 🚀 Instalación

1. **Clonar o descargar el repositorio**:
   ```bash
   git clone https://github.com/GEHL2004/SIMA.git
   cd SIMA
   ```

2. **Instalar dependencias con Composer**:
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**:
   ```bash
   cp .env.example .env
   ```
   Edita el archivo `.env` con tu configuración de base de datos.

4. **Importar la base de datos**:
   - Crea una base de datos llamada `sima` (o la que configures en .env)
   - Importa el archivo SQL proporcionado

5. **Configurar el servidor web**:
   - Configura el document root hacia la carpeta `public/`
   - Asegúrate de que el módulo `mod_rewrite` está habilitado

6. **Iniciar el servidor**:
   - Inicia XAMPP o tu servidor Apache preferido
   - Accede al sistema desde `http://localhost/SIMA`

## 🔐 Credenciales de Acceso

Por defecto, el sistema crea un usuario Super Administrador(a).

**Credenciales por defecto:**
- Usuario: admin
- Contraseña: Admin//2025**

## 📊 Uso del Sistema

### Dashboard
El panel de control muestra estadísticas generales del sistema, incluyendo:
- Total de médicos registrados
- Médicos por especialidad
- Médicos por municipio
- Gráficos interactivos

### Gestión de Médicos
1. Accede al módulo de Médicos
2. Utiliza los filtros para buscar médicos específicos
3. Click en "Nuevo Médico" para registrar
4. Completa el formulario con los datos requeridos
5. Adjunta foto y documentos necesarios

### Reportes
1. Selecciona el tipo de reporte deseado
2. Aplica filtros (municipio, especialidad, etc.)
3. Genera el reporte en PDF o visualízalo en pantalla

## 🔧 Mantenimiento

### Backup de Base de Datos
El sistema incluye un módulo para realizar backups manuales desde la interfaz web.

### Restauración
Desde el panel de servicios de base de datos puedes restaurar un backup previo.

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.

## 👤 Autor

**Gabriel Enrique Hernández López**
- Whatsapp: https://wa.me/04128846190
- Email: morochogabriel2016+dev@gmail.com
- GitHub: https://github.com/GEHL2004

## 📞 Soporte

Para reportar errores o solicitar características, por favor crea un issue en el repositorio o contacta al desarrollador.

---

*Sistema Integral de Médicos de Aragua (SIMA)* - *Gestión médica para el estado Aragua, Venezuela*