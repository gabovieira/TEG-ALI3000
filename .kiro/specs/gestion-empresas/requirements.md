# Requirements Document

## Introduction

El módulo de Gestión de Empresas permitirá a los administradores del sistema ALI3000 gestionar de manera eficiente todas las empresas registradas en la plataforma. Los administradores podrán ver una lista completa de empresas, filtrarlas por diferentes criterios, editar sus datos, eliminar empresas y asignar consultores a empresas específicas. Este módulo es esencial para mantener actualizada la información de las empresas y garantizar que estén correctamente asignadas a los consultores que trabajan con ellas.

## Requirements

### Requirement 1

**User Story:** Como administrador, quiero ver una lista de todas las empresas registradas en el sistema, para poder tener una visión general de las empresas y acceder rápidamente a su información.

#### Acceptance Criteria

1. WHEN el administrador accede a la sección de gestión de empresas THEN el sistema SHALL mostrar una tabla con todas las empresas registradas.
2. WHEN se muestra la lista de empresas THEN el sistema SHALL mostrar para cada empresa: nombre, RIF, tipo de empresa, estado y fecha de registro.
3. WHEN el administrador visualiza la lista de empresas THEN el sistema SHALL permitir paginar los resultados si hay más de 10 empresas.
4. WHEN el administrador visualiza la lista de empresas THEN el sistema SHALL permitir ordenar la lista por nombre, RIF, tipo de empresa, estado o fecha de registro.

### Requirement 2

**User Story:** Como administrador, quiero poder filtrar la lista de empresas por diferentes criterios, para encontrar rápidamente empresas específicas.

#### Acceptance Criteria

1. WHEN el administrador accede a la lista de empresas THEN el sistema SHALL proporcionar filtros por tipo de empresa (S.A., C.A., Otro).
2. WHEN el administrador accede a la lista de empresas THEN el sistema SHALL proporcionar filtros por estado (activa, inactiva).
3. WHEN el administrador accede a la lista de empresas THEN el sistema SHALL proporcionar un campo de búsqueda para buscar por nombre o RIF.
4. WHEN el administrador aplica un filtro THEN el sistema SHALL actualizar la lista de empresas mostrando solo las que cumplen con los criterios seleccionados.

### Requirement 3

**User Story:** Como administrador, quiero poder ver los detalles completos de una empresa, para conocer toda su información y los consultores que están asignados a ella.

#### Acceptance Criteria

1. WHEN el administrador selecciona una empresa de la lista THEN el sistema SHALL mostrar una vista detallada con toda la información de la empresa.
2. WHEN se muestra la vista detallada de una empresa THEN el sistema SHALL mostrar los consultores que están asignados actualmente a la empresa.
3. WHEN se muestra la vista detallada de una empresa THEN el sistema SHALL mostrar estadísticas relevantes (horas registradas, pagos generados, etc.).
4. WHEN se muestra la vista detallada de una empresa THEN el sistema SHALL proporcionar opciones para editar, eliminar o cambiar el estado de la empresa.

### Requirement 4

**User Story:** Como administrador, quiero poder editar la información de las empresas, para mantener sus datos actualizados.

#### Acceptance Criteria

1. WHEN el administrador selecciona la opción de editar una empresa THEN el sistema SHALL mostrar un formulario con los datos actuales de la empresa.
2. WHEN el administrador edita los datos de una empresa THEN el sistema SHALL validar que los campos obligatorios estén completos y con el formato correcto.
3. WHEN el administrador guarda los cambios THEN el sistema SHALL actualizar la información de la empresa en la base de datos.
4. WHEN se actualiza la información de una empresa THEN el sistema SHALL mostrar un mensaje de confirmación.
5. WHEN el administrador edita una empresa THEN el sistema SHALL permitir cambiar su estado (activa, inactiva).

### Requirement 5

**User Story:** Como administrador, quiero poder eliminar empresas del sistema, para mantener la base de datos limpia y actualizada.

#### Acceptance Criteria

1. WHEN el administrador selecciona la opción de eliminar una empresa THEN el sistema SHALL solicitar confirmación antes de proceder.
2. WHEN el administrador confirma la eliminación THEN el sistema SHALL eliminar la empresa de la base de datos o marcarla como eliminada.
3. WHEN se elimina una empresa THEN el sistema SHALL mostrar un mensaje de confirmación.
4. WHEN se intenta eliminar una empresa con registros asociados (horas, pagos, consultores) THEN el sistema SHALL advertir al administrador y ofrecer la opción de inactivar la empresa en lugar de eliminarla.

### Requirement 6

**User Story:** Como administrador, quiero poder asignar consultores a empresas específicas, para establecer las relaciones laborales entre consultores y empresas.

#### Acceptance Criteria

1. WHEN el administrador accede a la vista detallada de una empresa THEN el sistema SHALL mostrar una sección para gestionar los consultores asignados.
2. WHEN el administrador selecciona la opción de asignar consultores THEN el sistema SHALL mostrar una lista de todos los consultores disponibles.
3. WHEN el administrador selecciona consultores para asignar THEN el sistema SHALL guardar la relación consultor-empresa en la base de datos.
4. WHEN el administrador elimina una asignación consultor-empresa THEN el sistema SHALL solicitar confirmación antes de proceder.
5. WHEN se modifica una asignación consultor-empresa THEN el sistema SHALL mostrar un mensaje de confirmación.
6. WHEN se intenta eliminar una asignación consultor-empresa que tiene registros de horas asociados THEN el sistema SHALL advertir al administrador sobre las implicaciones.

### Requirement 7

**User Story:** Como administrador, quiero poder crear nuevas empresas directamente desde el panel de administración, para facilitar la incorporación de nuevas empresas al sistema.

#### Acceptance Criteria

1. WHEN el administrador selecciona la opción de crear una nueva empresa THEN el sistema SHALL mostrar un formulario para ingresar los datos de la empresa.
2. WHEN el administrador completa el formulario de nueva empresa THEN el sistema SHALL validar que los campos obligatorios estén completos y con el formato correcto.
3. WHEN el administrador guarda la nueva empresa THEN el sistema SHALL crear la empresa en la base de datos.
4. WHEN se crea una nueva empresa THEN el sistema SHALL mostrar un mensaje de confirmación.
5. WHEN se crea una nueva empresa THEN el sistema SHALL permitir asignarle consultores inmediatamente.
