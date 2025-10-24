# 📝 Changelog

Todas las notables cambios a este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
