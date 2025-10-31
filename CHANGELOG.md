# 📝 Changelog

Todas las notables cambios a este proyecto serán documentadas en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.0.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v0.2.10] - 2025-10-31 - 10:59:00

### 🔧 Correcciones Críticas en Editor de Equipos

#### Lógica de Posiciones Corregida
- **Sistema 6:0**: Funciona correctamente mostrando iconos de "sin posición"
- **Sistema 5:1**: 
  - Corregido: 7 jugadores obligatorios (antes mostraba 8)
  - Jugador #8 ahora muestra icono de suplente correctamente
  - Posiciones dinámicas con cantidades específicas por rol
- **Sistema 4:2**:
  - **Con Centrales**: 7 jugadores obligatorios (2 Punta, 2 Central, 2 Armador, 1 Libero)
  - **Con Opuestos**: 6 jugadores obligatorios (2 Punta, 2 Opuesto, 2 Armador)
  - Corregido cálculo de jugadores requeridos según tipo

#### Selectores de Posición Mejorados
- **Opción "Seleccionar" disponible**: Permite intercambiar posiciones fácilmente
  - Seleccionar una posición → cambiarla a "Seleccionar"
  - Asignar la posición liberada a otro jugador
  - Facilita reorganización de posiciones sin conflictos
- **Lógica igual al registro**: Implementada función `obtenerPosicionesDisponibles()`
  - Respeta cantidades máximas por posición
  - Actualiza opciones dinámicamente según selecciones
  - Previene duplicación de posiciones únicas (Libero, Armador en 5:1)

#### Jugadores Suplentes
- **Identificación correcta**: Jugadores más allá del mínimo requerido
  - 6:0: Jugadores 7-8 son suplentes
  - 5:1: Jugador 8 es suplente
  - 4:2 con Centrales: Jugador 8 es suplente
  - 4:2 con Opuestos: Jugadores 7-8 son suplentes
- **Icono de suplente**: Muestra icono refresh-ccw en lugar de select
- **Sin posición asignada**: Suplentes no requieren posición específica

#### Nuevo Archivo JavaScript
- **scripts/admin-edit.js**: Lógica dedicada para edición de equipos
  - Función `obtenerPosicionesDisponibles()`: Calcula posiciones disponibles
  - Función `regenerarPosiciones()`: Actualiza tabla según sistema seleccionado
  - Función `actualizarPosicionSeleccionada()`: Maneja cambios de posición
  - Función `actualizarSistema()`: Muestra/oculta tipo de 4:2
  - Función `actualizarDivision()`: Actualiza divisiones según curso
  - Array `posicionesSeleccionadas`: Rastrea posiciones asignadas

#### Funcionalidad de Eliminación
- **Botón "Eliminar Equipo"**: Nuevo botón con estilo danger (rojo)
- **Confirmación de eliminación**: Diálogo de confirmación antes de eliminar
- **php/delete_team.php**: Nuevo archivo para procesar eliminación
  - Elimina integrantes del equipo (foreign key)
  - Elimina el equipo de la base de datos
  - Marca código de acceso como no usado (disponible nuevamente)
  - Redirige a lista de equipos con mensaje de éxito

#### Estilos CSS Agregados
- **`.btn-danger`**: Botón rojo para acciones destructivas
  - Background: var(--color-error)
  - Hover: #d32f2f con elevación
  - Color de texto: blanco para contraste
- **`.team-meta-edit`**: Grid de metadata en página de edición
  - 2 columnas en desktop, 1 en móvil
  - Muestra ID, código, fecha de registro
  - Background destacado con borde

#### Correcciones de Bugs
- **Cálculo de jugadores obligatorios**: Ahora considera tipo de 4:2
  - 4:2 con Opuestos: 6 jugadores (antes incorrectamente 7)
  - 4:2 con Centrales: 7 jugadores (correcto)
- **Regeneración de posiciones**: Al cambiar sistema se actualizan correctamente
  - Mantiene posiciones seleccionadas cuando es posible
  - Limpia posiciones incompatibles con nuevo sistema
  - Actualiza iconos de suplentes dinámicamente
- **Inicialización en carga**: DOMContentLoaded recolecta posiciones iniciales
  - Lee valores de selects generados por PHP
  - Aplica lógica de posiciones desde el inicio

### 📝 Archivos Modificados
- `pages/admins/teams/edit/index.php`: Integración de nuevo script JS
- `styles/main.css`: Estilos para botón danger y metadata
- `scripts/admin-edit.js`: Nueva lógica de edición (creado)
- `php/delete_team.php`: Endpoint de eliminación (creado)

### 🎯 Mejoras de UX
- **Intercambio de posiciones más fácil**: Opción "Seleccionar" permite reorganizar
- **Feedback visual claro**: Iconos distintos para sin posición vs suplente
- **Validación automática**: Solo muestra posiciones disponibles según sistema
- **Eliminación segura**: Confirmación antes de borrar equipo permanentemente

## [v0.2.9] - 2025-10-29 - 20:26:00

### 🔄 Rediseño Completo del Sistema de Edición de Equipos

#### Arquitectura Tradicional PHP
- **Eliminado enfoque JSON/AJAX problemático**: Reemplazado por formularios PHP tradicionales
  - Mayor confiabilidad y estabilidad
  - Sin errores de parseo JSON
  - Procesamiento del lado del servidor más robusto
- **Página de edición dedicada**: Nueva ruta `/a/teams/edit?id=X`
  - Formulario completo similar al de registro
  - Carga de datos del equipo desde la base de datos
  - Procesamiento POST tradicional con recarga de página

#### Flujo de Edición Simplificado
1. **Lista de equipos** (`/a/teams`): Vista de todos los equipos con botón "Editar"
2. **Página de edición** (`/a/teams/edit?id=X`): Formulario completo con datos precargados
3. **Guardado**: Submit del formulario → Procesamiento PHP → Recarga con mensaje de éxito
4. **Vuelta a lista**: Botón "Volver a Equipos" para regresar

#### Funcionalidades de Edición
- **Información del equipo**:
  - Nombre del equipo
  - Curso y división (con actualización dinámica)
  - Sistema de juego (6:0, 4:2, 5:1)
  - Tipo de 4:2 (aparece/desaparece dinámicamente)
  - Color de remera
  - Teléfono del capitán

- **Gestión de integrantes**:
  - Editar nombres de integrantes existentes
  - Cambiar posiciones según sistema de juego
  - Agregar nuevos integrantes (hasta 8 total)
  - Campos vacíos para integrantes adicionales
  - Posiciones dinámicas según sistema seleccionado

#### Metadata Visible
- **ID del registro**: Identificador único en la base de datos
- **Código usado**: Código de acceso utilizado para registrar el equipo
- **Fecha de registro**: Fecha original de registro (formato dd/mm/yyyy)

#### Procesamiento del Lado del Servidor
- **Validación robusta**: Verificación de campos requeridos
- **Actualización atómica**: 
  1. Actualiza datos del equipo en tabla `equipos`
  2. Elimina integrantes antiguos
  3. Inserta integrantes nuevos/actualizados
- **Mensajes de feedback**: Success/error messages después de guardar
- **Seguridad**: Prepared statements para prevenir SQL injection

#### JavaScript Dinámico
- **actualizarDivision()**: Cambia opciones de división según curso seleccionado
  - Ciclo Básico (1ro-3ro): A, B, C
  - Ciclo Superior (4to-7mo): 1ra, 2da
- **actualizarSistema()**: Muestra/oculta campo de tipo 4:2
  - Aparece solo cuando sistema es 4:2
  - Se elimina para otros sistemas
- **actualizarPosiciones()**: Regenera selectores de posición
  - 6:0: Sin posiciones (icono de slash)
  - 4:2/5:1: Selectores con posiciones disponibles
  - Mantiene valores seleccionados al cambiar

#### Archivos Eliminados
- `scripts/admin-teams.js`: Lógica AJAX eliminada
- `php/update_team.php`: Endpoint JSON eliminado

#### Archivos Nuevos
- `pages/admins/teams/edit/index.php`: Página de edición completa con procesamiento PHP

#### Archivos Modificados
- `pages/admins/teams/index.php`: Simplificado a solo vista de equipos con botón "Editar"
- `index.php`: Agregada ruta para `/a/teams/edit`

### 🎯 Ventajas del Nuevo Enfoque
- **Sin errores de JSON**: Eliminados todos los problemas de parseo
- **Más simple**: Menos código JavaScript, más PHP tradicional
- **Más robusto**: Procesamiento del lado del servidor más confiable
- **Mejor UX**: Mensajes claros de éxito/error
- **Fácil debugging**: Errores PHP más fáciles de rastrear que errores AJAX
- **Compatible**: Funciona en todos los navegadores sin problemas

### 🔧 Mejoras Técnicas
- **Prepared statements**: Todas las consultas SQL usan prepared statements
- **Validación de sesión**: Verificación de admin en ambas páginas
- **Redirecciones seguras**: Validación de ID de equipo antes de mostrar formulario
- **Transacciones implícitas**: Delete + Insert de integrantes en secuencia

## [v0.2.8] - 2025-10-29 - 17:38:00

### ✏️ Editor de Equipos Mejorado

#### Interfaz de Usuario
- **Botones de ancho completo**: Todos los botones ahora ocupan el 100% del ancho disponible
  - Mejor usabilidad en dispositivos móviles
  - Diseño más consistente y profesional
  - Clase `.btn-full-width` aplicada a botones de navegación y acciones

#### Diseño de Campos
- **Grid layout para campos**: Todos los campos de edición organizados en grid `repeat(2, 1fr)`
  - Mejor aprovechamiento del espacio
  - Diseño responsive que se adapta a móviles (1 columna en pantallas pequeñas)
  - Campos alineados y balanceados visualmente

#### Información General Ampliada
- **Nuevos campos en vista de equipo**:
  - **ID del Registro**: Identificador único del equipo en la base de datos
  - **Teléfono del Capitán**: Número de contacto del capitán del equipo
  - **Código Usado**: Código de acceso utilizado para el registro
  - **Fecha de Registro**: Fecha en que se registró el equipo (formato dd/mm/yyyy)
- **Grid layout mejorado**: Metadata organizada en grid de 2 columnas
  - Responsive: cambia a 1 columna en móviles

#### Modo de Edición
- **Toggle entre vista y edición**: Botón "Editar" cambia el div a modo edición
  - Interfaz similar al formulario de registro
  - Todos los campos editables con sus respectivos controles
  - Botón "Cancelar" para volver a la vista sin guardar

#### Campos Editables
- **Información básica**:
  - Nombre del equipo (input text)
  - Color de remera (input text)
  - Curso (select con todas las opciones)
  - División (select dinámico según curso)
  - Sistema de juego (select: 6:0, 4:2, 5:1)
  - Tipo de 4:2 (select: Con Centrales / Con Opuestos) - aparece solo si sistema es 4:2

#### Gestión de Integrantes
- **Tabla editable de miembros**:
  - Editar nombre de cada integrante (input text inline)
  - Cambiar posición (select inline con opciones según sistema)
  - Eliminar integrantes existentes (botón 🗑️)
  - Agregar nuevos integrantes (botón "+ Agregar Integrante")
- **Validaciones**:
  - Máximo 8 integrantes por equipo
  - Mínimo según sistema de juego (6 para 6:0 y 4:2 con opuestos, 7 para 4:2 con centrales y 5:1)
  - Renumeración automática al eliminar integrantes
- **Posiciones dinámicas**: Select de posiciones se adapta al sistema de juego
  - 6:0: Sin posiciones (N/A)
  - 4:2 con Centrales: Punta, Central, Armador, Libero
  - 4:2 con Opuestos: Punta, Opuesto, Armador
  - 5:1: Punta, Opuesto, Central, Armador, Libero

#### Funcionalidad JavaScript
- **toggleEditMode()**: Cambia entre vista y modo edición
- **cancelEdit()**: Cancela edición y vuelve a vista sin guardar
- **handleSistemaChange()**: Muestra/oculta campo de tipo 4:2 según sistema seleccionado
- **addIntegranteRow()**: Agrega nueva fila para integrante (máx 8)
- **removeIntegrante()**: Elimina integrante con confirmación
- **saveTeamChanges()**: Guarda cambios del equipo (preparado para implementación AJAX)
  - Recolecta datos del formulario
  - Serializa información de integrantes
  - Console logs para debugging
  - Placeholder para llamada AJAX

### 🎨 Estilos CSS Nuevos

#### Clases Agregadas
- `.btn-full-width`: Botones de ancho completo (100%)
- `.edit-fields-grid`: Grid de 2 columnas para campos de edición
- `.members-edit-section`: Sección de edición de miembros
- `.edit-members-table`: Tabla editable con inputs y selects inline
- `.btn-icon`: Botones de iconos para acciones (editar, eliminar)
- `.btn-primary`: Botón primario con color principal
- `.team-edit-mode`: Contenedor del modo de edición
- `.edit-team-form`: Formulario de edición con flex layout

#### Responsive Design
- Grid de campos: 2 columnas → 1 columna en móviles (<768px)
- Botones adaptables a diferentes tamaños de pantalla
- Tablas con scroll horizontal en pantallas pequeñas

### 🔧 Mejoras Técnicas
- **Consulta SQL mejorada**: Incluye JOIN con tabla `codigos_acceso` para obtener código usado
- **Estructura de datos completa**: Toda la información necesaria para edición disponible en el frontend
- **Preparado para AJAX**: Funciones JavaScript listas para implementar llamadas al backend
- **Debugging facilitado**: Console logs con prefijo `[v0]` para seguimiento de operaciones

### 📝 Archivos Modificados
- `pages/admins/teams/index.php`: Interfaz completa de edición con vista y modo edición
- `styles/main.css`: Nuevos estilos para editor de equipos y componentes relacionados

### 🚀 Próximos Pasos
- Implementar endpoints PHP para guardar cambios (AJAX)
- Validaciones del lado del servidor
- Mensajes de éxito/error después de guardar
- Actualización en tiempo real sin recargar página

## [v0.2.7] - 2025-10-29 - 15:20:00

### 🎨 Mejoras de Diseño y UX

#### Navegación de Administración
- **Grid layout mejorado**: Navegación de admin reorganizada con `grid-template-columns: repeat(2, 1fr)`
  - Mejor adaptabilidad en diferentes tamaños de pantalla
  - Diseño más limpio y organizado
  - Responsive: cambia a una columna en móviles (<480px)

#### Dashboard de Administración
- **Simplificación de contenido**: Eliminadas las secciones de "Ciclos Básicos" y "Ciclo Superior"
  - Dashboard más enfocado en estadísticas y navegación
  - Reducción de scroll innecesario
  - Mejor rendimiento al cargar menos datos

#### Colores Utilizados
- **Estilos consistentes**: Aplicados los mismos estilos de la página de registro
  - Diseño de tarjetas con `.color-card`
  - Lista de colores con `.color-list` usando flexbox
  - Formato de chips/badges para cada color
  - Bordes y sombras consistentes con el resto del sitio

### 📱 Integración de WhatsApp

#### Acceso Directo en Registro
- **Botón de WhatsApp**: Agregado en la página de registro
  - Acceso directo al grupo del torneo
  - Mismo diseño que en la página de éxito
  - Ubicado después del enlace a equipos registrados

#### Corrección para iPhone
- **Detección mejorada de dispositivos**:
  - iOS: Usa `https://chat.whatsapp.com/` directamente
  - Android: Usa `intent://` para abrir la app nativa
  - Desktop: Usa WhatsApp Web
- **Fix específico para iPhone**: Corregido el problema donde el enlace no funcionaba
  - Anteriormente usaba `intent://` que no es compatible con iOS
  - Ahora detecta iOS y usa el esquema correcto `https://`
  - Funciona correctamente en iPhone, iPad y iPod

### 🎨 Estilos CSS

#### Nuevas Clases
- `.admin-nav-buttons`: Grid de 2 columnas para navegación
- `.colors-section`: Sección de colores utilizados
- `.colors-grid`: Grid de 2 columnas para tarjetas de colores
- `.color-card`: Tarjeta individual de colores por ciclo
- `.color-list`: Lista flex de colores con chips
- `.whatsapp-container`: Contenedor para botón de WhatsApp en registro

#### Responsive Design
- Grid de navegación: 2 columnas → 1 columna en móviles
- Grid de colores: 2 columnas → 1 columna en tablets (<768px)

### 📝 Archivos Modificados
- `styles/main.css`: Nuevos estilos para navegación, colores y WhatsApp
- `pages/admins/dashboard/index.php`: Simplificación y nuevos estilos de colores
- `pages/register/index.php`: Agregado botón de WhatsApp con detección de dispositivo

## [v0.2.6] - 2025-10-28 - 11:50:00

### 🏗️ Reestructuración del Sistema de Administración
- **Nueva estructura de carpetas**:
  - `/pages/admins/login/` - Página de inicio de sesión de administrador
  - `/pages/admins/dashboard/` - Dashboard principal con vista general
  - `/pages/admins/codes/` - Gestión de códigos de acceso
  - `/pages/admins/teams/` - Editor de equipos con funcionalidad completa

### 🛣️ URLs Amigables para Administración
- **Nuevas rutas cortas**:
  - `/a/login` - Acceso al panel de administración
  - `/a/dash` - Dashboard principal
  - `/a/codes` - Generador y listado de códigos
  - `/a/teams` - Editor de equipos
- **Compatibilidad hacia atrás**: Las rutas antiguas (`/admin`, `/dash`) redirigen automáticamente a las nuevas

### 📊 Dashboard Mejorado
- **Navegación centralizada**: Botones para acceder a todas las secciones del panel
  - Inicio (página pública)
  - Ver Equipos (página pública)
  - Códigos (administración)
  - Editar Equipos (administración)
- **Tarjetas de equipos**: Visualización completa de todos los equipos registrados
  - Separados por Ciclos Básicos y Ciclo Superior
  - Información detallada de cada equipo
  - Lista de integrantes con posiciones
- **Tarjetas de colores**: Dos nuevas tarjetas que muestran:
  - Colores utilizados en Ciclos Básicos
  - Colores utilizados en Ciclo Superior
- **Contadores de estadísticas**: Equipos y personas registradas

### ✏️ Editor de Equipos
- **Funcionalidad de edición completa** (interfaz lista, lógica por implementar):
  - Editar nombre del equipo
  - Cambiar color de remera
  - Modificar sistema de juego
  - Cambiar tipo de 4:2 (Con Centrales / Con Opuestos)
  - Agregar nuevos integrantes
  - Eliminar integrantes existentes
  - Editar nombre y posición de integrantes
- **Tablas con acciones**: Botones de editar y eliminar en cada integrante
- **Botón de agregar**: Permite añadir nuevos integrantes al equipo
- **Visualización mejorada**: Muestra tipo de 4:2 cuando corresponde

### 🎨 Interfaz de Administración
- **Navegación consistente**: Botones de navegación en todas las páginas de admin
- **Diseño unificado**: Todas las páginas siguen el mismo patrón visual
- **Breadcrumbs implícitos**: Botones de "Volver" y navegación clara
- **Acciones contextuales**: Botones relevantes según la página actual

### 🔐 Seguridad
- **Validación de sesión**: Todas las páginas de admin verifican autenticación
- **Redirecciones automáticas**: Usuarios no autenticados son redirigidos al login
- **URLs protegidas**: Acceso restringido a todas las rutas de administración

### 🔄 Migraciones
- **Compatibilidad total**: Las rutas antiguas siguen funcionando
- **Redirecciones automáticas**: `/admin` → `/a/login`, `/dash` → `/a/dash`
- **Sin pérdida de funcionalidad**: Todas las características existentes preservadas

### 📝 Notas Técnicas
- **Funciones JavaScript placeholder**: Las funciones de edición están preparadas para implementación AJAX
- **Estructura modular**: Cada sección de admin en su propia carpeta
- **Código reutilizable**: Componentes compartidos entre páginas de admin
- **Preparado para expansión**: Estructura lista para agregar más funcionalidades

## [v0.2.5] - 2025-10-27 - 23:50:00

### ✨ Nuevo
- **Selección de tipo de 4:2**: Implementado selector de tipo de sistema 4:2
  - Nuevo campo "Tipo de 4:2" que aparece al seleccionar sistema 4:2
  - Opciones disponibles:
    * 4:2 con Centrales (2 Punta, 2 Centrales, 2 Armadores, 1 Libero)
    * 4:2 con Opuestos (2 Punta, 2 Opuestos, 2 Armadores)
  - Campo obligatorio para sistema 4:2

### 🎨 Interfaz Mejorada
- **Revelación progresiva de formulario**: Implementado sistema de campos progresivos
  - Sistema de juego → Tipo de 4:2 (solo si es 4:2) → Tabla de integrantes
  - Tabla completa y válida → Campo de teléfono
  - Teléfono válido → Campo de color
  - Color válido → Campo de código
  - Código válido → Botón de envío
  - Mejor experiencia de usuario con validación en tiempo real

### 🔧 Validaciones
- **Validación de tabla completa**: Sistema inteligente que verifica:
  - Todos los nombres obligatorios ingresados (mínimo 4 caracteres)
  - Todas las posiciones seleccionadas (excepto en 6:0)
  - Validación de formato de teléfono antes de mostrar siguiente campo
  - Validación de color antes de mostrar código
  - Validación de código antes de mostrar botón de envío

### 📊 Base de Datos
- **Nuevo campo**: `tipo_cuatro_dos` en tabla `equipos`
  - Tipo: ENUM('c', 'o')
  - 'c' = 4:2 con Centrales
  - 'o' = 4:2 con Opuestos
  - Campo opcional (solo requerido para sistema 4:2)
  - Actualización de `sql/db.sql` y `sql/db-infinityfree.sql`

### 🔄 Sistema de Posiciones
- **Posiciones dinámicas según tipo de 4:2**:
  - 4:2 con Centrales: Armador, Central, Punta, Libero
  - 4:2 con Opuestos: Armador, Opuesto, Punta (sin Libero)
  - Validación automática de posiciones disponibles
  - Actualización dinámica de opciones según selección

### 🐛 Correcciones
- **Campo de teléfono**: Movido fuera de la tabla de integrantes
  - Ahora aparece como campo independiente después de completar la tabla
  - Mejor organización visual del formulario
  - Validación mejorada con patrón específico

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
