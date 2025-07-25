# Documento de Requisitos - Gestión de Pagos de Honorarios

## Introducción

El sistema de gestión de pagos de honorarios profesionales permitirá a ALI3000 Consultores optimizar el flujo de trabajo para procesar pagos a consultores, desde la aprobación de horas registradas hasta la generación de órdenes de pago y control de estados. Esta funcionalidad se integrará al dashboard administrativo existente, aprovechando las tablas existentes (usuarios, registros_horas, pagos, tasas_bcv) y agregando el flujo de órdenes de pago.

## Requisitos

### Requisito 1

**Historia de Usuario:** Como administrador de ALI3000, quiero gestionar los datos laborales de consultores y sus tarifas, para poder calcular correctamente sus honorarios.

#### Criterios de Aceptación

1. CUANDO el administrador acceda al módulo de consultores ENTONCES el sistema DEBERÁ mostrar una lista de usuarios tipo "consultor" con sus datos laborales
2. CUANDO el administrador edite un consultor ENTONCES el sistema DEBERÁ permitir actualizar la tarifa_por_hora en la tabla datos_laborales
3. CUANDO el administrador asigne un consultor a una empresa ENTONCES el sistema DEBERÁ crear/actualizar el registro en empresa_consultores
4. CUANDO el sistema calcule honorarios ENTONCES DEBERÁ usar la tarifa_por_hora de la tabla datos_laborales
5. CUANDO el administrador consulte consultores activos ENTONCES el sistema DEBERÁ mostrar solo aquellos con estado "activo" en usuarios y empresa_consultores

### Requisito 2

**Historia de Usuario:** Como administrador, quiero aprobar las horas registradas por consultores y prepararlas para el proceso de pago, para asegurar la validez de los datos antes de generar pagos.

#### Criterios de Aceptación

1. CUANDO el administrador acceda al módulo de aprobación de horas ENTONCES el sistema DEBERÁ mostrar todos los registros_horas con estado "en_espera"
2. CUANDO el administrador revise un registro de horas ENTONCES el sistema DEBERÁ mostrar: consultor, empresa, fecha_trabajo, actividades, horas_normales, horas_extra, quincena_laboral
3. CUANDO el administrador apruebe horas ENTONCES el sistema DEBERÁ cambiar el estado a "aprobada" en registros_horas
4. CUANDO el administrador rechace horas ENTONCES el sistema DEBERÁ cambiar el estado a "rechazada" y permitir agregar observaciones
5. CUANDO se aprueben horas ENTONCES el sistema DEBERÁ calcular el monto usando la tarifa_por_hora del consultor desde datos_laborales

### Requisito 3

**Historia de Usuario:** Como administrador, quiero generar órdenes de pago basadas en horas aprobadas, para crear registros formales en la tabla pagos con todos los cálculos necesarios.

#### Criterios de Aceptación

1. CUANDO el administrador seleccione una quincena ENTONCES el sistema DEBERÁ mostrar todos los registros_horas con estado "aprobada" agrupados por consultor y empresa
2. CUANDO el administrador genere una orden de pago ENTONCES el sistema DEBERÁ crear un registro en la tabla pagos con todos los campos calculados: horas, tarifa_por_hora, iva_porcentaje, islr_porcentaje, montos en USD y bolívares
3. CUANDO se calcule el pago ENTONCES el sistema DEBERÁ usar los porcentajes de IVA e ISLR desde la tabla configuraciones
4. CUANDO se genere el pago ENTONCES el sistema DEBERÁ usar la tasa_cambio más reciente de tasas_bcv y registrar la fecha_tasa_bcv
5. CUANDO se cree el registro de pago ENTONCES el sistema DEBERÁ asignar estado "pendiente" y registrar el procesado_por con el ID del administrador

### Requisito 4

**Historia de Usuario:** Como administrador, quiero controlar los estados de los pagos generados, para hacer seguimiento del proceso desde la generación hasta la confirmación del pago.

#### Criterios de Aceptación

1. CUANDO se cree un registro en pagos ENTONCES el sistema DEBERÁ asignarle el estado inicial "pendiente"
2. CUANDO el administrador marque un pago como procesado ENTONCES el sistema DEBERÁ cambiar el estado a "pagado" en la tabla pagos
3. CUANDO el administrador anule un pago ENTONCES el sistema DEBERÁ cambiar el estado a "anulado" y permitir agregar observaciones
4. CUANDO un pago cambie a "pagado" ENTONCES el sistema DEBERÁ actualizar la fecha_actualizacion automáticamente
5. CUANDO el administrador consulte pagos ENTONCES el sistema DEBERÁ permitir filtrar por estado, usuario_id, empresa_id y rango de fechas

### Requisito 5

**Historia de Usuario:** Como administrador, quiero visualizar reportes y métricas de pagos en el dashboard, para tener control financiero y tomar decisiones informadas.

#### Criterios de Aceptación

1. CUANDO el administrador acceda al dashboard ENTONCES el sistema DEBERÁ mostrar widgets con: total pagado en el mes (suma de total_menos_islr_bs de pagos con estado "pagado"), pagos pendientes (count de pagos con estado "pendiente"), consultores activos (count de usuarios tipo "consultor" activos)
2. CUANDO el administrador genere un reporte mensual ENTONCES el sistema DEBERÁ consultar la tabla pagos agrupando por usuario_id y mostrando totales en USD y bolívares
3. CUANDO se muestren montos actuales ENTONCES el sistema DEBERÁ usar la tasa más reciente de tasas_bcv para conversiones en tiempo real
4. CUANDO el administrador exporte reportes ENTONCES el sistema DEBERÁ generar archivos Excel con datos de pagos, registros_horas relacionados y información de consultores
5. CUANDO se calculen métricas del dashboard ENTONCES el sistema DEBERÁ considerar solo registros de pagos con estado "pagado" para totales definitivos

### Requisito 6

**Historia de Usuario:** Como administrador, quiero que el sistema integre automáticamente la tasa BCV actualizada desde la tabla tasas_bcv, para calcular correctamente los montos en bolívares en todos los pagos.

#### Criterios de Aceptación

1. CUANDO se genere un registro de pago ENTONCES el sistema DEBERÁ consultar la tabla tasas_bcv para obtener la tasa más reciente y almacenarla en tasa_cambio y fecha_tasa_bcv
2. CUANDO no haya tasa BCV del día actual ENTONCES el sistema DEBERÁ usar la tasa más reciente disponible en tasas_bcv y mostrar una advertencia al administrador
3. CUANDO se muestren pagos históricos ENTONCES el sistema DEBERÁ usar la tasa_cambio y fecha_tasa_bcv almacenadas en cada registro de pago
4. CUANDO el administrador consulte un pago ENTONCES el sistema DEBERÁ mostrar tanto los montos originales como una conversión con la tasa actual de tasas_bcv
5. CUANDO se generen reportes ENTONCES el sistema DEBERÁ incluir la fecha_tasa_bcv y tasa_cambio utilizada para cada pago en los detalles
