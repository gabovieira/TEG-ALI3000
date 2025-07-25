# Design Document - Sistema de Gestión de Horas de Trabajo y Control de Asistencia

## Overview

El sistema de gestión de horas y control de asistencia es un módulo integral que permite el registro de horas trabajadas, control de asistencia con horarios fijos, y gestión de descuentos por faltas y llegadas tarde. El sistema está diseñado para ser flexible, escalable y fácil de usar tanto para consultores como administradores.

## Architecture

### High-Level Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Blade Views) │◄──►│   (Laravel)     │◄──►│   (MySQL)       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
                              │
                              ▼
                       ┌─────────────────┐
                       │  Notification   │
                       │    System       │
                       └─────────────────┘
```

### Component Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Presentation Layer                        │
├─────────────────────────────────────────────────────────────┤
│  Admin Views        │  Consultor Views    │  API Endpoints  │
│  - Horarios Config  │  - Registro Horas   │  - REST API     │
│  - Gestión Desc.    │  - Asistencia       │  - JSON Resp.   │
│  - Reportes         │  - Ver Horas        │                 │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│                    Business Logic Layer                      │
├─────────────────────────────────────────────────────────────┤
│  Controllers        │  Services           │  Validators     │
│  - HorasController  │  - AsistenciaServ.  │  - HorasValid.  │
│  - AsistController  │  - DescuentoServ.   │  - TimeValid.   │
│  - DescuentoCtrl    │  - NotificacionServ.│  - PolicyValid. │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│                    Data Access Layer                         │
├─────────────────────────────────────────────────────────────┤
│  Models             │  Repositories       │  Database       │
│  - RegistroHoras    │  - HorasRepository  │  - MySQL        │
│  - Asistencia       │  - AsistRepository  │  - Migrations   │
│  - Descuento        │  - DescRepository   │  - Seeders      │
└─────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### 1. Registro de Horas Component

**Purpose**: Manejo del registro y gestión de horas trabajadas

**Interfaces**:

-   `HorasController`: Controlador principal para CRUD de horas
-   `HorasService`: Lógica de negocio para validaciones y cálculos
-   `HorasRepository`: Acceso a datos de registros de horas

**Key Methods**:

```php
// HorasController
public function store(Request $request)
public function index(Request $request)
public function approve($id)
public function reject($id, Request $request)

// HorasService
public function validateHours($consultor_id, $fecha, $horas)
public function calculateDailyTotal($consultor_id, $fecha)
public function applyBusinessRules($registro)
```

### 2. Control de Asistencia Component

**Purpose**: Gestión de horarios, entrada/salida y control de puntualidad

**Interfaces**:

-   `AsistenciaController`: Controlador para registro de asistencia
-   `HorarioService`: Gestión de horarios por empresa
-   `AsistenciaService`: Lógica de control de asistencia

**Key Methods**:

```php
// AsistenciaController
public function registrarEntrada(Request $request)
public function registrarSalida(Request $request)
public function verAsistencia($fecha)

// HorarioService
public function configurarHorario($empresa_id, $horarios)
public function validarHorario($horarios)
public function obtenerHorario($empresa_id)

// AsistenciaService
public function detectarLlegadaTarde($entrada, $horario)
public function detectarFalta($consultor_id, $fecha)
public function calcularHorasTrabajadas($entrada, $salida)
```

### 3. Sistema de Descuentos Component

**Purpose**: Gestión de descuentos por faltas y llegadas tarde

**Interfaces**:

-   `DescuentoController`: Controlador para gestión de descuentos
-   `DescuentoService`: Lógica de cálculo y aplicación de descuentos
-   `PolicyService`: Gestión de políticas de descuentos

**Key Methods**:

```php
// DescuentoController
public function index()
public function aprobar($id)
public function rechazar($id, Request $request)
public function configurarPoliticas(Request $request)

// DescuentoService
public function calcularDescuentoRetraso($minutos, $tarifa, $politica)
public function calcularDescuentoFalta($tarifa_diaria, $politica)
public function aplicarDescuento($consultor_id, $descuento)

// PolicyService
public function crearPolitica($empresa_id, $politicas)
public function obtenerPolitica($empresa_id, $tipo)
public function validarPolitica($politica)
```

## Data Models

### Core Entities

#### 1. RegistroHoras (Extended)

```php
class RegistroHoras extends Model
{
    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'fecha',
        'horas_trabajadas',
        'descripcion_actividades',
        'estado', // pendiente, aprobado, rechazado
        'tipo_registro', // manual, automatico
        'aprobado_por',
        'fecha_aprobacion',
        'motivo_rechazo',
        'hora_entrada',
        'hora_salida'
    ];
}
```

#### 2. HorarioEmpresa (New)

```php
class HorarioEmpresa extends Model
{
    protected $fillable = [
        'empresa_id',
        'entrada_manana',    // 08:00:00
        'salida_manana',     // 12:00:00
        'entrada_tarde',     // 13:30:00
        'salida_tarde',      // 17:00:00
        'dias_laborales',    // JSON: [1,2,3,4,5] (Lun-Vie)
        'tolerancia_minutos', // 15
        'activo',
        'fecha_inicio',
        'fecha_fin'
    ];
}
```

#### 3. RegistroAsistencia (New)

```php
class RegistroAsistencia extends Model
{
    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'fecha',
        'hora_entrada_manana',
        'hora_salida_manana',
        'hora_entrada_tarde',
        'hora_salida_tarde',
        'minutos_retraso',
        'es_falta',
        'justificacion',
        'estado', // presente, tarde, falta, justificado
        'aprobado_por'
    ];
}
```

#### 4. Descuento (New)

```php
class Descuento extends Model
{
    protected $fillable = [
        'usuario_id',
        'registro_asistencia_id',
        'tipo', // retraso, falta
        'fecha',
        'monto_descuento',
        'motivo',
        'estado', // pendiente, aprobado, rechazado
        'aprobado_por',
        'fecha_aprobacion',
        'observaciones'
    ];
}
```

#### 5. PoliticaDescuento (New)

```php
class PoliticaDescuento extends Model
{
    protected $fillable = [
        'empresa_id',
        'tipo', // retraso, falta
        'metodo_calculo', // por_minuto, por_bloque, tarifa_fija
        'valor_descuento',
        'tolerancia_minutos',
        'maximo_descuento_dia',
        'activo',
        'fecha_inicio',
        'fecha_fin'
    ];
}
```

### Relationships

```php
// Usuario
public function registrosHoras() { return $this->hasMany(RegistroHoras::class); }
public function registrosAsistencia() { return $this->hasMany(RegistroAsistencia::class); }
public function descuentos() { return $this->hasMany(Descuento::class); }

// Empresa
public function horarios() { return $this->hasMany(HorarioEmpresa::class); }
public function politicasDescuento() { return $this->hasMany(PoliticaDescuento::class); }
public function registrosHoras() { return $this->hasMany(RegistroHoras::class); }

// RegistroAsistencia
public function usuario() { return $this->belongsTo(Usuario::class); }
public function empresa() { return $this->belongsTo(Empresa::class); }
public function descuentos() { return $this->hasMany(Descuento::class); }
```

## Error Handling

### Validation Rules

#### Registro de Horas

```php
$rules = [
    'empresa_id' => 'required|exists:empresas,id',
    'fecha' => 'required|date|before_or_equal:today',
    'horas_trabajadas' => 'required|numeric|between:0.5,12',
    'descripcion_actividades' => 'required|string|max:500'
];
```

#### Configuración de Horarios

```php
$rules = [
    'entrada_manana' => 'required|date_format:H:i',
    'salida_manana' => 'required|date_format:H:i|after:entrada_manana',
    'entrada_tarde' => 'required|date_format:H:i|after:salida_manana',
    'salida_tarde' => 'required|date_format:H:i|after:entrada_tarde',
    'tolerancia_minutos' => 'required|integer|between:0,60'
];
```

### Exception Handling

```php
// Custom Exceptions
class HorarioConflictException extends Exception {}
class DescuentoCalculationException extends Exception {}
class AsistenciaValidationException extends Exception {}

// Error Response Format
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["validation error"]
    },
    "code": "ERROR_CODE"
}
```

## Testing Strategy

### Unit Tests

#### 1. Service Layer Tests

```php
// HorasServiceTest
public function test_validate_hours_within_limit()
public function test_calculate_daily_total_correctly()
public function test_detect_overtime_hours()

// AsistenciaServiceTest
public function test_detect_late_arrival()
public function test_calculate_worked_hours()
public function test_detect_absence()

// DescuentoServiceTest
public function test_calculate_delay_discount()
public function test_calculate_absence_discount()
public function test_apply_discount_policies()
```

#### 2. Model Tests

```php
// RegistroHorasTest
public function test_registro_creation()
public function test_estado_transitions()
public function test_relationships()

// HorarioEmpresaTest
public function test_horario_validation()
public function test_overlap_detection()
```

### Integration Tests

#### 1. Controller Tests

```php
// HorasControllerTest
public function test_store_registro_horas()
public function test_approve_registro()
public function test_reject_registro()

// AsistenciaControllerTest
public function test_registrar_entrada()
public function test_registrar_salida()
public function test_detect_late_arrival()
```

#### 2. Feature Tests

```php
// Complete Workflow Tests
public function test_complete_attendance_workflow()
public function test_discount_application_workflow()
public function test_approval_workflow()
```

### Performance Tests

```php
// Load Testing Scenarios
- 100 concurrent users registering hours
- Bulk approval of 1000+ records
- Report generation with large datasets
- Real-time attendance tracking
```

## Security Considerations

### Authentication & Authorization

-   Role-based access control (Admin vs Consultor)
-   Route protection with middleware
-   CSRF protection on all forms
-   Session management

### Data Protection

-   Input validation and sanitization
-   SQL injection prevention (Eloquent ORM)
-   XSS protection (Blade templating)
-   Sensitive data encryption

### Audit Trail

```php
// Audit logging for critical actions
- Hora registration/modification
- Attendance records
- Discount approvals/rejections
- Policy changes
```

## Performance Optimization

### Database Optimization

```sql
-- Indexes for performance
CREATE INDEX idx_registro_horas_usuario_fecha ON registro_horas(usuario_id, fecha);
CREATE INDEX idx_asistencia_usuario_fecha ON registro_asistencia(usuario_id, fecha);
CREATE INDEX idx_descuentos_estado ON descuentos(estado);
```

### Caching Strategy

```php
// Cache frequently accessed data
- Horarios por empresa
- Políticas de descuento activas
- Configuraciones del sistema
- Reportes generados
```

### Query Optimization

```php
// Eager loading relationships
RegistroHoras::with(['usuario', 'empresa'])->get();

// Pagination for large datasets
$registros = RegistroHoras::paginate(50);

// Database transactions for consistency
DB::transaction(function() {
    // Multiple related operations
});
```

## Deployment Considerations

### Environment Configuration

```env
# Attendance specific settings
ATTENDANCE_TOLERANCE_MINUTES=15
DISCOUNT_CALCULATION_METHOD=automatic
NOTIFICATION_ENABLED=true
REPORT_CACHE_TTL=3600
```

### Migration Strategy

```php
// Database migrations in order
1. create_horario_empresa_table
2. create_registro_asistencia_table
3. create_descuentos_table
4. create_politica_descuento_table
5. modify_registro_horas_table
```

### Monitoring & Logging

-   Application performance monitoring
-   Error tracking and alerting
-   User activity logging
-   System health checks
