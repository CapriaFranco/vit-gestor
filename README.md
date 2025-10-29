# 🏐 Torneo de Voley 2025 - Sistema de Registro

Sistema web para el registro de equipos en el Torneo de Voley 2025. Desarrollado con PHP, MySQL y JavaScript.

[![English](https://img.shields.io/badge/English-README.en.md-blue)](README_EN.md)
[![Version](https://img.shields.io/badge/version-v0.2.9-green)](CHANGELOG.md)
[![Changelog](https://img.shields.io/badge/changelog-ver%20historial-blue)](CHANGELOG.md)

## 📋 ¿Qué es?

Este es un sistema web que permite a los equipos registrarse para participar en el Torneo de Voley 2025. Los equipos pueden:

- Seleccionar su curso (1ro a 7mo)
- Elegir su división (A, B, C para básico; 1ra, 2da para superior)
- Registrar el nombre del equipo
- Seleccionar el sistema de juego (6:0, 4:2, 5:1)
- Agregar los integrantes con sus posiciones
- Elegir el color de remera (con validación de duplicados)
- Ver todos los equipos registrados organizados por ciclos

## 🚀 Instalación

### Requisitos
- PHP 8.2+ (probado con 8.2.12)
- MySQL 10.4+ / MariaDB 10.4+ (probado con MariaDB 10.4.32)
- Apache 2.4+ (probado con 2.4.58)
- XAMPP 3.3.0+ (recomendado)

### Pasos

1. **Clona el repositorio**
   ```bash
   git clone https://github.com/CapriaFranco/vit-gestor.git
   cd vit-gestor
   ```

2. **Configura la base de datos**
   ```bash
   # Copia el archivo de ejemplo
   cp php/db.example.php php/db.php
   
   # Edita con tus datos
   nano php/db.php
   ```

3. **Importa la base de datos**
   ```sql
   -- Para desarrollo local
   mysql -u root -p < sql/db.sql
   
   -- Para InfinityFree
   mysql -u usuario -p < sql/db-infinityfree.sql
   ```

4. **Configura el servidor**
   - **Local**: Coloca en `htdocs/vit-gestor/`
   - **InfinityFree**: Sube a la raíz de `htdocs`

## 🎯 Cómo funciona

### Flujo de registro

1. **Página principal** → Formulario de registro
2. **Selección de curso** → Se habilita la división
3. **Nombre del equipo** → Se habilita el sistema de juego
4. **Sistema de juego** → Se habilita la tabla de integrantes
5. **Integrantes** → Se habilita la selección de color
6. **Color de remera** → Se habilita el botón de envío
7. **Envío** → Página de éxito

### Validaciones

- ✅ Campos obligatorios
- ✅ Colores únicos por curso
- ✅ Posiciones válidas según sistema de juego
- ✅ Prevención de duplicados

### Tecnologías

- **Backend**: PHP 8.2.12
- **Base de datos**: MariaDB 10.4.32
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Servidor**: Apache 2.4.58 (mod_rewrite)
- **Entorno**: XAMPP 3.3.0

## 📁 Estructura

```
vit-gestor/
├── index.php              # Punto de entrada
├── .htaccess              # Configuración de rutas
├── php/
│   ├── db.example.php     # Ejemplo de configuración
│   ├── db.php             # Configuración real (NO subir)
│   ├── functions.php      # Funciones auxiliares
│   ├── clear_session.php  # Limpieza de sesiones
│   └── colores_ajax.php   # API de colores
├── pages/
│   ├── register/          # Página de registro
│   ├── registered/        # Página de éxito
│   ├── registered-teams/  # Visualización de equipos registrados
│   └── err/               # Página de error 404
├── assets/                # Imágenes y fuentes
├── scripts/               # JavaScript
├── styles/                # CSS
└── sql/                   # Scripts de base de datos
```

## 🔧 Configuración

### Base de datos local (XAMPP)

```php
// php/db.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vit_gestor_db";
```

### Base de datos InfinityFree

```php
// php/db.php
$servername = "sql309.infinityfree.com";
$username = "tu_usuario";
$password = "tu_password";
$dbname = "tu_base_datos";
```

## 🎨 Características

- **Diseño responsive** para móviles y desktop  
- **Interfaz moderna** con gradientes y animaciones  
- **Validación en tiempo real** de colores  
- **Sistema de posiciones** dinámico según el sistema de juego  
- **Páginas de éxito y error** personalizadas (403, 404, 500, offline)  
- **Ruteo amigable** con .htaccess  
- **Enlaces inteligentes de WhatsApp** (detecta móvil/desktop automáticamente)  
- **Compatibilidad cross-browser** optimizada con autoprefixer  
- **Validaciones de patrones** para caracteres especiales en español  
- **Longitud de campos** configurada  
  * Nombre del equipo: 3–100 caracteres  
  * Integrantes / Color: 4–100 caracteres  
- **Visualización de equipos registrados** organizados por ciclos (Básico/Superior)  
- **Indicadores visuales** para suplentes y capitanes  
- **Contadores de estadísticas** de equipos y personas registradas  
- **Tablas optimizadas** con scroll horizontal en móviles  
- **Sistema de administración completo** con login protegido  
- **Dashboard para gestión de códigos de acceso**  
- **Generador de códigos únicos** (formato: aaaa-bbbb)  
- **Nueva tabla en base de datos para códigos de acceso**  
- **Validación de códigos en registro de equipos**  
- **Autenticación en base de datos** con contraseñas encriptadas  
- **Nuevas rutas**: /admin/, /dash/, /offline/, /err/403/, /err/404/, /err/500/  
- **Páginas de error personalizadas** con diseño consistente  
- **Sistema de estadísticas** en panel de administración  
  * Total de equipos y jugadores  
  * Distribución por ciclos  
  * Colores más elegidos  
  * Últimos equipos registrados  
- **Filtros y orden dinámico** en la tabla de códigos  
- **Estilos optimizados** para panel de administración  

## 📊 Versiones

### 🚀 Actual: v0.2.9
- ✅ Nuevo sistema de estadísticas en panel de administración  
- ✅ Visualización de totales, distribución y colores más usados  
- ✅ Filtros y orden dinámico en tabla de códigos  
- ✅ Mejoras visuales en dashboard y estructura de columnas  
- ✅ Actualización de SQL y documentación  

### 📝 Historial completo
Ver [CHANGELOG.md](CHANGELOG.md) para el historial detallado de cambios.

## 📱 Uso

### Registro
1. Accede a la página principal
2. Completa el formulario paso a paso
3. Envía el registro
4. Recibe confirmación de éxito

### Ver equipos registrados
1. Accede a `/teams` para ver todos los equipos registrados
2. Los equipos están organizados por ciclos (Básico/Superior)
3. Visualiza detalles de equipos, integrantes y posiciones
4. Usa la leyenda para entender los indicadores de posición

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -m 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Abre un Pull Request

## 👥 Autor

**Capria Franco** - [GitHub](https://github.com/CapriaFranco)

--- 

⭐ **¡Dale una estrella si te gusta el proyecto!** ⭐
