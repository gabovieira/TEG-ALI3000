# Requirements Document

## Introduction

El módulo de Pagos Individuales permite a los administradores gestionar el proceso completo de pago a consultores de manera individual, incluyendo la selección de períodos, cálculo automático de montos con impuestos, visualización de datos bancarios, generación de comprobantes y confirmación por parte del consultor. Este módulo complementa el sistema existente de gestión de pagos, añadiendo funcionalidades específicas para el flujo de pago individual y la confirmación por parte del consultor.

## Requirements

### Requirement 1

**User Story:** Como administrador, quiero seleccionar un período y un consultor específico para generar un pago individual, de modo que pueda procesar pagos de manera flexible según las necesidades.

#### Acceptance Criteria

1. WHEN el administrador accede a la sección de pagos individuales THEN el sistema SHALL mostrar un formulario con selección de período (quincenal o personalizado)
2. WHEN el administrador selecciona un período THEN el sistema SHALL permitir seleccionar un consultor específico
3. WHEN el administrador selecciona un consultor THEN el sistema SHALL mostrar las horas aprobadas del consultor en ese período
4. WHEN no hay horas aprobadas para el período seleccionado THEN el sistema SHALL mostrar un mensaje indicando que no hay horas disponibles
5. WHEN el administrador confirma la selección THEN el sistema SHALL calcular automáticamente los montos basados en las horas aprobadas

### Requirement 2

**User Story:** Como administrador, quiero visualizar el cálculo detallado del pago con todos los impuestos y conversiones, para verificar que los montos sean correctos antes de procesar el pago.

#### Acceptance Criteria

1. WHEN el sistema calcula un pago individual THEN el sistema SHALL mostrar el desglose completo: total de horas aprobadas, monto base en divisas, IVA, ISLR, total con IVA, total menos ISLR
2. WHEN se muestra el cálculo THEN el sistema SHALL mostrar la conversión a bolívares usando la tasa BCV más reciente
3. WHEN no hay tasa BCV disponible THEN el sistema SHALL mostrar una advertencia y usar la tasa más reciente disponible
4. WHEN se visualiza el cálculo THEN el sistema SHALL permitir al administrador revisar todos los montos antes de confirmar
5. WHEN el administrador necesita ajustar algún valor THEN el sistema SHALL permitir regresar al paso anterior

### Requirement 3

**User Story:** Como administrador, quiero ver los datos bancarios del consultor al generar un pago, para poder realizar la transferencia correctamente.

#### Acceptance Criteria

1. WHEN se muestra la información de pago THEN el sistema SHALL mostrar los datos bancarios del consultor: nombre del banco, tipo de cuenta, número de cuenta, cédula/RIF, nombre del titular
2. WHEN un consultor tiene múltiples cuentas bancarias THEN el sistema SHALL mostrar la cuenta marcada como principal
3. WHEN no hay datos bancarios registrados THEN el sistema SHALL mostrar una advertencia y solicitar al administrador que contacte al consultor
4. WHEN se visualizan los datos bancarios THEN el sistema SHALL mostrar un indicador de que la transferencia debe realizarse manualmente fuera del sistema

### Requirement 4

**User Story:** Como administrador, quiero marcar un pago como "Procesado" después de realizar la transferencia, para generar un comprobante y notificar al consultor.

#### Acceptance Criteria

1. WHEN el administrador realiza la transferencia manualmente THEN el sistema SHALL permitir marcar el pago como "Procesado"
2. WHEN el administrador marca un pago como "Procesado" THEN el sistema SHALL solicitar confirmación y permitir agregar observaciones
3. WHEN se confirma el procesamiento THEN el sistema SHALL generar un comprobante de pago con todos los detalles
4. WHEN se genera el comprobante THEN el sistema SHALL notificar automáticamente al consultor
5. WHEN se procesa el pago THEN el sistema SHALL registrar la fecha y el usuario que procesó el pago

### Requirement 5

**User Story:** Como consultor, quiero recibir notificación de pagos procesados y poder confirmar la recepción del pago, para mantener un registro claro de los pagos recibidos.

#### Acceptance Criteria

1. WHEN un pago es marcado como "Procesado" THEN el sistema SHALL enviar una notificación al consultor
2. WHEN el consultor accede a su panel THEN el sistema SHALL mostrar los pagos pendientes de confirmación
3. WHEN el consultor verifica el pago en su cuenta THEN el sistema SHALL permitir confirmar la recepción del pago
4. WHEN el consultor confirma un pago THEN el sistema SHALL permitir agregar comentarios opcionales
5. WHEN se confirma la recepción THEN el sistema SHALL actualizar el estado del pago y notificar al administrador

### Requirement 6

**User Story:** Como administrador, quiero gestionar los datos bancarios de los consultores, para mantener actualizada la información necesaria para los pagos.

#### Acceptance Criteria

1. WHEN el administrador accede al perfil de un consultor THEN el sistema SHALL mostrar una sección para gestionar datos bancarios
2. WHEN el administrador agrega datos bancarios THEN el sistema SHALL solicitar: banco, tipo de cuenta, número de cuenta, cédula/RIF, titular
3. WHEN se guardan datos bancarios THEN el sistema SHALL validar el formato del número de cuenta según el banco seleccionado
4. WHEN un consultor tiene múltiples cuentas THEN el sistema SHALL permitir marcar una como principal
5. WHEN se eliminan datos bancarios THEN el sistema SHALL solicitar confirmación y verificar que no estén asociados a pagos en proceso

### Requirement 7

**User Story:** Como administrador, quiero generar y descargar comprobantes de pago con todos los detalles, para mantener un registro formal de los pagos realizados.

#### Acceptance Criteria

1. WHEN un pago es procesado THEN el sistema SHALL generar automáticamente un comprobante de pago
2. WHEN se genera un comprobante THEN el sistema SHALL incluir: datos del consultor, empresa, período, desglose de montos, datos bancarios, fecha de procesamiento
3. WHEN el administrador accede al detalle de un pago THEN el sistema SHALL permitir descargar el comprobante en formato PDF
4. WHEN se descarga un comprobante THEN el sistema SHALL mantener el diseño corporativo con logos y colores de la empresa
5. WHEN un pago es confirmado por el consultor THEN el sistema SHALL actualizar el comprobante con la fecha de confirmación

### Requirement 8

**User Story:** Como sistema, quiero mantener un registro completo del ciclo de vida de cada pago, para proporcionar trazabilidad y auditoría de todas las operaciones.

#### Acceptance Criteria

1. WHEN se crea un pago individual THEN el sistema SHALL registrar: usuario que lo creó, fecha de creación, estado inicial
2. WHEN cambia el estado de un pago THEN el sistema SHALL registrar: usuario que realizó el cambio, fecha, estado anterior y nuevo
3. WHEN se procesa un pago THEN el sistema SHALL registrar: administrador que lo procesó, fecha, observaciones
4. WHEN un consultor confirma un pago THEN el sistema SHALL registrar: fecha de confirmación, comentarios
5. WHEN se consulta el historial de un pago THEN el sistema SHALL mostrar la secuencia completa de eventos en orden cronológico
