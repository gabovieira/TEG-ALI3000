# Generador de Registros de Horas

Este script permite generar registros de horas para consultores en un período específico, útil para pruebas y demostraciones.

## Requisitos

-   PHP 8.0 o superior
-   Laravel 9.0 o superior
-   Base de datos configurada con usuarios y empresas

## Instalación

1. Asegúrate de que la migración `create_registro_horas_table` se ha ejecutado:

```bash
php artisan migrate
```

## Uso

### Método 1: Usando el archivo batch (Windows)

Simplemente ejecuta el archivo `generar_horas.bat` haciendo doble clic en él.

### Método 2: Usando Artisan

```bash
php artisan horas:generar
```

### Opciones disponibles

El comando acepta varias opciones para personalizar la generación de registros:

-   `--consultores`: IDs de los consultores específicos (por defecto, todos los consultores activos)

    ```bash
    php artisan horas:generar --consultores=1 --consultores=2
    ```

-   `--empresas`: IDs de las empresas específicas (por defecto, todas las empresas activas)

    ```bash
    php artisan horas:generar --empresas=1 --empresas=2
    ```

-   `--inicio`: Fecha de inicio en formato Y-m-d (por defecto, inicio de la quincena actual)

    ```bash
    php artisan horas:generar --inicio=2025-07-01
    ```

-   `--fin`: Fecha de fin en formato Y-m-d (por defecto, fin de la quincena actual)

    ```bash
    php artisan horas:generar --fin=2025-07-15
    ```

-   `--estado`: Estados a generar (pendiente, aprobado, rechazado)

    ```bash
    php artisan horas:generar --estado=pendiente --estado=aprobado
    ```

-   `--forzar`: Forzar la creación incluso si ya existen registros
    ```bash
    php artisan horas:generar --forzar
    ```

### Ejemplos

1. Generar registros para la quincena actual para todos los consultores:

    ```bash
    php artisan horas:generar
    ```

2. Generar registros solo para el consultor con ID 1 en la primera quincena de julio:

    ```bash
    php artisan horas:generar --consultores=1 --inicio=2025-07-01 --fin=2025-07-15
    ```

3. Generar solo registros pendientes para todos los consultores:

    ```bash
    php artisan horas:generar --estado=pendiente
    ```

4. Sobrescribir registros existentes:
    ```bash
    php artisan horas:generar --forzar
    ```

## Comportamiento

-   El script genera registros solo para días laborables (lunes a viernes)
-   Cada consultor tiene un 80% de probabilidad de tener un registro para cada día laborable
-   Las horas registradas varían aleatoriamente entre 4 y 8 horas
-   Los registros se distribuyen entre las empresas asignadas al consultor
-   Por defecto, se generan registros con estados aleatorios (pendiente, aprobado, rechazado)
-   No se generan registros duplicados para la misma combinación de consultor, empresa y fecha (a menos que se use --forzar)

## Notas

-   Este script es solo para fines de prueba y demostración
-   Los registros generados tienen descripciones de actividades aleatorias
-   Los registros aprobados o rechazados tendrán un administrador asignado como aprobador
