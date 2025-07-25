# Plan de Implementación - Gestión de Pagos de Honorarios

-   [ ] 1. Configurar estructura base y modelos

    -   Crear migraciones para índices de optimización en tablas existentes
    -   Extender modelos existentes (Usuario, RegistroHoras, Pago) con relaciones y scopes necesarios
    -   Crear modelo DatosLaborales si no existe, con relación a usuarios
    -   _Requisitos: 1.1, 1.4, 1.5_

-   [ ] 2. Implementar servicios de cálculo de pagos

    -   Crear CalculadoraPagosService con métodos para calcular honorarios, IVA, ISLR
    -   Implementar integración con tabla tasas_bcv para obtener tasa de cambio actual
    -   Crear métodos para aplicar configuraciones de impuestos desde tabla configuraciones
    -   Escribir pruebas unitarias para todos los cálculos
    -   _Requisitos: 3.2, 3.4, 6.1, 6.2_

-   [ ] 3. Desarrollar controlador de aprobación de horas

    -   Crear HorasController con métodos index, aprobar, rechazar
    -   Implementar validaciones para cambios de estado en registros_horas
    -   Crear formularios de aprobación/rechazo con observaciones
    -   Integrar filtros por consultor, empresa y fecha
    -   _Requisitos: 2.1, 2.2, 2.3, 2.4_

-   [ ] 4. Implementar generación de órdenes de pago

    -   Crear métodos en PagosController para generar registros de pago
    -   Implementar lógica para agrupar horas aprobadas por consultor y quincena
    -   Integrar cálculos automáticos usando CalculadoraPagosService
    -   Crear validaciones para evitar pagos duplicados
    -   _Requisitos: 3.1, 3.2, 3.3, 3.5_

-   [ ] 5. Desarrollar gestión de estados de pagos

    -   Implementar métodos para cambiar estado de pagos (pendiente → pagado → anulado)
    -   Crear sistema de observaciones para cambios de estado
    -   Implementar validaciones de permisos para cambios de estado
    -   Agregar timestamps automáticos para auditoría
    -   _Requisitos: 4.1, 4.2, 4.3, 4.4, 4.5_

-   [ ] 6. Crear vistas de aprobación de horas

    -   Diseñar vista index usando el estilo de cards existente con colores corporativos ALI3000
    -   Implementar tabla responsiva con filtros y paginación manteniendo el diseño actual
    -   Crear modales para aprobar/rechazar usando el estilo de componentes existentes
    -   Integrar con fuente Plus Jakarta Sans y paleta exacta de la landing: #4682B4 (Steel Blue), #FF6347 (Tomato), #708090 (Slate Gray), #000000 (títulos)
    -   _Requisitos: 2.1, 2.2, 2.3, 2.4_

-   [ ] 7. Desarrollar interfaz de gestión de pagos

    -   Crear vista index de pagos con tabla filtrable por estado, consultor, fecha
    -   Implementar vista de detalle de pago con todos los cálculos mostrados
    -   Crear formulario de generación de pagos desde horas aprobadas
    -   Diseñar interfaz para cambio de estados con confirmaciones
    -   _Requisitos: 3.1, 4.5, 5.4_

-   [ ] 8. Implementar widgets de métricas en dashboard

    -   Extender DashboardController con métodos para métricas de pagos
    -   Crear widgets para: total pagado mensual, pagos pendientes, consultores activos
    -   Implementar gráficos de tendencias mensuales usando Chart.js
    -   Integrar métricas con el dashboard existente manteniendo el diseño
    -   _Requisitos: 5.1, 5.5_

-   [ ] 9. Desarrollar sistema de reportes

    -   Crear ReportesService para generar reportes mensuales y por período
    -   Implementar exportación a Excel usando Laravel Excel
    -   Crear vista de reportes con filtros avanzados
    -   Integrar visualización de datos con tablas y gráficos
    -   _Requisitos: 5.2, 5.4_

-   [ ] 10. Implementar gestión de consultores y tarifas

    -   Crear/extender ConsultoresController para gestionar datos laborales
    -   Implementar formularios para editar tarifas por hora
    -   Crear interfaz para asignar consultores a empresas
    -   Integrar validaciones para cambios de tarifas
    -   _Requisitos: 1.1, 1.2, 1.3, 1.4_

-   [ ] 11. Integrar navegación en sidebar existente

    -   Expandir sección "Financiero" del sidebar con nuevos enlaces: "Aprobar Horas", "Generar Pagos"
    -   Actualizar sección "Gestión" con enlace mejorado a gestión de horas
    -   Mantener el estilo visual exacto de la landing: colores #4682B4 (Steel Blue), #FF6347 (Tomato), #708090 (Slate Gray), #000000 (títulos) y fuente Plus Jakarta Sans
    -   Actualizar badges/contadores dinámicos en tiempo real (horas pendientes, pagos por procesar)
    -   _Requisitos: Todos los módulos_

-   [ ] 12. Implementar validaciones y manejo de errores

    -   Crear Form Requests para validación de datos en todos los formularios
    -   Implementar manejo de excepciones personalizadas (TasaBCVNoDisponible, etc.)
    -   Agregar validaciones de negocio en servicios
    -   Crear mensajes de error y éxito consistentes con el diseño
    -   _Requisitos: 2.4, 3.4, 6.2_

-   [ ] 13. Optimizar rendimiento y agregar cache

    -   Implementar cache para métricas del dashboard (5 minutos)
    -   Agregar índices de base de datos para consultas frecuentes
    -   Implementar eager loading en consultas con relaciones
    -   Optimizar consultas de reportes con agregaciones
    -   _Requisitos: 5.1, 5.2_

-   [ ] 14. Escribir pruebas de integración

    -   Crear pruebas para flujo completo: aprobación → generación → pago
    -   Implementar pruebas de controladores con datos de prueba
    -   Crear pruebas de validaciones y manejo de errores
    -   Escribir pruebas de integración con tasa BCV
    -   _Requisitos: Todos_

-   [ ] 15. Implementar características de seguridad

    -   Agregar middleware de autenticación a todas las rutas
    -   Implementar validación de permisos por tipo de usuario
    -   Crear logs de auditoría para cambios críticos (aprobaciones, pagos)
    -   Implementar protección CSRF en todos los formularios
    -   _Requisitos: Todos los módulos_

-   [ ] 16. Crear documentación y guías de usuario
    -   Escribir documentación técnica del código
    -   Crear guía de usuario para el flujo de pagos
    -   Documentar configuraciones necesarias
    -   Crear manual de troubleshooting para errores comunes
    -   _Requisitos: Todos_
