<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Horarios Semanales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        .day-cell {
            background-color: #f9f9f9;
            font-weight: bold;
            vertical-align: top;
        }
        .subject-name {
            font-weight: bold;
            color: #333;
        }
        .subject-code {
            color: #666;
            font-size: 9px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE HORARIOS SEMANALES</h1>
        <p><strong>Periodo Académico:</strong> {{ $academicManagement->name }}</p>
        @if($group)
            <p><strong>Grupo:</strong> {{ $group->name }}</p>
        @endif
        <p><strong>Generado:</strong> {{ $generatedAt }}</p>
    </div>

    @if(count($scheduleData) > 0)
        <table>
            <thead>
                <tr>
                    <th width="10%">Día</th>
                    <th width="12%">Horario</th>
                    <th width="20%">Materia</th>
                    <th width="18%">Docente</th>
                    <th width="15%">Grupo</th>
                    <th width="15%">Aula</th>
                    <th width="10%">Infraestructura</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scheduleData as $day => $assignments)
                    @foreach($assignments as $index => $assignment)
                        <tr>
                            @if($index === 0)
                                <td rowspan="{{ $assignments->count() }}" class="day-cell">
                                    {{ __($day) }}
                                </td>
                            @endif
                            <td>
                                {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->start)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->end)->format('H:i') }}
                            </td>
                            <td>
                                <div class="subject-name">{{ $assignment->userSubject->subject->name }}</div>
                                <div class="subject-code">{{ $assignment->userSubject->subject->code }}</div>
                            </td>
                            <td>{{ $assignment->userSubject->user->name }}</td>
                            <td>{{ $assignment->group->name }}</td>
                            <td>{{ $assignment->classroom->name }}</td>
                            <td>{{ $assignment->classroom->infrastructure->name }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #666; margin-top: 50px;">
            No se encontraron horarios para mostrar.
        </p>
    @endif

    <div class="footer">
        <p>Sistema de Gestión de Asignación de Aulas</p>
    </div>
</body>
</html>
