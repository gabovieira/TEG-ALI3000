# Design Document

## Overview

El módulo de Gestión de Empresas es una funcionalidad crítica para el sistema ALI3000 que permitirá a los administradores gestionar eficientemente todas las empresas de la plataforma. Este módulo proporcionará una interfaz intuitiva para ver, filtrar, editar, eliminar empresas y asignar consultores a empresas específicas.

## Architecture

El módulo de Gestión de Empresas seguirá la arquitectura MVC (Modelo-Vista-Controlador) utilizada en el resto del sistema ALI3000, basado en Laravel. Se integrará con los modelos existentes de Empresa y Usuario, y utilizará las relaciones ya establecidas entre ellos.

### Components

1. **Controlador de Empresas (EmpresasController)**: Gestionará todas las operaciones CRUD relacionadas con las empresas.
2. **Vistas**: Conjunto de vistas para listar, mostrar detalles, crear y editar empresas.
3. **Rutas**: Definición de rutas para acceder a las diferentes funcionalidades del módulo.
4. **Middleware**: Verificación de permisos para asegurar que solo los administradores puedan acceder al módulo.

## Components and Interfaces

### Controllers

#### EmpresasController

Este controlador gestionará todas las operaciones relacionadas con las empresas:

-   `index()`: Muestra la lista de empresas con opciones de filtrado y ordenación.
-   `show($id)`: Muestra los detalles de una empresa específica.
-   `create()`: Muestra el formulario para crear una nueva empresa.
-   `store(Request $request)`: Procesa la creación de una nueva empresa.
-   `edit($id)`: Muestra el formulario para editar una empresa existente.
-   `update(Request $request, $id)`: Procesa la actualización de una empresa.
-   `destroy($id)`: Elimina o inactiva una empresa.
-   `asignarConsultores($id)`: Muestra la interfaz para asignar consultores a una empresa.
-   `guardarAsignaciones(Request $request, $id)`: Guarda las asignaciones de consultores a una empresa.
-   `eliminarAsignacion($empresaId, $usuarioId)`: Elimina una asignación específica entre una empresa y un consultor.

### Views

#### Lista de Empresas (index.blade.php)

-   Tabla con la lista de empresas
-   Filtros por tipo de empresa, estado y campo de búsqueda
-   Opciones para ordenar por diferentes columnas
-   Paginación
-   Botones de acción para ver detalles, editar y eliminar
-   Botón para crear nueva empresa

#### Detalles de Empresa (show.blade.php)

-   Información completa de la empresa
-   Lista de consultores asignados
-   Estadísticas relevantes (horas registradas, pagos, etc.)
-   Botones para editar, eliminar o cambiar estado
-   Botón para gestionar asignaciones de consultores

#### Formulario de Empresa (create.blade.php / edit.blade.php)

-   Campos para todos los datos de la empresa
-   Validación en tiempo real
-   Selector de tipo de empresa y estado
-   Para nuevas empresas: opción para asignar consultores inmediatamente

#### Asignación de Consultores (asignar-consultores.blade.php)

-   Lista de consultores disponibles con casillas de verificación
-   Lista de consultores ya asignados con opción de eliminar
-   Búsqueda y filtrado de consultores

### Routes

```php
// Rutas para gestión de empresas
Route::prefix('admin/empresas')->name('admin.empresas.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [EmpresasController::class, 'index'])->name('index');
    Route::get('/crear', [EmpresasController::class, 'create'])->name('create');
    Route::post('/', [EmpresasController::class, 'store'])->name('store');
    Route::get('/{id}', [EmpresasController::class, 'show'])->name('show');
    Route::get('/{id}/editar', [EmpresasController::class, 'edit'])->name('edit');
    Route::put('/{id}', [EmpresasController::class, 'update'])->name('update');
    Route::delete('/{id}', [EmpresasController::class, 'destroy'])->name('destroy');

    // Rutas para asignación de consultores
    Route::get('/{id}/asignar-consultores', [EmpresasController::class, 'asignarConsultores'])->name('asignar-consultores');
    Route::post('/{id}/asignar-consultores', [EmpresasController::class, 'guardarAsignaciones'])->name('guardar-asignaciones');
    Route::delete('/{empresaId}/consultores/{usuarioId}', [EmpresasController::class, 'eliminarAsignacion'])->name('eliminar-asignacion');
});
```

## Data Models

El módulo utilizará los modelos existentes en el sistema:

### Empresa

```php
class Empresa extends Model
{
    // Atributos y relaciones existentes

    // Relación con consultores
    public function consultores()
    {
        return $this->belongsToMany(Usuario::class, 'empresa_consultores', 'empresa_id', 'consultor_id')
                    ->withTimestamps();
    }

    // Scope para filtrar por tipo de empresa
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_empresa', $tipo);
    }

    // Scope para filtrar por estado
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Scope para buscar por nombre o RIF
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                     ->orWhere('rif', 'like', "%{$termino}%");
    }
}
```

### Usuario

```php
class Usuario extends Authenticatable
{
    // Atributos y relaciones existentes

    // Relación con empresas (para consultores)
    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_consultores', 'consultor_id', 'empresa_id')
                    ->withTimestamps();
    }
}
```

## Error Handling

-   Validación de formularios en el lado del cliente y servidor
-   Mensajes de error claros y específicos
-   Manejo de excepciones para operaciones críticas (eliminación, asignación)
-   Confirmación antes de acciones destructivas
-   Advertencias cuando las acciones pueden tener consecuencias en otros módulos

## Testing Strategy

### Unit Tests

-   Pruebas para los métodos del controlador
-   Pruebas para los scopes del modelo Empresa
-   Pruebas para las validaciones de formularios

### Feature Tests

-   Prueba de listado de empresas con diferentes filtros
-   Prueba de creación de empresas
-   Prueba de edición de empresas
-   Prueba de eliminación de empresas
-   Prueba de asignación de consultores a empresas

### Integration Tests

-   Prueba de integración con el módulo de registro de horas
-   Prueba de integración con el módulo de pagos

## Security Considerations

-   Solo los administradores pueden acceder al módulo de gestión de empresas
-   Validación de permisos en cada acción
-   Protección contra CSRF en todos los formularios
-   Validación de datos de entrada
-   Sanitización de datos antes de mostrarlos en las vistas
-   Logs de acciones críticas (creación, edición, eliminación de empresas)

## User Interface Design

El diseño de la interfaz de usuario seguirá los mismos patrones y estilos utilizados en el resto del sistema ALI3000, utilizando Tailwind CSS para mantener la consistencia visual.

### Lista de Empresas

-   Tabla responsive con columnas para nombre, RIF, tipo, estado y acciones
-   Filtros en la parte superior
-   Paginación en la parte inferior
-   Botones de acción con iconos intuitivos

### Formularios

-   Diseño limpio y espaciado
-   Validación en tiempo real con mensajes de error claros
-   Botones de acción prominentes
-   Campos agrupados lógicamente

### Detalles de Empresa

-   Información organizada en secciones
-   Uso de tarjetas para separar diferentes tipos de información
-   Estadísticas visuales cuando sea apropiado
-   Botones de acción claramente visibles
