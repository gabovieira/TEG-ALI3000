# Requirements Document - Gestión de Pagos

## Introduction

El módulo de Gestión de Pagos permite a los administradores generar, calcular y gestionar los pagos a consultores basándose en las horas aprobadas. El sistema debe manejar cálculos complejos incluyendo IVA, ISLR, conversión de divisas usando tasas del BCV, y generar reportes detallados de pagos por quincena.

## Requirements

### Requirement 1

**User Story:** Como administrador, quiero generar pagos automáticamente para consultores basándome en sus horas aprobadas, para agilizar el proceso de nómina quincenal.

#### Acceptance Criteria

1. WHEN el administrador selecciona un período quincenal THEN el sistema SHALL mostrar todos los consultores con horas aprobadas en ese período
2. WHEN el administrador confirma la generación de pagos THEN el sistema SHALL calcular automáticamente un pago por consultor sumando todas sus horas aprobadas de todas las empresas
3. WHEN se genera un pago THEN el sistema SHALL aplicar los cálculos de IVA e ISLR según los porcentajes configurados al total del consultor
4. WHEN se calcula un pago THEN el sistema SHALL usar la tasa de cambio BCV más reciente disponible
5. IF no hay tasa BCV disponible para la fecha THEN el sistema SHALL mostrar una advertencia y permitir ingreso manual
6. WHEN un consultor trabajó para múltiples empresas THEN el sistema SHALL crear un solo pago que incluya el desglose por empresa

### Requirement 2

**User Story:** Como administrador, quiero ver un listado completo de todos los pagos generados con filtros y búsqueda, para tener control total sobre los pagos realizados.

#### Acceptance Criteria

1. WHEN el administrador accede al módulo de pagos THEN el sistema SHALL mostrar una tabla con todos los pagos ordenados por fecha más reciente
2. WHEN el administrador aplica filtros THEN el sistema SHALL permitir filtrar por consultor, empresa, estado, período quincenal y rango de fechas
3. WHEN se muestra un pago en la lista THEN el sistema SHALL mostrar consultor, empresa, período, monto total, estado y fecha de pago
4. WHEN el administrador hace clic en un pago THEN el sistema SHALL mostrar el detalle completo con todos los cálculos

### Requirement 3

**User Story:** Como administrador, quiero ver el detalle completo de un pago incluyendo todos los cálculos y desglose, para verificar la exactitud de los montos.

#### Acceptance Criteria

1. WHEN el administrador ve el detalle de un pago THEN el sistema SHALL mostrar el desglose completo de horas, tarifa, monto base, IVA, ISLR
2. WHEN se muestra el detalle THEN el sistema SHALL mostrar tanto los montos en divisas como en bolívares
3. WHEN se visualiza el cálculo THEN el sistema SHALL mostrar la tasa de cambio utilizada y su fecha
4. WHEN el pago tiene observaciones THEN el sistema SHALL mostrar las observaciones registradas
5. IF el pago está pendiente THEN el sistema SHALL permitir marcarlo como pagado o anulado

### Requirement 4

**User Story:** Como administrador, quiero configurar tarifas por hora para cada consultor y empresa, para automatizar los cálculos de pagos.

#### Acceptance Criteria

1. WHEN el administrador configura tarifas THEN el sistema SHALL permitir establecer tarifa por hora específica para cada combinación consultor-empresa
2. WHEN se establece una tarifa THEN el sistema SHALL validar que sea un monto positivo mayor a cero
3. WHEN se modifica una tarifa THEN el sistema SHALL aplicar la nueva tarifa solo a pagos futuros
4. WHEN no existe tarifa configurada THEN el sistema SHALL mostrar advertencia y solicitar configuración antes de generar pagos

### Requirement 5

**User Story:** Como administrador, quiero configurar los porcentajes de IVA e ISLR del sistema, para mantener actualizados los cálculos fiscales.

#### Acceptance Criteria

1. WHEN el administrador accede a configuración fiscal THEN el sistema SHALL mostrar los porcentajes actuales de IVA e ISLR
2. WHEN se modifica un porcentaje THEN el sistema SHALL validar que esté entre 0 y 100
3. WHEN se actualiza la configuración THEN el sistema SHALL aplicar los nuevos porcentajes solo a pagos futuros
4. WHEN se cambia la configuración THEN el sistema SHALL registrar el cambio con fecha y usuario que lo realizó

### Requirement 6

**User Story:** Como administrador, quiero generar reportes de pagos por período, consultor o empresa, para análisis financiero y contable.

#### Acceptance Criteria

1. WHEN el administrador solicita un reporte THEN el sistema SHALL permitir seleccionar criterios como período, consultor, empresa y estado
2. WHEN se genera el reporte THEN el sistema SHALL mostrar totales por consultor, empresa y gran total
3. WHEN se visualiza el reporte THEN el sistema SHALL incluir tanto montos en divisas como en bolívares
4. WHEN el reporte está listo THEN el sistema SHALL permitir exportar a PDF y Excel
5. IF el reporte incluye múltiples períodos THEN el sistema SHALL mostrar subtotales por período

### Requirement 7

**User Story:** Como administrador, quiero marcar pagos como pagados o anulados, para mantener el estado actualizado de todos los pagos.

#### Acceptance Criteria

1. WHEN el administrador marca un pago como pagado THEN el sistema SHALL solicitar confirmación y fecha de pago
2. WHEN se confirma el pago THEN el sistema SHALL actualizar el estado y registrar la fecha de pago
3. WHEN el administrador anula un pago THEN el sistema SHALL solicitar motivo de anulación
4. WHEN se anula un pago THEN el sistema SHALL mantener el registro pero marcarlo como anulado
5. IF un pago ya está pagado o anulado THEN el sistema SHALL no permitir cambios de estado

### Requirement 8

**User Story:** Como administrador, quiero que el sistema valide que no se generen pagos duplicados para el mismo consultor y período, para evitar errores de procesamiento.

#### Acceptance Criteria

1. WHEN se intenta generar un pago THEN el sistema SHALL verificar que no exista otro pago para la misma combinación consultor-período
2. IF ya existe un pago para el período THEN el sistema SHALL mostrar advertencia y no permitir la generación
3. WHEN existe un pago anulado THEN el sistema SHALL permitir generar un nuevo pago para reemplazarlo
4. WHEN se detecta duplicación THEN el sistema SHALL mostrar los detalles del pago existente

### Requirement 9

**User Story:** Como sistema, quiero integrarme con el servicio de tasas BCV para obtener automáticamente las tasas de cambio actualizadas, para garantizar cálculos precisos.

#### Acceptance Criteria

1. WHEN se genera un pago THEN el sistema SHALL intentar obtener la tasa BCV más reciente
2. WHEN la tasa se obtiene exitosamente THEN el sistema SHALL usar esa tasa para los cálculos
3. IF el servicio BCV no está disponible THEN el sistema SHALL usar la última tasa almacenada y mostrar advertencia
4. WHEN se usa una tasa manual THEN el sistema SHALL registrar que fue ingresada manualmente
5. WHEN se actualiza la tasa THEN el sistema SHALL registrar la fecha y hora de actualización
