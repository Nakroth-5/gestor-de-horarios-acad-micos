<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Aulas Disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.3;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        .header h1 {
            margin: 0;
            font-size: 14px;
            color: #333;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 8px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .summary-box {
            display: table-cell;
            width: 50%;
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .summary-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
        }
        .available { color: #059669; }
        .occupied { color: #dc2626; }
        
        .section-title {
            font-size: 11px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            padding: 5px;
            background-color: #f0f0f0;
            border-left: 3px solid #333;
        }
        .section-title.available-section {
            border-left-color: #059669;
            background-color: #d1fae5;
            color: #065f46;
        }
        .section-title.occupied-section {
            border-left-color: #dc2626;
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 8px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9px;
        }
        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
        }
        .badge-available {
            background-color: #059669;
            color: white;
        }
        .badge-occupied {
            background-color: #dc2626;
            color: white;
        }
        
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE DISPONIBILIDAD DE AULAS</h1>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
        <p><strong>Generado:</strong> {{ $generatedAt }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="summary-label">Aulas Disponibles</div>
            <div class="summary-value available">{{ $availableClassrooms->count() }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-label">Aulas Ocupadas</div>
            <div class="summary-value occupied">{{ $occupiedClassrooms->count() }}</div>
        </div>
    </div>

    <!-- Aulas Disponibles -->
    <div class="section-title available-section">✓ AULAS DISPONIBLES ({{ $availableClassrooms->count() }})</div>
    
    @if($availableClassrooms->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Aula</th>
                    <th style="width: 35%;">Infraestructura</th>
                    <th style="width: 15%;">Capacidad</th>
                    <th style="width: 15%;">Tipo</th>
                    <th style="width: 15%;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availableClassrooms as $classroom)
                    <tr>
                        <td><strong>{{ $classroom->name }}</strong></td>
                        <td>{{ $classroom->infrastructure->name }}</td>
                        <td>{{ $classroom->capacity }}</td>
                        <td>{{ ucfirst($classroom->type) }}</td>
                        <td><span class="status-badge badge-available">DISPONIBLE</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #666; padding: 10px; font-size: 8px;">
            No hay aulas disponibles con los criterios seleccionados.
        </p>
    @endif

    <!-- Aulas Ocupadas -->
    <div class="section-title occupied-section">✗ AULAS OCUPADAS ({{ $occupiedClassrooms->count() }})</div>
    
    @if($occupiedClassrooms->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Aula</th>
                    <th style="width: 20%;">Infraestructura</th>
                    <th style="width: 20%;">Materia</th>
                    <th style="width: 20%;">Docente</th>
                    <th style="width: 10%;">Grupo</th>
                    <th style="width: 15%;">Horario</th>
                </tr>
            </thead>
            <tbody>
                @foreach($occupiedClassrooms as $classroom)
                    @php
                        $assignment = $occupiedDetails[$classroom->id] ?? null;
                    @endphp
                    <tr style="background-color: #fee2e2;">
                        <td><strong>{{ $classroom->name }}</strong></td>
                        <td>{{ $classroom->infrastructure->name }}</td>
                        <td>{{ $assignment ? $assignment->userSubject->subject->name : '-' }}</td>
                        <td>{{ $assignment ? $assignment->userSubject->user->name : '-' }}</td>
                        <td>{{ $assignment ? $assignment->group->name : '-' }}</td>
                        <td>
                            @if($assignment)
                                {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->start)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($assignment->daySchedule->schedule->end)->format('H:i') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #666; padding: 10px; font-size: 8px;">
            Todas las aulas están disponibles.
        </p>
    @endif

    <div class="footer">
        <p>Sistema de Gestión de Asignación de Aulas - Reporte de Disponibilidad</p>
    </div>
</body>
</html>
