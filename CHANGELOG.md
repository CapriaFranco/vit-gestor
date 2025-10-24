# 📝 Changelog

Todas las notables cambios a este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

# 📝 Changelog

Todas las notables cambios a este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
\`\`\`javascript
const mobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
const linkMobile = "intent://chat.whatsapp.com/CbpE4tkN2kMAoaTlkQXSLr#Intent;package=com.whatsapp;scheme=https;end";
const linkDesktop = "https://chat.whatsapp.com/CbpE4tkN2kMAoaTlkQXSLr";
document.getElementById("whatsappLink").href = mobile ? linkMobile : linkDesktop;
\`\`\`

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
