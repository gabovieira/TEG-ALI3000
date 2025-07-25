# Requirements Document - Sistema de Gestión de Horas de Trabajo y Control de Asistencia

## Introduction

El Sistema de Gestión de Horas de Trabajo y Control de Asistencia permite a los consultores registrar sus horas trabajadas por empresa, controlar su asistencia según horarios establecidos, y a los administradores gestionar estos registros, aplicar descuentos por faltas y llegadas tarde. Este sistema es fundamental para el cálculo posterior de pagos, control de productividad y gestión disciplinaria.

## Requirements

### Requirement 1: Registro de Horas por Consultores

**User Story:** Como consultor, quiero registrar mis horas trabajadas por empresa y fecha, para que puedan ser aprobadas y posteriormente pagadas.

#### Acceptance Criteria

1. WHEN un consultor accede al formulario de registro de horas THEN el sistema SHALL mostrar un formulario con campos para empresa, fecha, horas trabajadas y descripción de actividades
2. WHEN un consultor selecciona una empresa THEN el sistema SHALL mostrar solo las empresas a las que está asignado el consultor
3. WHEN un consultor ingresa horas trabajadas THEN el sistema SHALL validar que sean números decimales entre 0.5 y 12 horas por día
4. WHEN un consultor registra horas para una fecha THEN el sistema SHALL verificar que no exceda las 12 horas totales por día considerando otros registros
5. WHEN un consultor guarda un registro de horas THEN el sistema SHALL crear el registro con estado "pendiente" y mostrar mensaje de confirmación

### Requirement 2: Visualización de Horas Registradas

**User Story:** Como consultor, quiero ver mis horas registradas con su estado de aprobación, para hacer seguimiento de mis registros y pagos pendientes.

#### Acceptance Criteria

1. WHEN un consultor accede a la lista de sus horas THEN el sistema SHALL mostrar todos sus registros ordenados por fecha descendente
2. WHEN se muestra la lista de horas THEN el sistema SHALL incluir fecha, empresa, horas, estado (pendiente/aprobado/rechazado) y descripción
3. WHEN un consultor filtra por estado THEN el sistema SHALL mostrar solo los registros que coincidan con el filtro seleccionado
4. WHEN un consultor filtra por rango de fechas THEN el sistema SHALL mostrar solo los registros dentro del período especificado
5. WHEN un registro está en estado "pendiente" THEN el consultor SHALL poder editarlo o eliminarlo

### Requirement 3: Aprobación de Horas por Administradores

**User Story:** Como administrador, quiero revisar y aprobar las horas registradas por los consultores, para controlar la calidad y veracidad de los registros antes del pago.

#### Acceptance Criteria

1. WHEN un administrador accede a la gestión de horas THEN el sistema SHALL mostrar todos los registros pendientes de aprobación
2. WHEN un administrador revisa un registro THEN el sistema SHALL mostrar detalles completos incluyendo consultor, empresa, fecha, horas y descripción
3. WHEN un administrador aprueba un registro THEN el sistema SHALL cambiar el estado a "aprobado" y registrar fecha y usuario que aprobó
4. WHEN un administrador rechaza un registro THEN el sistema SHALL cambiar el estado a "rechazado", requerir motivo del rechazo y notificar al consultor
5. WHEN un administrador aprueba/rechaza múltiples registros THEN el sistema SHALL permitir acciones en lote para eficiencia

### Requirement 4: Reportes y Estadísticas de Horas

**User Story:** Como administrador, quiero generar reportes de horas trabajadas por consultor y empresa, para analizar productividad y planificar pagos.

#### Acceptance Criteria

1. WHEN un administrador solicita reporte por consultor THEN el sistema SHALL generar reporte con total de horas por período, estado y empresa
2. WHEN un administrador solicita reporte por empresa THEN el sistema SHALL mostrar total de horas trabajadas por todos los consultores en esa empresa
3. WHEN se genera un reporte THEN el sistema SHALL incluir filtros por fecha, consultor, empresa y estado
4. WHEN se visualiza un reporte THEN el sistema SHALL permitir exportar a PDF y Excel
5. WHEN se calcula estadísticas THEN el sistema SHALL mostrar promedios, totales y tendencias por período

### Requirement 5: Validaciones y Reglas de Negocio

**User Story:** Como sistema, quiero aplicar reglas de negocio para mantener la integridad de los datos de horas trabajadas.

#### Acceptance Criteria

1. WHEN se registra horas para una fecha futura THEN el sistema SHALL rechazar el registro con mensaje de error apropiado
2. WHEN se registra horas para una fecha con más de 30 días de antigüedad THEN el sistema SHALL requerir aprobación especial del administrador
3. WHEN un consultor no está asignado a una empresa THEN el sistema SHALL impedir el registro de horas para esa empresa
4. WHEN se registra horas en días feriados THEN el sistema SHALL aplicar tarifas especiales si están configuradas
5. WHEN se modifica un registro aprobado THEN el sistema SHALL requerir autorización de administrador y mantener historial de cambios

### Requirement 6: Configuración de Horarios por Empresa

**User Story:** Como administrador, quiero configurar horarios de trabajo específicos para cada empresa, para poder controlar la asistencia y puntualidad de los consultores.

#### Acceptance Criteria

1. WHEN un administrador configura horarios para una empresa THEN el sistema SHALL permitir definir horario de entrada mañana, salida mañana, entrada tarde y salida tarde
2. WHEN se configura un horario THEN el sistema SHALL validar que las horas sean lógicas (entrada antes que salida, formato 24 horas)
3. WHEN una empresa tiene horario configurado THEN el sistema SHALL aplicar control de asistencia a todos los consultores asignados a esa empresa
4. WHEN se modifica un horario existente THEN el sistema SHALL aplicar los cambios solo a registros futuros, manteniendo histórico
5. WHEN una empresa no tiene horario configurado THEN el sistema SHALL funcionar solo como registro libre de horas sin control de asistencia

### Requirement 7: Control de Asistencia y Puntualidad

**User Story:** Como sistema, quiero controlar automáticamente la asistencia y puntualidad de los consultores según los horarios establecidos por empresa.

#### Acceptance Criteria

1. WHEN un consultor registra hora de entrada THEN el sistema SHALL comparar con el horario establecido y marcar llegada tarde si excede la tolerancia
2. WHEN un consultor no registra asistencia en un día laboral THEN el sistema SHALL marcar automáticamente como falta
3. WHEN se detecta llegada tarde THEN el sistema SHALL calcular los minutos de retraso y aplicar descuento según políticas configuradas
4. WHEN se detecta una falta THEN el sistema SHALL registrar la falta y aplicar descuento correspondiente al día completo
5. WHEN un consultor trabaja tiempo parcial THEN el sistema SHALL calcular descuentos proporcionalmente a las horas programadas

### Requirement 8: Registro de Entrada y Salida

**User Story:** Como consultor, quiero registrar mis horas de entrada y salida por empresa, para cumplir con el control de asistencia establecido.

#### Acceptance Criteria

1. WHEN un consultor accede al registro de asistencia THEN el sistema SHALL mostrar las empresas con horarios fijos asignadas al consultor
2. WHEN un consultor registra entrada THEN el sistema SHALL capturar fecha, hora exacta y empresa, y mostrar si hay retraso
3. WHEN un consultor registra salida THEN el sistema SHALL calcular las horas trabajadas y validar contra el horario mínimo requerido
4. WHEN un consultor intenta registrar entrada/salida fuera del rango permitido THEN el sistema SHALL requerir justificación o aprobación especial
5. WHEN se registra asistencia THEN el sistema SHALL mostrar resumen del día: horas trabajadas, retrasos, y descuentos aplicables

### Requirement 9: Gestión de Descuentos por Administradores

**User Story:** Como administrador, quiero gestionar y aplicar descuentos por faltas y llegadas tarde, para mantener disciplina y control de costos.

#### Acceptance Criteria

1. WHEN un administrador accede a gestión de descuentos THEN el sistema SHALL mostrar todos los descuentos pendientes de revisión
2. WHEN se detecta automáticamente una falta o llegada tarde THEN el sistema SHALL crear un descuento pendiente de aprobación
3. WHEN un administrador revisa un descuento THEN el sistema SHALL mostrar detalles: consultor, fecha, tipo (falta/retraso), monto calculado y justificación
4. WHEN un administrador aprueba un descuento THEN el sistema SHALL aplicarlo al cálculo de pago del consultor
5. WHEN un administrador rechaza un descuento THEN el sistema SHALL requerir justificación y notificar al consultor

### Requirement 10: Configuración de Políticas de Descuentos

**User Story:** Como administrador, quiero configurar las políticas de descuentos por faltas y llegadas tarde, para establecer reglas claras y consistentes.

#### Acceptance Criteria

1. WHEN un administrador configura políticas THEN el sistema SHALL permitir definir tolerancia en minutos para llegadas tarde
2. WHEN se configura descuento por retraso THEN el sistema SHALL permitir definir si es por minuto, por bloque de tiempo, o tarifa fija
3. WHEN se configura descuento por falta THEN el sistema SHALL permitir definir si es día completo, proporcional, o tarifa fija
4. WHEN se establecen políticas THEN el sistema SHALL permitir diferentes configuraciones por empresa o tipo de consultor
5. WHEN se modifican políticas THEN el sistema SHALL aplicar cambios solo a eventos futuros, manteniendo histórico de políticas anteriores

### Requirement 11: Reportes de Asistencia y Descuentos

**User Story:** Como administrador, quiero generar reportes de asistencia y descuentos aplicados, para analizar patrones y tomar decisiones gerenciales.

#### Acceptance Criteria

1. WHEN un administrador solicita reporte de asistencia THEN el sistema SHALL mostrar estadísticas de puntualidad, faltas y retrasos por consultor
2. WHEN se genera reporte de descuentos THEN el sistema SHALL incluir montos descontados, motivos y impacto en pagos
3. WHEN se visualiza reporte de tendencias THEN el sistema SHALL mostrar gráficos de evolución de asistencia por período
4. WHEN se filtra por empresa THEN el sistema SHALL mostrar comparativas de asistencia entre diferentes empresas
5. WHEN se exporta reporte THEN el sistema SHALL incluir detalles suficientes para auditoría y justificación de descuentos

### Requirement 12: Notificaciones y Alertas de Asistencia

**User Story:** Como usuario del sistema, quiero recibir notificaciones sobre eventos de asistencia, para mantenerme informado y tomar acciones correctivas.

#### Acceptance Criteria

1. WHEN un consultor llega tarde THEN el sistema SHALL notificar inmediatamente al consultor y a los administradores
2. WHEN se detecta una falta THEN el sistema SHALL notificar al final del día al consultor y administradores
3. WHEN se acumulan múltiples retrasos THEN el sistema SHALL enviar alerta de patrón de impuntualidad
4. WHEN se aplica un descuento THEN el sistema SHALL notificar al consultor con detalles del descuento y cómo evitarlo
5. WHEN se acerca el fin de período de pago THEN el sistema SHALL enviar resumen de descuentos que afectarán el pago
