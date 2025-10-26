# 📝 Changelog

Todas las notables cambios a este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v0.2.4] - 2025-10-26 - 17:44:00

### ✨ Nuevo
- **Validación de teléfono mejorada**
  - Ahora el campo acepta formatos `XX XXXX-XXXX` o `XXX XXXX-XXXX`
  - Validación estricta con regex: solo un espacio y un guion en posiciones fijas  
  - Mensaje de error más claro: `"Formato válido: XX XXXX-XXXX o XXX XXXX-XXXX (solo números, un espacio y un guion)"`  
  - Implementado `minlength="12"` y `maxlength="13"`

### 📱 Interfaz de Registro
- Campo de teléfono del capitán ubicado debajo del nombre del capitán  
- Mantiene validación estricta y formato uniforme  
- Previene ingreso de caracteres no válidos o múltiples guiones

## [v0.2.3] - 2025-10-26 - 16:04:00

### ✨ Nuevo
- **Campo de teléfono**: Agregado campo de teléfono del capitán en formulario de registro
  - Ubicado debajo del nombre del capitán en la tabla de integrantes
  - Placeholder de ejemplo: "11 3126-4254"
  - Validación de formato: solo números, espacios y guiones
  - Campo obligatorio
  - Almacenado en tabla `equipos` con columna `telefono`

### 📊 Base de Datos
- Actualización de `sql/db.sql` con columna `telefono` en tabla `equipos`
- Actualización de `sql/db-infinityfree.sql` con columna `telefono` en tabla `equipos`

## [v0.2.2] - 2025-10-26 - 00:44:00

### 🔐 Sistema de Autenticación Mejorado
- **Base de datos para contraseña**
  - Nueva tabla `admin` para almacenar contraseña encriptada
  - Migración de contraseña hardcodeada a base de datos
  - Uso de `password_hash()` y `password_verify()` para seguridad
  - Archivo temporal para registrar contraseña inicial

### 🚨 Páginas de Error Personalizadas
- **Nuevas páginas de error**
  - Página 403 (Acceso Prohibido)
  - Página 500 (Error Interno del Servidor)
  - Diseño consistente con página 404 existente
  
- **Configuración de ErrorDocument**
  - Agregados ErrorDocument 403, 404, 500 en .htaccess
  - URLs absolutas para manejo correcto de errores
  - Rutas amigables en index.php para páginas de error

### 🔧 Correcciones del Dashboard
- **Redirect corregido**
  - Dashboard ahora permanece en `/dash/` al generar códigos
  - Eliminado redirect incorrecto a index
  - Mejor experiencia de usuario en panel de administración

### 🎨 Optimizaciones de Interfaz
- **Tabla de códigos optimizada**
  - Reducido ancho de columna "Código" para mejor uso del espacio
  - Mejora en la legibilidad de la tabla
  - Diseño más compacto y eficiente

### 📊 Base de Datos
- Actualización de `sql/db.sql` con tabla `admin`
- Actualización de `sql/db-infinityfree.sql` con tabla `admin`
- Estructura optimizada para autenticación segura

## [v0.2.1] - 2025-10-25 - 23:46:00

### 🔧 Correcciones del Panel de Administración
- **Interfaz mejorada**
  - Agregados estilos completos al input de contraseña en login
  - Corregido espaciado en tabla de códigos (columnas separadas correctamente)
  - Reducido ancho de columna "Código" para optimizar espacio
  
- **Funcionalidad corregida**
  - Prevenida regeneración automática de códigos al recargar página
  - Corregido redirect de logout para evitar error PHP
  - Reducido tamaño del texto del código generado
  
- **Tabla de códigos reestructurada**
  - Nueva estructura: ID | [ESTADO] CODIGO | [ID EQUIPO] EQUIPO
  - Indicadores visuales de estado (cuadrados verde/rojo)
  - Verde: código disponible | Rojo: código usado
  - Formato de equipo: "#07 Nombre del equipo"
  
- **Mensajes consolidados**
  - Código y botón copiar movidos a alerta de éxito
  - Botón generar reposicionado arriba
  - Mensaje de éxito mostrado debajo
  
- **Recursos**
  - Agregado ícono copy.svg para botón de copiar
  - Completados estilos CSS del panel de administración

## [v0.2.0] - 2025-10-25 - 22:58:00

### 🔐 Sistema de Administración
- **Panel de administración completo**
  - Login con contraseña protegida
  - Dashboard para gestión de códigos
  - Generador de códigos de acceso únicos
  - Formato de código: `aaaa-bbbb` (8 caracteres a-z 0-9, minúsculas)
  
### 🎫 Sistema de Códigos de Acceso
- **Nueva tabla en base de datos**: `codigos_acceso`
  - Almacena códigos generados
  - Rastrea uso de códigos (usado/disponible)
  - Vincula códigos con equipos registrados
  
- **Validación en registro de equipos**
  - Campo obligatorio de código de acceso
  - Validación silenciosa: si el código no existe o ya fue usado, limpia el formulario
  - Marca código como usado al registrar equipo exitosamente

### 🛣️ Nuevas Rutas
- `/admin/` - Acceso al panel de administración
- `/dash/` - Dashboard de administración
- `/offline/` - Página de error sin conexión

### 🎨 Estilos
- Estilos para panel de administración
- Estilos para generador de códigos
- Estilos para tabla de códigos
- Badges de estado (disponible/usado)
- Mensajes de error y éxito

### 📊 Base de Datos
- Actualización de `sql/db.sql` con tabla `codigos_acceso`
- Actualización de `sql/db-infinityfree.sql` con tabla `codigos_acceso`

## [v0.1.6] - 2025-10-25 - 21:05:00

### ✨ Mejorado
- **Validación de colores**: Agregado mensaje informativo en formulario de registro
  - Nuevo aviso: "No repetir colores de los equipos ya registrados."
  - Ubicado en la sección de color de remera
  - Clase CSS: `.infoRepetirColor` con estilos consistentes
  - Mejora la experiencia de usuario al prevenir errores de duplicación

## [v0.1.5] - 2025-10-25 - 03:50:00

### 🐛 Corregido
- **Compatibilidad con WhatsApp**: Corregidas etiquetas meta para compartir en WhatsApp
  - Agregados metadatos específicos requeridos por WhatsApp:
    * `og:image:width` (1200px)
    * `og:image:height` (628px)
    * `og:image:type` (image/png)
    * `og:image:secure_url` (URL HTTPS)
    * `og:image:alt` (texto alternativo)
  - Cambiadas URLs relativas a URLs absolutas con dominio completo
  - Solucionado problema de caché de WhatsApp con metadatos correctos

### ✨ Mejorado
- **Validación de nombres**: Agregado mensaje aclaratorio en formulario de registro
  - Nuevo aviso: "Ingresar apellido y nombre completos. No usar apodos ni abreviaciones."
  - Ubicado sobre la tabla de integrantes para mejor visibilidad
  - Estilos consistentes con el diseño del formulario

### 🎨 Diseño
- **Mensaje informativo**: Nuevo contenedor `.infoCompletTable` con estilos
  - Fondo semi-transparente con color primario
  - Bordes redondeados y padding adecuado
  - Tipografía clara y legible

### 📊 Redes Sociales
- **Previsualizaciones mejoradas**: Ahora funcionan correctamente en:
  - ✅ WhatsApp (móvil y web)
  - ✅ Facebook
  - ✅ Twitter/X
  - ✅ LinkedIn
  - ✅ Telegram
  - ✅ Otras plataformas que usan Open Graph

## [v0.1.4] - 2025-10-24 - 19:39:30

### ✨ Nuevo
- **Etiquetas meta completas**: Implementadas etiquetas meta para SEO en todas las páginas
- **Open Graph**: Agregadas etiquetas Open Graph para compartir en Facebook y redes sociales
- **Twitter Cards**: Implementadas Twitter Cards para previsualizaciones en Twitter/X
- **Imagen para redes sociales**: Nueva imagen optimizada de 1200x628px para compartir
- **Theme color**: Color de tema del navegador usando el color primario (#94d0af)

### 🎨 SEO y Redes Sociales
- **Meta description**: Descripciones únicas y optimizadas para cada página
- **Meta keywords**: Palabras clave relevantes para búsquedas
- **Meta author**: Información de autoría del sitio
- **Meta robots**: Configuración de indexación para motores de búsqueda
- **OG tags**: og:title, og:description, og:image, og:url, og:type
- **Twitter tags**: twitter:card, twitter:title, twitter:description, twitter:image
- **Favicon**: Icono del sitio en todas las páginas

### 🐛 Corregido
- **Error CSS**: Corregido error de sintaxis en `-ms-grid-columns` (línea 337)
  - Antes: `-ms-grid-columns: (1fr)[2];` ❌
  - Ahora: `-ms-grid-columns: 1fr 1fr;` ✅
- **Validación CSS**: Eliminados errores de sintaxis en media queries

### 📊 Mejoras
- **Imagen OG optimizada**: Imagen de 1200x628px para previsualizaciones perfectas
- **URLs dinámicas**: Open Graph URLs generadas dinámicamente según el entorno
- **Metadatos consistentes**: Todas las páginas con el mismo conjunto de meta tags

## [v0.1.3] - 2025-10-24 - 18:20:00

### ✨ Nuevo
- **Página de equipos registrados**: Nueva página `/teams` para visualizar todos los equipos registrados
- **Organización por ciclos**: Equipos separados en Ciclos Básicos (1ro-3ro) y Ciclo Superior (4to-7mo)
- **Indicadores de suplentes**: Icono visual para jugadores suplentes en rotaciones 6:0, 4:2 y 5:1
- **Leyenda completa**: Agregado indicador de Capitán (C) en la leyenda de iconos
- **Contadores de estadísticas**: Visualización de equipos y personas registradas
- **Navegación mejorada**: Botones para volver y subir al inicio de la página

### 🎨 Diseño
- **Consistencia visual**: Página de equipos con el mismo diseño que el formulario de registro
- **Layout optimizado**: Grid responsive para información de equipos (Curso, División, Sistema, Color)
- **Tablas mejoradas**: Anchos de columna optimizados (5% #, 55% Nombre, 40% Posición)
- **Scroll horizontal**: Implementado min-width en tablas para mejor legibilidad en móviles
- **Espaciado mejorado**: Padding superior e inferior en formularios para mejor scroll en móviles

### 🔧 Técnico
- **Ruta amigable**: Agregada ruta `/teams` en index.php para acceso directo
- **Validación de datos**: Manejo seguro de arrays de integrantes con isset() y is_array()
- **Iconos de posición**: Sistema inteligente para mostrar iconos según tipo de rotación
- **CSS Grid**: Implementado para layout de información de equipos
- **Responsive design**: Adaptación automática de layout en pantallas pequeñas

### 🐛 Corregido
- **Espaciado de iconos**: Corregido gap entre iconos de la leyenda
- **Iconos de suplentes**: Ahora se muestran correctamente para jugadores #7 y #8
- **Scroll de tablas**: Implementado correctamente para pantallas pequeñas
- **Error de array**: Solucionado "Undefined array key 'integrantes'" con validaciones

### 📊 Estadísticas
- Contador de equipos registrados por ciclo
- Contador total de personas registradas
- Visualización clara y accesible de datos



## [v0.1.2] - 2025-10-24 - 15:00:00

### ✨ Mejorado
- **Compatibilidad de navegadores**: Adaptado CSS para todos los navegadores usando autoprefixer
- **Mejor soporte cross-browser**: Garantizada compatibilidad con navegadores modernos y legacy
- **Validaciones de formulario**: Implementadas validaciones de patrones para campos de texto

### 🔧 Técnico
- Implementado autoprefixer para CSS
- Optimización de estilos para mejor rendimiento
- Mejoras en la compatibilidad de prefijos CSS
- Agregadas validaciones de patrones con regex para campos de texto:
  - **Nombre del equipo**: `pattern="[A-Za-záéíóúÁÉÍÓÚñÑ _-]+"` (min: 3, max: 100 caracteres)
  - **Integrantes**: `pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]+"` (min: 4, max: 100 caracteres)
  - **Color**: `pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]+"` (min: 4, max: 100 caracteres)

### 🎨 CSS Cross-browser
- Adaptación completa de estilos para compatibilidad universal
- Prefijos CSS automáticos para navegadores legacy
- Optimización de rendimiento visual

### 📝 Validaciones mejoradas
- Patrones regex para caracteres especiales en español
- Longitud mínima y máxima configurada por campo
- Mejor experiencia de usuario con validaciones más precisas

## [v0.1.1] - 2025-10-24 - 14:14:00

### 🐛 Corregido
- **Enlaces de WhatsApp**: Corregido sistema de detección de dispositivos móviles
- **Navegación móvil**: Mejorada experiencia de usuario en dispositivos móviles

### 🔧 Técnico
- Cambiado `href` estático por `id="whatsappLink"` dinámico
- Implementada detección automática de dispositivos móviles con JavaScript
- Sistema inteligente de enlaces:
  - Móviles: `intent://chat.whatsapp.com/...` (abre app nativa)
  - Desktop: `https://chat.whatsapp.com/...` (abre web)

### 📱 Detección de dispositivos
```javascript
const mobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
const linkMobile = "intent://chat.whatsapp.com/CbpE4tkN2kMAoaTlkQXSLr#Intent;package=com.whatsapp;scheme=https;end";
const linkDesktop = "https://chat.whatsapp.com/CbpE4tkN2kMAoaTlkQXSLr";
document.getElementById("whatsappLink").href = mobile ? linkMobile : linkDesktop;
```

## [v0.1.0] - 2025-10-24 - 04:41:45

### 🎉 Lanzamiento inicial (MVP)
- **Sistema de registro completo** para Torneo de Voley 2025
- **Formulario dinámico** con validaciones en tiempo real
- **Selección de curso y división** (1ro-7mo, A/B/C, 1ra/2da)
- **Sistema de posiciones** según modalidad de juego (6:0, 4:2, 5:1)
- **Validación de colores únicos** por curso
- **Diseño responsive** para móviles y desktop
- **Páginas de éxito y error** personalizadas
- **Ruteo amigable** con .htaccess

### 🛠️ Tecnologías
- **Backend**: PHP 8.2.12
- **Base de datos**: MariaDB 10.4.32
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Servidor**: Apache 2.4.58

### 📋 Características principales
- ✅ Registro de equipos completo
- ✅ Validación de datos en tiempo real
- ✅ Prevención de duplicados de colores
- ✅ Interfaz moderna y responsive
- ✅ Sistema de posiciones dinámico
- ✅ Páginas de confirmación personalizadas
