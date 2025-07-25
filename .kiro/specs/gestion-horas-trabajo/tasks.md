# Implementation Plan - Sistema de Gestión de Horas de Trabajo y Control de Asistencia

-   [ ] 1. Crear estructura base de datos y modelos

    -   Crear migración para tabla horario_empresa con campos de horarios por empresa
    -   Crear migración para tabla registro_asistencia con control de entrada/salida
    -   Crear migración para tabla descuentos con gestión de penalizaciones
    -   Crear migración para tabla politica_descuento con configuración de reglas
    -   Modificar migración de registro_horas para incluir campos de estado y aprobación
    -   _Requirements: 6.1, 6.2, 7.1, 9.1, 10.1_

-   [ ] 2. Implementar modelos Eloquent con relaciones

    -   Crear modelo HorarioEmpresa con validaciones y relaciones
    -   Crear modelo RegistroAsistencia con cálculos automáticos
    -   Crear modelo Descuento con estados y aprobaciones
    -   Crear modelo PoliticaDescuento con reglas de negocio
    -   Extender modelo RegistroHoras con nuevos campos y estados
    -   Definir todas las relaciones entre modelos (belongsTo, hasMany)
    -   _Requirements: 1.1, 2.1, 3.1, 6.1, 7.1_

-   [ ] 3. Crear servicios de lógica de negocio
-   [ ] 3.1 Implementar HorasService para gestión de horas

    -   Crear método validateHours para validar límites diarios
    -   Crear método calculateDailyTotal para sumar horas por día
    -   Crear método applyBusinessRules para reglas de antigüedad y feriados
    -   Escribir tests unitarios para validaciones de horas
    -   _Requirements: 1.3, 1.4, 5.1, 5.2_

-   [ ] 3.2 Implementar AsistenciaService para control de asistencia

    -   Crear método detectarLlegadaTarde comparando con horarios
    -   Crear método detectarFalta para días sin registro
    -   Crear método calcularHorasTrabajadas entre entrada y salida
    -   Escribir tests unitarios para detección de retrasos y faltas
    -   _Requirements: 7.1, 7.2, 7.3, 7.4_

-   [ ] 3.3 Implementar DescuentoService para cálculo de descuentos

    -   Crear método calcularDescuentoRetraso según políticas
    -   Crear método calcularDescuentoFalta para días completos
    -   Crear método aplicarDescuento al registro del consultor
    -   Escribir tests unitarios para cálculos de descuentos
    -   _Requirements: 9.2, 9.3, 10.2, 10.3_

-   [ ] 4. Desarrollar controladores para consultores
-   [ ] 4.1 Implementar HorasController para registro de horas

    -   Crear método store para guardar nuevos registros de horas
    -   Crear método index con filtros por estado y fechas
    -   Crear método edit/update para modificar registros pendientes
    -   Crear método destroy para eliminar registros pendientes
    -   Implementar validaciones de formulario y reglas de negocio
    -   _Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 2.1, 2.2, 2.5_

-   [ ] 4.2 Implementar AsistenciaController para registro de entrada/salida

    -   Crear método registrarEntrada con validación de horarios
    -   Crear método registrarSalida con cálculo de horas trabajadas
    -   Crear método verAsistencia para mostrar registros del consultor
    -   Implementar detección automática de retrasos y faltas
    -   _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

-   [ ] 5. Desarrollar controladores para administradores
-   [ ] 5.1 Implementar gestión de aprobación de horas

    -   Crear método index para listar horas pendientes de aprobación
    -   Crear método show para ver detalles de registro específico
    -   Crear método approve para aprobar registros individuales
    -   Crear método reject con motivo de rechazo obligatorio
    -   Crear método bulkApprove para aprobaciones masivas
    -   _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

-   [ ] 5.2 Implementar configuración de horarios por empresa

    -   Crear método configurarHorario para establecer horarios
    -   Crear método validarHorario para verificar lógica de horarios
    -   Crear método obtenerHorario para consultar horarios activos
    -   Implementar historial de cambios de horarios
    -   _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

-   [ ] 5.3 Implementar gestión de descuentos

    -   Crear método index para listar descuentos pendientes
    -   Crear método aprobar para confirmar descuentos automáticos
    -   Crear método rechazar con justificación obligatoria
    -   Crear método configurarPoliticas para establecer reglas
    -   _Requirements: 9.1, 9.2, 9.3, 9.4, 9.5, 10.1, 10.2, 10.3, 10.4, 10.5_

-   [ ] 6. Crear vistas para consultores
-   [ ] 6.1 Implementar formulario de registro de horas

    -   Crear vista create con formulario de registro de horas
    -   Implementar selector de empresa filtrado por asignaciones
    -   Agregar validación JavaScript para límites de horas
    -   Mostrar total de horas del día en tiempo real
    -   _Requirements: 1.1, 1.2, 1.3, 1.4_

-   [ ] 6.2 Implementar lista de horas registradas

    -   Crear vista index con tabla de horas del consultor
    -   Implementar filtros por estado, fecha y empresa
    -   Agregar acciones de editar/eliminar para registros pendientes
    -   Mostrar estados con colores distintivos
    -   _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

-   [ ] 6.3 Implementar registro de asistencia

    -   Crear vista para registro de entrada/salida por empresa
    -   Mostrar horarios establecidos y tolerancias
    -   Implementar botones de entrada/salida con confirmación
    -   Mostrar resumen diario de asistencia y retrasos
    -   _Requirements: 8.1, 8.2, 8.3, 8.5_

-   [ ] 7. Crear vistas para administradores
-   [ ] 7.1 Implementar gestión de aprobación de horas

    -   Crear vista index con lista de horas pendientes
    -   Implementar vista show con detalles completos del registro
    -   Agregar formulario de rechazo con campo de motivo
    -   Implementar selección múltiple para aprobaciones masivas
    -   _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5_

-   [ ] 7.2 Implementar configuración de horarios

    -   Crear formulario de configuración de horarios por empresa
    -   Implementar validación de horarios lógicos
    -   Mostrar historial de cambios de horarios
    -   Agregar vista previa de impacto en consultores
    -   _Requirements: 6.1, 6.2, 6.3, 6.4_

-   [ ] 7.3 Implementar gestión de descuentos

    -   Crear vista index con descuentos pendientes de aprobación
    -   Implementar vista de configuración de políticas de descuento
    -   Agregar formularios de aprobación/rechazo de descuentos
    -   Mostrar cálculos automáticos y justificaciones
    -   _Requirements: 9.1, 9.2, 9.3, 9.4, 10.1, 10.2, 10.3_

-   [ ] 8. Implementar sistema de reportes
-   [ ] 8.1 Crear reportes de horas trabajadas

    -   Implementar reporte por consultor con filtros de período
    -   Crear reporte por empresa con totales y promedios
    -   Agregar exportación a PDF y Excel
    -   Implementar gráficos de estadísticas y tendencias
    -   _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

-   [ ] 8.2 Crear reportes de asistencia y descuentos

    -   Implementar reporte de puntualidad por consultor
    -   Crear reporte de descuentos aplicados por período
    -   Agregar gráficos de tendencias de asistencia
    -   Implementar comparativas entre empresas
    -   _Requirements: 11.1, 11.2, 11.3, 11.4, 11.5_

-   [ ] 9. Implementar sistema de notificaciones

    -   Crear servicio de notificaciones para eventos de asistencia
    -   Implementar notificación inmediata de llegadas tarde
    -   Crear notificación diaria de faltas detectadas
    -   Implementar alertas de patrones de impuntualidad
    -   Agregar notificaciones de descuentos aplicados
    -   Crear resumen de fin de período con descuentos
    -   _Requirements: 12.1, 12.2, 12.3, 12.4, 12.5_

-   [ ] 10. Crear rutas y middleware de seguridad

    -   Definir rutas para consultores con middleware auth
    -   Definir rutas para administradores con middleware admin
    -   Implementar middleware de verificación de asignación empresa-consultor
    -   Agregar middleware de validación de horarios activos
    -   Crear middleware de auditoría para acciones críticas
    -   _Requirements: 1.2, 3.1, 6.3, 8.4, 9.1_

-   [ ] 11. Implementar validaciones y reglas de negocio

    -   Crear FormRequest para validación de registro de horas
    -   Implementar FormRequest para configuración de horarios
    -   Crear validaciones personalizadas para reglas de negocio
    -   Implementar middleware de validación de fechas y límites
    -   Agregar validaciones de integridad de datos
    -   _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

-   [ ] 12. Crear seeders y datos de prueba

    -   Crear seeder para horarios de ejemplo por empresa
    -   Implementar seeder para políticas de descuento básicas
    -   Crear registros de prueba de horas y asistencia
    -   Agregar usuarios de prueba con diferentes roles
    -   Implementar factory para generar datos de testing
    -   _Requirements: 6.1, 10.1, 1.1, 7.1_

-   [ ] 13. Escribir tests de integración

    -   Crear tests de flujo completo de registro de horas
    -   Implementar tests de workflow de aprobación
    -   Crear tests de detección automática de faltas y retrasos
    -   Implementar tests de cálculo de descuentos
    -   Agregar tests de generación de reportes
    -   _Requirements: 1.1-1.5, 3.1-3.5, 7.1-7.5, 9.1-9.5_

-   [ ] 14. Optimizar rendimiento y seguridad
    -   Implementar índices de base de datos para consultas frecuentes
    -   Agregar cache para horarios y políticas de descuento
    -   Implementar paginación en listados grandes
    -   Crear sistema de auditoría para cambios críticos
    -   Agregar rate limiting para APIs de registro
    -   _Requirements: 4.1, 4.2, 11.1, 11.2_
