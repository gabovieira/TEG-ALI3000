# Design Document

## Overview

El módulo de Gestión de Usuarios es una funcionalidad crítica para el sistema ALI3000 que permitirá a los administradores gestionar eficientemente todos los usuarios de la plataforma. Este módulo proporcionará una interfaz intuitiva para ver, filtrar, editar, eliminar usuarios y asignar consultores a empresas específicas.

## Architecture

El módulo de Gestión de Usuarios seguirá la arquitectura MVC (Modelo-Vista-Controlador) utilizada en el resto del sistema ALI3000, basado en Laravel. Se integrará con los modelos existentes de Usuario y Empresa, y utilizará las relaciones ya establecidas entre ellos.

### Components

1. **Controlador de Usuarios (UsuariosController)**: Gestionará todas las operaciones CRUD relacionadas con los usuarios.
2. **Vistas**: Conjunto de vistas para listar, mostrar detalles, crear y editar usuarios.
3. **Rutas**: Definición de rutas para acceder a las diferentes funcionalidades del módulo.
4. **Middleware**: Verificación de permisos para asegurar que solo los administradores puedan acceder al módulo.

## Components and Interfaces

### Controllers

#### UsuariosController

Este controlador gestionará todas las operaciones relacionadas con los usuarios:

-   `index()`: Muestra la lista de usuarios con opciones de filtrado y ordenación.
-   `show($id)`: Muestra los detalles de un usuario específico.
-   `create()`: Muestra el formulario para crear un nuevo usuario.
-   `store(Request $request)`: Procesa la creación de un nuevo usuario.
-   `edit($id)`: Muestra el formulario para editar un usuario existente.
-   `update(Request $request, $id)`: Procesa la actualización de un usuario.
-   `destroy($id)`: Elimina o inactiva un usuario.
-   `asignarEmpresas($id)`: Muestra la interfaz para asignar empresas a un consultor.
-   `guardarAsignaciones(Request $request, $id)`: Guarda las asignaciones de empresas a un consultor.
-   `eliminarAsignacion($usuarioId, $empresaId)`: Elimina una asignación específica entre un consultor y una empresa.

### Views

#### Lista de Usuarios (index.blade.php)

-   Tabla con la lista de usuarios
-   Filtros por tipo de usuario, estado y campo de búsqueda
-   Opciones para ordenar por diferentes columnas
-   Paginación
-   Botones de acción para ver detalles, editar y eliminar
-   Botón para crear nuevo usuario

#### Detalles de Usuario (show.blade.php)

-   Información completa del usuario
-   Para consultores: lista de empresas asignadas
-   Estadísticas relevantes (horas registradas, pagos, etc.)
-   Botones para editar, eliminar o cambiar estado
-   Para consultores: botón para gestionar asignaciones de empresas

#### Formulario de Usuario (create.blade.php / edit.blade.php)

-   Campos para todos los datos del usuario
-   Validación en tiempo real
-   Selector de tipo de usuario y estado
-   Para nuevos consultores: opción para asignar empresas inmediatamente

#### Asignación de Empresas (asignar-empresas.blade.php)

-   Lista de empresas disponibles con casillas de verificación
-   Lista de empresas ya asignadas con opción de eliminar
-   Búsqueda y filtrado de empresas

### Routes

```php
// Rutas para gestión de usuarios
Route::prefix('admin/usuarios')->name('admin.usuarios.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [UsuariosController::class, 'index'])->name('index');
    Route::get('/crear', [UsuariosController::class, 'create'])->name('create');
    Route::post('/', [UsuariosController::class, 'store'])->name('store');
    Route::get('/{id}', [UsuariosController::class, 'show'])->name('show');
    Route::get('/{id}/editar', [UsuariosController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UsuariosController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsuariosController::class, 'destroy'])->name('destroy');

    // Rutas para asignación de empresas
    Route::get('/{id}/asignar-empresas', [UsuariosController::class, 'asignarEmpresas'])->name('asignar-empresas');
    Route::post('/{id}/asignar-empresas', [UsuariosController::class, 'guardarAsignaciones'])->name('guardar-asignaciones');
    Route::delete('/{usuarioId}/empresas/{empresaId}', [UsuariosController::class, 'eliminarAsignacion'])->name('eliminar-asignacion');
});
```

## Data Models

El módulo utilizará los modelos existentes en el sistema:

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

    // Scope para filtrar por tipo de usuario
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_usuario', $tipo);
    }

    // Scope para filtrar por estado
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Scope para buscar por nombre o correo
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                     ->orWhere('apellido', 'like', "%{$termino}%")
                     ->orWhere('email', 'like', "%{$termino}%");
    }
}
```

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
-   Pruebas para los scopes del modelo Usuario
-   Pruebas para las validaciones de formularios

### Feature Tests

-   Prueba de listado de usuarios con diferentes filtros
-   Prueba de creación de usuarios
-   Prueba de edición de usuarios
-   Prueba de eliminación de usuarios
-   Prueba de asignación de empresas a consultores

### Integration Tests

-   Prueba de integración con el módulo de registro de horas
-   Prueba de integración con el módulo de pagos

## Security Considerations

-   Solo los administradores pueden acceder al módulo de gestión de usuarios
-   Validación de permisos en cada acción
-   Protección contra CSRF en todos los formularios
-   Validación de datos de entrada
-   Sanitización de datos antes de mostrarlos en las vistas
-   Logs de acciones críticas (creación, edición, eliminación de usuarios)

## User Interface Design

El diseño de la interfaz de usuario seguirá los mismos patrones y estilos utilizados en el resto del sistema ALI3000, utilizando Tailwind CSS para mantener la consistencia visual.

### Lista de Usuarios

-   Tabla responsive con columnas para nombre, correo, tipo, estado y acciones
-   Filtros en la parte superior
-   Paginación en la parte inferior
-   Botones de acción con iconos intuitivos

### Formularios

-   Diseño limpio y espaciado
-   Validación en tiempo real con mensajes de error claros
-   Botones de acción prominentes
-   Campos agrupados lógicamente

### Detalles de Usuario

-   Información organizada en secciones
-   Uso de tarjetas para separar diferentes tipos de información
-   Estadísticas visuales cuando sea apropiado
-   Botones de acción claramente visibles
