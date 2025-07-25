# Implementation Plan - Gestión de Pagos

-   [ ] 1. Crear modelos y migraciones adicionales

    -   Crear migración para actualizar tabla pagos (remover empresa_id, agregar total_horas)
    -   Crear migración para tabla pago_detalles (desglose por empresa)
    -   Crear migración para tabla tarifa_consultores
    -   Crear migración para tabla configuracion_fiscal
    -   Crear modelo PagoDetalle con relaciones
    -   Crear modelo TarifaConsultor con relaciones
    -   Crear modelo ConfiguracionFiscal con relaciones
    -   Actualizar modelo Pago existente con nuevas relaciones
    -   _Requirements: 1.6, 4.1, 4.2, 5.1, 5.2_

-   [ ] 2. Implementar servicios de negocio principales
-   [ ] 2.1 Crear PagoService con lógica de cálculos

    -   Implementar método calcularPagoConsultor que suma horas de todas las empresas
    -   Crear método generarPagosQuincena que procesa un pago por consultor
    -   Implementar lógica para crear PagoDetalle por cada empresa del consultor
    -   Implementar validaciones de negocio (duplicados por consultor, tarifas)
    -   Crear método obtenerHorasAprobadasConsultor agrupando por empresa
    -   _Requirements: 1.2, 1.3, 1.4, 1.6, 8.1, 8.2_

-   [ ] 2.2 Crear TarifaService para gestión de tarifas

    -   Implementar CRUD de tarifas por consultor/empresa
    -   Crear método obtenerTarifa con lógica de vigencia
    -   Implementar validaciones de tarifas
    -   _Requirements: 4.1, 4.2, 4.3, 4.4_

-   [ ] 2.3 Crear ConfiguracionFiscalService

    -   Implementar gestión de configuraciones IVA/ISLR
    -   Crear método obtenerConfiguracionActual
    -   Implementar historial de configuraciones
    -   _Requirements: 5.1, 5.2, 5.3, 5.4_

-   [ ] 3. Implementar controlador principal de pagos
-   [ ] 3.1 Crear PagosController con métodos básicos

    -   Implementar método index con filtros y paginación
    -   Crear método show para detalle de pago
    -   Implementar método generarPagos con validaciones
    -   _Requirements: 2.1, 2.2, 3.1, 3.2_

-   [ ] 3.2 Implementar gestión de estados de pagos

    -   Crear método marcarPagado con validaciones
    -   Implementar método anular con motivos
    -   Validar transiciones de estado
    -   _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

-   [ ] 4. Crear interfaces de usuario principales
-   [ ] 4.1 Implementar vista de lista de pagos

    -   Crear vista admin/pagos/index.blade.php con tabla responsive
    -   Implementar filtros por consultor, empresa, estado, período
    -   Agregar paginación y búsqueda
    -   Crear componentes de estado y acciones
    -   _Requirements: 2.1, 2.2, 2.3_

-   [ ] 4.2 Crear vista de generación de pagos

    -   Implementar admin/pagos/generar.blade.php
    -   Crear selector de período quincenal
    -   Implementar vista previa de pagos a generar
    -   Agregar confirmación de generación masiva
    -   _Requirements: 1.1, 1.2_

-   [ ] 4.3 Implementar vista de detalle de pago

    -   Crear admin/pagos/show.blade.php con desglose completo por consultor
    -   Mostrar tabla de desglose por empresa (horas, tarifa, monto)
    -   Mostrar cálculos totales detallados en divisas y bolívares
    -   Implementar acciones según estado del pago
    -   Agregar historial de cambios
    -   _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

-   [ ] 5. Implementar gestión de tarifas
-   [ ] 5.1 Crear controlador de tarifas

    -   Implementar TarifasController con CRUD completo
    -   Crear validaciones de formularios
    -   Implementar búsqueda y filtros
    -   _Requirements: 4.1, 4.2, 4.3_

-   [ ] 5.2 Crear vistas de gestión de tarifas

    -   Implementar admin/pagos/tarifas/index.blade.php
    -   Crear formularios de creación y edición
    -   Implementar listado con filtros por consultor/empresa
    -   _Requirements: 4.1, 4.4_

-   [ ] 6. Implementar configuración fiscal
-   [ ] 6.1 Crear controlador de configuración fiscal

    -   Implementar ConfiguracionFiscalController
    -   Crear validaciones de porcentajes
    -   Implementar historial de cambios
    -   _Requirements: 5.1, 5.2, 5.3, 5.4_

-   [ ] 6.2 Crear vista de configuración fiscal

    -   Implementar admin/pagos/configuracion.blade.php
    -   Crear formulario de configuración IVA/ISLR
    -   Mostrar historial de configuraciones
    -   _Requirements: 5.1, 5.2_

-   [ ] 7. Implementar sistema de reportes
-   [ ] 7.1 Crear ReportePagosService

    -   Implementar generación de reportes por período
    -   Crear reportes por consultor y empresa
    -   Implementar cálculos de totales y subtotales
    -   _Requirements: 6.1, 6.2, 6.3, 6.5_

-   [ ] 7.2 Implementar exportación de reportes

    -   Crear exportación a PDF usando DomPDF
    -   Implementar exportación a Excel usando PhpSpreadsheet
    -   Crear templates de reportes
    -   _Requirements: 6.4_

-   [ ] 7.3 Crear interfaz de reportes

    -   Implementar admin/pagos/reportes.blade.php
    -   Crear filtros avanzados para reportes
    -   Implementar vista previa de reportes
    -   Agregar botones de exportación
    -   _Requirements: 6.1, 6.4_

-   [ ] 8. Integrar con sistema de tasas BCV
-   [ ] 8.1 Mejorar integración con TasaBcvService

    -   Implementar obtención automática de tasas en cálculos
    -   Crear fallback a tasas manuales
    -   Implementar validaciones de tasas
    -   _Requirements: 9.1, 9.2, 9.3, 9.4_

-   [ ] 8.2 Crear interfaz para gestión manual de tasas

    -   Implementar formulario de ingreso manual de tasas
    -   Crear validaciones de tasas manuales
    -   Mostrar advertencias cuando se usan tasas manuales
    -   _Requirements: 1.5, 9.4, 9.5_

-   [ ] 9. Implementar validaciones y seguridad
-   [ ] 9.1 Crear middleware de autorización

    -   Implementar middleware para acceso solo a administradores
    -   Crear validaciones de permisos por acción
    -   Implementar auditoría de acciones sensibles
    -   _Requirements: Todos los requirements de seguridad_

-   [ ] 9.2 Implementar validaciones de negocio

    -   Crear validaciones de duplicados de pagos
    -   Implementar validaciones de estados de pago
    -   Crear validaciones de rangos de fechas y montos
    -   _Requirements: 8.1, 8.2, 8.3, 8.4_

-   [ ] 10. Crear seeders y datos de prueba
-   [ ] 10.1 Crear seeders para configuración inicial

    -   Crear seeder para configuración fiscal inicial
    -   Implementar seeder de tarifas de ejemplo
    -   Crear datos de prueba para pagos
    -   _Requirements: Soporte para testing_

-   [ ] 10.2 Crear factory para testing

    -   Implementar factories para todos los modelos
    -   Crear datos de prueba realistas
    -   Implementar helpers para testing
    -   _Requirements: Soporte para testing_

-   [ ] 11. Implementar rutas y navegación
-   [ ] 11.1 Crear rutas del módulo de pagos

    -   Implementar todas las rutas en routes/web.php
    -   Crear grupos de rutas con middleware
    -   Implementar nombres de rutas consistentes
    -   _Requirements: Navegación del sistema_

-   [ ] 11.2 Actualizar navegación del admin

    -   Agregar enlaces del módulo de pagos al sidebar
    -   Crear breadcrumbs para todas las páginas
    -   Implementar indicadores de estado en navegación
    -   _Requirements: Experiencia de usuario_

-   [ ] 12. Crear tests unitarios y de integración
-   [ ] 12.1 Crear tests para servicios

    -   Implementar tests para PagoService
    -   Crear tests para TarifaService y ConfiguracionFiscalService
    -   Implementar tests para ReportePagosService
    -   _Requirements: Calidad del código_

-   [ ] 12.2 Crear tests de controladores

    -   Implementar tests para PagosController
    -   Crear tests para TarifasController
    -   Implementar tests de autorización
    -   _Requirements: Calidad del código_

-   [ ] 13. Optimización y performance
-   [ ] 13.1 Implementar optimizaciones de base de datos

    -   Crear índices necesarios en todas las tablas
    -   Implementar eager loading en consultas
    -   Optimizar consultas de reportes
    -   _Requirements: Performance del sistema_

-   [ ] 13.2 Implementar caching

    -   Crear cache para configuraciones fiscales
    -   Implementar cache para tarifas activas
    -   Crear cache para tasas BCV recientes
    -   _Requirements: Performance del sistema_

-   [ ] 14. Documentación y finalización
-   [ ] 14.1 Crear documentación técnica

    -   Documentar APIs y servicios
    -   Crear guía de uso del módulo
    -   Implementar comentarios en código
    -   _Requirements: Mantenibilidad_

-   [ ] 14.2 Testing final y ajustes
    -   Realizar testing completo del módulo
    -   Ajustar interfaz de usuario según feedback
    -   Optimizar performance final
    -   _Requirements: Calidad final del producto_
