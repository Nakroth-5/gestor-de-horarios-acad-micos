# Sistema de Marcado Automático de Ausencias

## 📋 Descripción

Sistema automático que crea registros de asistencia con estado "ausente" para las clases que ya finalizaron y no tienen registro de asistencia. Esto permite:

- ✅ Auditoría completa de todas las clases
- ✅ Registro automático de ausencias cuando el docente no marca asistencia
- ✅ Reportes completos con todas las clases del periodo

## 🚀 Uso Manual

### Marcar ausencias de hoy
```bash
php artisan attendance:mark-absent
```

### Marcar ausencias de una fecha específica
```bash
php artisan attendance:mark-absent --date=2025-11-10
```

## ⚙️ Ejecución Automática

El comando está programado para ejecutarse **automáticamente cada hora** entre las 6:00 AM y 10:00 PM.

### Configuración en `routes/console.php`:
```php
Schedule::command('attendance:mark-absent')
    ->hourly()
    ->between('06:00', '22:00')
    ->name('mark-absent-attendances')
    ->withoutOverlapping();
```

## 📊 Funcionamiento

### Proceso automático:

1. **Cada hora**, el sistema revisa todas las clases programadas para ese día
2. **Verifica** si la hora de finalización de cada clase ya pasó
3. **Comprueba** si existe un registro de asistencia para esa semana
4. **Crea** un registro con `status = 'absent'` si:
   - La clase ya terminó
   - No existe registro de asistencia
   - El docente no generó el QR ni marcó asistencia

### Lógica de detección:

```php
// Se marca ausente si:
- Hora actual > Hora de fin de clase
- No existe AttendanceRecord para esa semana
- assignment_id + user_id + semana actual = sin registro
```

## 🔄 Activar el Programador de Tareas

Para que los comandos programados funcionen, necesitas configurar el **cron job** en tu servidor:

### En Linux/Mac (crontab):
```bash
* * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

### En desarrollo local:
```bash
php artisan schedule:work
```

Este comando ejecuta el scheduler en modo interactivo y ejecutará todos los comandos programados.

## 📝 Logs

Los registros se guardan en `storage/logs/laravel.log`:

- ✅ Éxito: "Comando attendance:mark-absent ejecutado exitosamente"
- ❌ Error: "Error al ejecutar comando attendance:mark-absent"

## 🎯 Ejemplo de Salida

```
🔍 Iniciando proceso de marcado automático de ausencias...
📅 Procesando fecha: 11/11/2025
📚 Periodo académico: Gestión 2/2025
📆 Día de la semana: Monday
📋 Total de clases programadas hoy: 15

  ✓ Ausencia creada: Juan Pérez - Matemáticas I - SB
  ✓ Ausencia creada: María López - Física II - SA
  ✓ Ausencia creada: Carlos Gómez - Programación - SC

✅ Proceso completado:
   • Registros de ausencia creados: 3
   • Clases omitidas (ya registradas o no finalizadas): 12
```

## 🛠️ Mantenimiento

### Ver comandos programados:
```bash
php artisan schedule:list
```

### Probar ejecución de comandos programados:
```bash
php artisan schedule:test
```

### Ver próxima ejecución:
```bash
php artisan schedule:work --verbose
```

## ⚠️ Notas Importantes

1. **No crea duplicados**: Si ya existe un registro de asistencia para la semana, no crea otro
2. **Respeta horarios**: Solo marca ausente clases que ya terminaron
3. **Por semana**: Cada docente tiene un registro por clase por semana
4. **Auditoría completa**: Ahora todos los reportes mostrarán información completa

## 🔍 Verificación en Base de Datos

```sql
-- Ver registros de ausencia creados automáticamente
SELECT 
    ar.id,
    u.name as docente,
    s.name as materia,
    ar.status,
    ar.scan_time,
    ar.created_at
FROM attendance_records ar
JOIN users u ON ar.user_id = u.id
JOIN assignments a ON ar.assignment_id = a.id
JOIN user_subjects us ON a.user_subject_id = us.id
JOIN subjects s ON us.subject_id = s.id
WHERE ar.status = 'absent' 
AND ar.scan_time IS NULL
ORDER BY ar.created_at DESC;
```

## 📈 Impacto en Reportes

### Antes:
- ❌ Clases sin registro no aparecían en reportes
- ❌ Imposible hacer auditoría completa
- ❌ Estadísticas incompletas

### Después:
- ✅ Todas las clases aparecen en reportes
- ✅ Auditoría completa de asistencias
- ✅ Estadísticas precisas (presentes/ausentes/tardanzas)
- ✅ Trazabilidad completa del sistema
