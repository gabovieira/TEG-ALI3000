# Requirements Document

## Introduction

El módulo de Gestión de Usuarios permitirá a los administradores del sistema ALI3000 gestionar de manera eficiente todos los usuarios registrados en la plataforma. Los administradores podrán ver una lista completa de usuarios, filtrarlos por diferentes criterios, editar sus datos, eliminar usuarios y asignar consultores a empresas específicas. Este módulo es esencial para mantener actualizada la información de los usuarios y garantizar que los consultores estén correctamente asignados a las empresas con las que trabajan.

## Requirements

### Requirement 1

**User Story:** Como administrador, quiero ver una lista de todos los usuarios registrados en el sistema, para poder tener una visión general de los usuarios y acceder rápidamente a su información.

#### Acceptance Criteria

1. WHEN el administrador accede a la sección de gestión de usuarios THEN el sistema SHALL mostrar una tabla con todos los usuarios registrados.
2. WHEN se muestra la lista de usuarios THEN el sistema SHALL mostrar para cada usuario: nombre completo, correo electrónico, tipo de usuario (administrador, consultor), estado (activo, inactivo, pendiente_registro) y fecha de registro.
3. WHEN el administrador visualiza la lista de usuarios THEN el sistema SHALL permitir paginar los resultados si hay más de 10 usuarios.
4. WHEN el administrador visualiza la lista de usuarios THEN el sistema SHALL permitir ordenar la lista por nombre, correo, tipo de usuario, estado o fecha de registro.

### Requirement 2

**User Story:** Como administrador, quiero poder filtrar la lista de usuarios por diferentes criterios, para encontrar rápidamente usuarios específicos.

#### Acceptance Criteria

1. WHEN el administrador accede a la lista de usuarios THEN el sistema SHALL proporcionar filtros por tipo de usuario (administrador, consultor).
2. WHEN el administrador accede a la lista de usuarios THEN el sistema SHALL proporcionar filtros por estado (activo, inactivo, pendiente_registro).
3. WHEN el administrador accede a la lista de usuarios THEN el sistema SHALL proporcionar un campo de búsqueda para buscar por nombre o correo electrónico.
4. WHEN el administrador aplica un filtro THEN el sistema SHALL actualizar la lista de usuarios mostrando solo los que cumplen con los criterios seleccionados.

### Requirement 3

**User Story:** Como administrador, quiero poder ver los detalles completos de un usuario, para conocer toda su información y las empresas a las que está asignado (en caso de consultores).

#### Acceptance Criteria

1. WHEN el administrador selecciona un usuario de la lista THEN el sistema SHALL mostrar una vista detallada con toda la información del usuario.
2. WHEN se muestra la vista detallada de un consultor THEN el sistema SHALL mostrar las empresas a las que está asignado actualmente.
3. WHEN se muestra la vista detallada de un usuario THEN el sistema SHALL mostrar estadísticas relevantes (para consultores: horas registradas, pagos recibidos, etc.).
4. WHEN se muestra la vista detallada de un usuario THEN el sistema SHALL proporcionar opciones para editar, eliminar o cambiar el estado del usuario.

### Requirement 4

**User Story:** Como administrador, quiero poder editar la información de los usuarios, para mantener sus datos actualizados.

#### Acceptance Criteria

1. WHEN el administrador selecciona la opción de editar un usuario THEN el sistema SHALL mostrar un formulario con los datos actuales del usuario.
2. WHEN el administrador edita los datos de un usuario THEN el sistema SHALL validar que los campos obligatorios estén completos y con el formato correcto.
3. WHEN el administrador guarda los cambios THEN el sistema SHALL actualizar la información del usuario en la base de datos.
4. WHEN se actualiza la información de un usuario THEN el sistema SHALL mostrar un mensaje de confirmación.
5. WHEN el administrador edita un usuario THEN el sistema SHALL permitir cambiar su estado (activo, inactivo, pendiente_registro).

### Requirement 5

**User Story:** Como administrador, quiero poder eliminar usuarios del sistema, para mantener la base de datos limpia y actualizada.

#### Acceptance Criteria

1. WHEN el administrador selecciona la opción de eliminar un usuario THEN el sistema SHALL solicitar confirmación antes de proceder.
2. WHEN el administrador confirma la eliminación THEN el sistema SHALL eliminar al usuario de la base de datos o marcarlo como eliminado.
3. WHEN se elimina un usuario THEN el sistema SHALL mostrar un mensaje de confirmación.
4. WHEN se intenta eliminar un usuario con registros asociados (horas, pagos) THEN el sistema SHALL advertir al administrador y ofrecer la opción de inactivar al usuario en lugar de eliminarlo.

### Requirement 6

**User Story:** Como administrador, quiero poder asignar consultores a empresas específicas, para establecer las relaciones laborales entre consultores y empresas.

#### Acceptance Criteria

1. WHEN el administrador accede a la vista detallada de un consultor THEN el sistema SHALL mostrar una sección para gestionar las empresas asignadas.
2. WHEN el administrador selecciona la opción de asignar empresas THEN el sistema SHALL mostrar una lista de todas las empresas disponibles.
3. WHEN el administrador selecciona empresas para asignar THEN el sistema SHALL guardar la relación consultor-empresa en la base de datos.
4. WHEN el administrador elimina una asignación consultor-empresa THEN el sistema SHALL solicitar confirmación antes de proceder.
5. WHEN se modifica una asignación consultor-empresa THEN el sistema SHALL mostrar un mensaje de confirmación.
6. WHEN se intenta eliminar una asignación consultor-empresa que tiene registros de horas asociados THEN el sistema SHALL advertir al administrador sobre las implicaciones.

### Requirement 7

**User Story:** Como administrador, quiero poder crear nuevos usuarios directamente desde el panel de administración, para facilitar la incorporación de nuevos usuarios al sistema.

#### Acceptance Criteria

1. WHEN el administrador selecciona la opción de crear un nuevo usuario THEN el sistema SHALL mostrar un formulario para ingresar los datos del usuario.
2. WHEN el administrador completa el formulario de nuevo usuario THEN el sistema SHALL validar que los campos obligatorios estén completos y con el formato correcto.
3. WHEN el administrador guarda el nuevo usuario THEN el sistema SHALL crear el usuario en la base de datos.
4. WHEN se crea un nuevo usuario THEN el sistema SHALL mostrar un mensaje de confirmación.
5. WHEN se crea un nuevo usuario de tipo consultor THEN el sistema SHALL permitir asignarle empresas inmediatamente.
6. WHEN se crea un nuevo usuario THEN el sistema SHALL generar una contraseña temporal y/o enviar un correo de bienvenida con instrucciones para acceder al sistema.
