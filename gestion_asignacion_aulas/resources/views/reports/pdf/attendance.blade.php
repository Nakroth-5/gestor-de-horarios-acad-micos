<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Asistencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 10px;
        }
        .statistics {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
        }
        .stat-percentage {
            font-size: 9px;
            color: #999;
        }
        .stat-present { color: #059669; }
        .stat-absent { color: #dc2626; }
        .stat-late { color: #d97706; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #f0f0f0;
            padding: 8px 5px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-present {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-absent {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-late {
            background-color: #fef3c7;
            color: #92400e;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ASISTENCIA DOCENTE</h1>
        <p><strong>Periodo:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p><strong>Generado:</strong> {{ $generatedAt }}</p>
    </div>

    <div class="statistics">
        <div class="stat-box">
            <div class="stat-label">Total de Registros</div>
            <div class="stat-value">{{ $statistics['total'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Presentes</div>
            <div class="stat-value stat-present">{{ $statistics['present'] }}</div>
            <div class="stat-percentage">{{ $statistics['present_percentage'] }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Ausentes</div>
            <div class="stat-value stat-absent">{{ $statistics['absent'] }}</div>
            <div class="stat-percentage">{{ $statistics['absent_percentage'] }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Tardanzas</div>
            <div class="stat-value stat-late">{{ $statistics['late'] }}</div>
            <div class="stat-percentage">{{ $statistics['late_percentage'] }}%</div>
        </div>
    </div>

    @if($attendanceRecords->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="8%">Fecha</th>
                    <th width="18%">Docente</th>
                    <th width="22%">Materia</th>
                    <th width="12%">Grupo</th>
                    <th width="12%">Horario</th>
                    <th width="10%">Estado</th>
                    <th width="10%">Hora Marcado</th>
                    <th width="8%">Día</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceRecords as $record)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}</td>
                        <td>{{ $record->user->name }}</td>
                        <td>
                            <strong>{{ $record->assignment->userSubject->subject->name }}</strong><br>
                            <span style="color: #666; font-size: 8px;">{{ $record->assignment->userSubject->subject->code }}</span>
                        </td>
                        <td>{{ $record->assignment->group->name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($record->assignment->daySchedule->schedule->start)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($record->assignment->daySchedule->schedule->end)->format('H:i') }}
                        </td>
                        <td>
                            @if($record->status === 'present')
                                <span class="status-badge status-present">Presente</span>
                            @elseif($record->status === 'late')
                                <span class="status-badge status-late">Tardanza</span>
                            @else
                                <span class="status-badge status-absent">Ausente</span>
                            @endif
                        </td>
                        <td>{{ $record->scan_time ? \Carbon\Carbon::parse($record->scan_time)->format('H:i:s') : 'NO MARCO' }}</td>
                        <td>{{ __($record->assignment->daySchedule->day->name) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #666; margin-top: 50px;">
            No se encontraron registros de asistencia para el periodo seleccionado.
        </p>
    @endif

    <div class="footer">
        <p>Sistema de Gestión de Asignación de Aulas - Reporte de Asistencia Docente</p>
    </div>
</body>
</html>
