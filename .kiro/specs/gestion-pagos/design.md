# Design Document - Gestión de Pagos

## Overview

El módulo de Gestión de Pagos es un sistema complejo que automatiza el cálculo y gestión de pagos a consultores basándose en horas aprobadas. Integra cálculos fiscales (IVA, ISLR), conversión de divisas con tasas del BCV, y proporciona una interfaz completa para administrar todo el ciclo de vida de los pagos.

## Architecture

### Componentes Principales

1. **PagosController**: Controlador principal para gestión de pagos
2. **PagoService**: Servicio para lógica de negocio y cálculos
3. **TarifaService**: Servicio para gestión de tarifas por consultor/empresa
4. **ConfiguracionFiscalService**: Servicio para gestión de IVA/ISLR
5. **ReportePagosService**: Servicio para generación de reportes
6. **TasaBcvService**: Servicio existente para integración con BCV

### Modelos de Datos

#### Modelo Pago (actualizado)

```php
- id: bigint (PK)
- usuario_id: bigint (FK) // Consultor que recibe el pago
- quincena: string // Período del pago (ej: "2025-01-Q1")
- total_horas: decimal // Suma de todas las horas del consultor
- iva_porcentaje: decimal
- islr_porcentaje: decimal
- ingreso_divisas: decimal // Total antes de impuestos
- monto_base_divisas: decimal
- iva_divisas: decimal
- total_con_iva_divisas: decimal
- tasa_cambio: decimal
- fecha_tasa_bcv: date
- monto_base_bs: decimal
- iva_bs: decimal
- total_con_iva_bs: decimal
- islr_divisas: decimal
- total_menos_islr_divisas: decimal
- islr_bs: decimal
- total_menos_islr_bs: decimal
- fecha_pago: date
- estado: enum (pendiente, pagado, anulado)
- observaciones: text
- procesado_por: bigint (FK)
- created_at: timestamp
- updated_at: timestamp
```

#### Modelo PagoDetalle (nuevo - para desglose por empresa)

```php
- id: bigint (PK)
- pago_id: bigint (FK)
- empresa_id: bigint (FK)
- horas: decimal
- tarifa_por_hora: decimal
- monto_empresa_divisas: decimal
- created_at: timestamp
- updated_at: timestamp
```

#### Modelo TarifaConsultor (nuevo)

```php
- id: bigint (PK)
- usuario_id: bigint (FK)
- empresa_id: bigint (FK)
- tarifa_por_hora: decimal
- moneda: enum (USD, EUR)
- fecha_inicio: date
- fecha_fin: date (nullable)
- activa: boolean
- creado_por: bigint (FK)
- created_at: timestamp
- updated_at: timestamp
```

#### Modelo ConfiguracionFiscal (nuevo)

```php
- id: bigint (PK)
- iva_porcentaje: decimal
- islr_porcentaje: decimal
- fecha_vigencia: date
- activa: boolean
- creado_por: bigint (FK)
- created_at: timestamp
- updated_at: timestamp
```

## Components and Interfaces

### PagosController

```php
public function index(Request $request)
public function show($id)
public function generarPagos(Request $request)
public function marcarPagado($id, Request $request)
public function anular($id, Request $request)
public function reportes(Request $request)
public function exportarReporte(Request $request)
```

### PagoService

```php
public function generarPagosQuincena($quincena)
public function calcularPagoConsultor($usuarioId, $quincena)
public function obtenerHorasAprobadasConsultor($usuarioId, $fechaInicio, $fechaFin)
public function validarPagoDuplicado($usuarioId, $quincena)
public function marcarComoPagado($pagoId, $fechaPago, $observaciones = null)
public function anularPago($pagoId, $motivo)
```

### TarifaService

```php
public function obtenerTarifa($usuarioId, $empresaId, $fecha = null)
public function configurarTarifa($usuarioId, $empresaId, $tarifa, $moneda, $fechaInicio)
public function listarTarifas($filtros = [])
public function desactivarTarifa($tarifaId)
```

### ConfiguracionFiscalService

```php
public function obtenerConfiguracionActual()
public function actualizarConfiguracion($iva, $islr, $fechaVigencia)
public function obtenerHistorialConfiguraciones()
```

### ReportePagosService

```php
public function generarReportePeriodo($fechaInicio, $fechaFin, $filtros = [])
public function generarReporteConsultor($usuarioId, $fechaInicio, $fechaFin)
public function generarReporteEmpresa($empresaId, $fechaInicio, $fechaFin)
public function exportarPDF($reporte)
public function exportarExcel($reporte)
```

## Data Models

### Relaciones

-   Pago belongsTo Usuario (consultor)
-   Pago belongsTo Usuario (procesado_por)
-   Pago hasMany PagoDetalle
-   PagoDetalle belongsTo Pago
-   PagoDetalle belongsTo Empresa
-   TarifaConsultor belongsTo Usuario
-   TarifaConsultor belongsTo Empresa
-   ConfiguracionFiscal belongsTo Usuario (creado_por)

### Índices de Base de Datos

-   pagos: (usuario_id, empresa_id), estado, fecha_pago, quincena
-   tarifa_consultores: (usuario_id, empresa_id), activa, fecha_inicio
-   configuracion_fiscal: activa, fecha_vigencia

## Error Handling

### Validaciones de Negocio

1. **Pago Duplicado**: Verificar que no exista pago para mismo consultor/empresa/quincena
2. **Tarifa Faltante**: Validar que exista tarifa configurada antes de generar pago
3. **Horas Insuficientes**: Verificar que existan horas aprobadas para el período
4. **Tasa BCV**: Manejar casos donde no hay tasa disponible
5. **Estados de Pago**: Validar transiciones de estado válidas

### Manejo de Errores

```php
- PagoYaExisteException
- TarifaNoConfiguradaException
- HorasInsuficientesException
- TasaBcvNoDisponibleException
- EstadoPagoInvalidoException
```

## Testing Strategy

### Unit Tests

1. **PagoService**: Cálculos de pagos, validaciones de duplicados
2. **TarifaService**: Obtención de tarifas, configuración
3. **ConfiguracionFiscalService**: Gestión de configuraciones fiscales
4. **ReportePagosService**: Generación de reportes

### Integration Tests

1. **Generación de Pagos**: Flujo completo desde horas aprobadas hasta pago generado
2. **Integración BCV**: Obtención y uso de tasas de cambio
3. **Reportes**: Generación y exportación de reportes

### Feature Tests

1. **Interfaz de Pagos**: Navegación, filtros, acciones
2. **Configuración de Tarifas**: CRUD de tarifas
3. **Reportes**: Generación y descarga de reportes

## User Interface Design

### Páginas Principales y Componentes Visuales

#### 1. Lista de Pagos (`/admin/pagos`)

-   Tabla con filtros por consultor, empresa, estado, período (usa `FiltrosPago`)
-   Acciones: Ver detalle (`PagoCard`), marcar pagado/anular (`EstadoPago`)
-   Paginación y búsqueda

#### 2. Generar Pagos (`/admin/pagos/generar`)

-   Selector de período quincenal y empresa (inputs tipo select, igual a otros módulos)
-   Tabs para tipo de pago: Por Quincena / Pago Individual (Flowbite Tabs)
-   Card informativa con tasa BCV actual (`MontoDisplay`)
-   Vista previa de pagos a generar: lista de consultores, desglose por empresa (`PagoCard` + `CalculoDetalle`)
-   Validaciones visuales: advertencias de duplicidad, tarifas faltantes, horas insuficientes (alertas tipo dashboard)
-   Botón de confirmación destacado (color y estilo igual a otros módulos)
-   Información importante en card secundaria (iconos y colores consistentes)

#### 3. Detalle de Pago (`/admin/pagos/{id}`)

-   Card principal con datos del consultor y empresa
-   Desglose fiscal y por empresa (`CalculoDetalle`)
-   Historial de cambios de estado (timeline visual)
-   Acciones según estado actual (`EstadoPago`)

#### 4. Configuración de Tarifas (`/admin/pagos/tarifas`)

-   Tabla de tarifas por consultor/empresa
-   Formulario para nueva tarifa (inputs y validaciones como en usuarios/empresas)
-   Historial de cambios

#### 5. Configuración Fiscal (`/admin/pagos/configuracion`)

-   Card con configuración actual de IVA/ISLR
-   Formulario para nueva configuración (inputs con validación visual)
-   Historial de cambios (timeline)

#### 6. Reportes (`/admin/pagos/reportes`)

-   Filtros para generar reportes (`FiltrosPago`)
-   Vista previa de reportes (tabla y cards)
-   Opciones de exportación (botones con iconos PDF/Excel)

### Componentes UI Reutilizables

-   `PagoCard`: Tarjeta resumen de pago, con avatar, nombre, empresa, estado y monto.
-   `CalculoDetalle`: Componente para mostrar desglose de cálculos fiscales y por empresa.
-   `FiltrosPago`: Filtros reutilizables para tablas y reportes.
-   `EstadoPago`: Badge de estado con colores y tooltip.
-   `MontoDisplay`: Componente para mostrar montos en divisas y bolívares, con iconos.

Todos los componentes usan la misma tipografía, colores, bordes y espaciado que el dashboard principal. Los iconos y badges mantienen el branding y la jerarquía visual.

### Componentes UI Reutilizables

-   `PagoCard`: Tarjeta resumen de pago
-   `CalculoDetalle`: Componente para mostrar desglose de cálculos
-   `FiltrosPago`: Componente de filtros reutilizable
-   `EstadoPago`: Badge de estado con colores
-   `MontoDisplay`: Componente para mostrar montos en divisas y bolívares

## Security Considerations

### Autorización

-   Solo administradores pueden acceder al módulo de pagos
-   Validar permisos en cada acción sensible
-   Registrar auditoría de todas las acciones

### Validación de Datos

-   Validar todos los inputs de formularios
-   Sanitizar datos antes de cálculos
-   Validar rangos de fechas y montos

### Integridad de Datos

-   Transacciones para operaciones críticas
-   Validaciones de negocio antes de persistir
-   Logs detallados de cambios importantes

## Performance Considerations

### Optimizaciones de Base de Datos

-   Índices en campos de filtro frecuente
-   Eager loading para relaciones
-   Paginación en listados grandes

### Caching

-   Cache de configuraciones fiscales
-   Cache de tarifas activas
-   Cache de tasas BCV recientes

### Procesamiento Asíncrono

-   Generación de pagos masivos en background
-   Exportación de reportes grandes en cola
-   Notificaciones por email de procesos completados
