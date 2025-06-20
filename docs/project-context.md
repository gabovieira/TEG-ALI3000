ALI 3000 - Sistema de Gestión de Horas Laborales y Honorarios
Contexto Completo del Proyecto
Fecha Inicio: 10 de Junio 2025
Duración: 8 semanas (56 días)
Tiempo disponible: 2h diarias + 4-5h fines de semana = 152 horas total
Tipo: Trabajo Especial de Grado - TSU Informática
________________________________________
🎯 Objetivo Principal
Desarrollar una aplicación web para la gestión de horas laborales y cálculo de honorarios de consultores en la empresa ALI 3000, C.A., Caracas, Venezuela.
Problema Actual:
•	Proceso manual de registro y validación de horas
•	Cálculos de honorarios en Excel propenso a errores
•	Falta de centralización y trazabilidad
•	Comunicación verbal entre validadores y consultores
Solución Propuesta:
Sistema web centralizado que automatice todo el proceso desde registro hasta pago.
________________________________________
🏗️ Arquitectura Técnica
Stack Tecnológico:
•	Backend: PHP 8.1+ (nativo, sin frameworks)
•	Base de Datos: MySQL 8.0
•	Frontend: HTML5 + CSS3 + JavaScript + Bootstrap 5
•	Notificaciones: WhatsApp API
•	APIs Externas: Tasa de cambio BCV
•	Servidor Local: XAMPP
Estructura de Proyecto:
ali3000/
├── config/
│   ├── database.php
│   ├── whatsapp_config.php
│   ├── constants.php
│   └── api_config.php
├── src/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ConsultorController.php
│   │   ├── ValidadorController.php
│   │   └── AdminController.php
│   ├── models/
│   │   ├── User.php
│   │   ├── TimeRecord.php
│   │   ├── Company.php
│   │   └── Payment.php
│   ├── views/
│   │   ├── consultor/
│   │   ├── validador/
│   │   ├── admin/
│   │   └── shared/
│   └── services/
│       ├── NotificationService.php
│       ├── PaymentService.php
│       └── ExchangeRateService.php
├── assets/
│   ├── css/
│   ├── js/
│   └── img/
├── docs/
│   ├── database-schema.sql
│   ├── api-endpoints.md
│   └── daily-progress.md
└── index.php
________________________________________
👥 Usuarios del Sistema
1. Consultores (C-XXXXXX)
Funciones:
•	Registrar horas diarias (máx 8h + extras)
•	Ver estatus de validación
•	Generar facturas post-pago
•	Consultar historial de pagos
Dashboard:
•	Módulo registrar actividades/horas
•	Quincena actual (ej: "1-15 Junio 2025")
•	Historial de pagos
•	Horas pendientes de validar/pagar
•	Generar factura
2. Validadores (V-XXXXXX)
Funciones:
•	Validar horas por días específicos
•	Aprobar o solicitar modificaciones
•	Gestionar retrasos y faltas
•	Límite: 8 horas para validar
Asignación:
•	Un validador por empresa cliente
•	Puede validar múltiples consultores de su empresa
3. Administradores (ALI 3000)
Funciones:
•	Crear usuarios (C-XXXXX / V-XXXXX)
•	Asignar consultores a empresas/validadores
•	Procesar pagos con botón Banesco
•	Generar comprobantes de pago
•	Configurar parámetros del sistema
________________________________________
🔄 Flujo Operativo Completo
Fase 1: Registro de Horas
1.	Consultor registra horas diarias con interfaz conversacional: 
o	"¡Hola Gabo! ¿Qué realizaste hoy?"
o	Selecciona fecha (flexible, días anteriores permitidos)
o	Elige empresa cliente
o	Describe actividades (con botones rápidos)
o	Marca horas normales (máx 8) + extras
o	Guardado automático como borrador
Fase 2: Envío Quincenal
2.	Días 15 y 30: Sistema envía recordatorio WhatsApp
3.	Consultor envía horas para validación
4.	Estatus cambia: "BORRADOR" → "PENDIENTE VALIDACIÓN"
5.	Notificación WhatsApp al Validador
Fase 3: Validación
6.	Validador revisa horas día por día
7.	Opciones: 
o	✅ Aprobar: Cambia a "VALIDADO"
o	❌ Solicitar modificación: Notifica al consultor específico
8.	Límite: 8 horas para validar
9.	Notificación WhatsApp al Admin y Consultor
Fase 4: Procesamiento de Pago
10.	Admin ve horas con estatus "VALIDADO"
11.	Cálculo automático: 
o	Costo por hora (configurable por consultor)
o	IVA: 16% (configurable)
o	ISLR: 3% retención (configurable)
o	Tasa BCV (API automática)
o	Conversión USD → Bs
12.	Botón de pago Banesco para procesar
13.	Genera comprobante automático
14.	Notificación WhatsApp al consultor
________________________________________
📊 Estructura de Base de Datos
Tablas Principales:
users
- id (PK)
- user_code (C-XXXXX / V-XXXXX)
- user_type (consultor/validador/admin)
- first_name, last_name
- document_id, birth_date
- hourly_rate (solo consultores)
- development_level (solo consultores)
- status (active/inactive)
- created_at
companies
- id (PK)
- name (PDVSA, Banesco, etc.)
- validator_id (FK)
- status
user_companies (relación many-to-many)
- user_id (FK)
- company_id (FK)
time_records
- id (PK)
- user_id (FK)
- company_id (FK)
- work_date
- activities (TEXT)
- normal_hours (max 8)
- extra_hours
- status (borrador/pendiente/validado/rechazado)
- validator_comments
- created_at, updated_at
payments
- id (PK)
- user_id (FK)
- period_start, period_end
- total_hours, total_extra_hours
- hourly_rate, total_usd
- iva_amount, islr_amount
- exchange_rate, total_bs
- payment_date
- receipt_number
- status (pendiente/pagado)
________________________________________
🔧 Configuraciones del Sistema
Parámetros Configurables (Admin):
•	IVA: 16% (modificable)
•	ISLR: 3% (modificable)
•	Tasa de cambio: API BCV automática
•	Costo por hora: Individual por consultor
•	Fechas de corte: 15 y último día del mes (fijas)
Excepciones:
•	Vacaciones: Registro especial con notificación
•	Permisos médicos: Registro especial
•	Feriados: Calendario BCV automático
________________________________________
📱 Sistema de Notificaciones (WhatsApp)
Eventos de Notificación:
Al subir horas (consultor → validador + admin):
•	"Gabo Vieira ha enviado 120 horas para validación (1-15 Jun)"
Al validar (validador → admin + consultor):
•	"Horas de Gabo Vieira validadas por PDVSA"
Modificaciones (validador → consultor):
•	"PDVSA solicita ajuste en horas del 5 de junio"
Pago procesado (admin → consultor):
•	"Pago procesado: Bs. 15.982,93 - Comprobante #ALI001"
Recordatorios automáticos:
•	Día 15: "Recordatorio: Envía tus horas de la primera quincena"
•	Día 30/31: "Recordatorio: Envía tus horas de la segunda quincena"
________________________________________
🚀 Plan de Desarrollo (8 Semanas)
Semana 1 (10-16 Jun): Fundaciones
•	Día 1-2: Setup XAMPP + Base de datos
•	Día 3-4: Sistema de autenticación
•	Día 5-7: Estructura MVC básica
Semana 2 (17-23 Jun): Gestión de Usuarios
•	Día 8-9: CRUD usuarios (C-XXXXX / V-XXXXX)
•	Día 10-11: Asignación empresas/consultores
•	Día 12-14: Dashboard básico por rol
Semana 3 (24-30 Jun): Registro de Horas
•	Día 15-16: Interfaz conversacional registro
•	Día 17-18: Validación y guardado
•	Día 19-21: Gestión de estados
Semana 4 (1-7 Jul): Validación y Flujo
•	Día 22-23: Panel validador
•	Día 24-25: Proceso aprobación/rechazo
•	Día 26-28: Notificaciones WhatsApp
Semana 5 (8-14 Jul): Cálculos y Pagos
•	Día 29-30: Motor de cálculos automático
•	Día 31-32: Integración tasa BCV
•	Día 33-35: Sistema de pagos
Semana 6 (15-21 Jul): Panel Admin
•	Día 36-37: Dashboard administrativo
•	Día 38-39: Gestión configuraciones
•	Día 40-42: Reportes y comprobantes
Semana 7 (22-28 Jul): Integraciones
•	Día 43-44: API WhatsApp completa
•	Día 45-46: Botón pago Banesco
•	Día 47-49: Generación facturas
Semana 8 (29-4 Ago): Testing y Pulimiento
•	Día 50-51: Testing integral
•	Día 52-53: Optimización UI/UX
•	Día 54-56: Documentación y entrega
________________________________________
🎨 Características UX Clave
Interfaz Conversacional:
•	Saludos personalizados
•	Preguntas naturales tipo chat
•	Botones de acceso rápido
•	Diseño atractivo con gradientes
Funcionalidades Smart:
•	Guardado automático
•	Cálculos en tiempo real
•	Notificaciones push
•	Responsive design
Controles de Calidad:
•	Validación de máximos (8h normales)
•	Prevención de duplicados
•	Límites de tiempo para acciones
•	Auditoría de cambios
________________________________________
📋 Casos Especiales
Flujo de Rechazo:
1.	Validador rechaza con comentarios
2.	Notificación WhatsApp al consultor
3.	Consultor corrige y reenvía
4.	Nuevo ciclo de validación
Correcciones Post-Aprobación:
•	Solo Admin puede devolver para corrección
•	Se notifica a consultor y validador
•	Nueva validación requerida
Consultor sin registro a tiempo:
•	No recibe datos para factura
•	No se procesa pago
•	Queda pendiente para siguiente período


