<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WeeklySchedulesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $scheduleData;
    protected $academicManagement;
    protected $group;

    public function __construct($scheduleData, $academicManagement, $group = null)
    {
        $this->scheduleData = $scheduleData;
        $this->academicManagement = $academicManagement;
        $this->group = $group;
    }

    public function collection()
    {
        $data = collect();

        foreach ($this->scheduleData as $day => $assignments) {
            foreach ($assignments as $assignment) {
                $data->push($assignment);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Día',
            'Horario Inicio',
            'Horario Fin',
            'Código Materia',
            'Nombre Materia',
            'Docente',
            'Grupo',
            'Aula',
            'Infraestructura',
            'Capacidad',
        ];
    }

    public function map($assignment): array
    {
        return [
            __(trim($assignment->daySchedule->day->name)),
            \Carbon\Carbon::parse($assignment->daySchedule->schedule->start)->format('H:i'),
            \Carbon\Carbon::parse($assignment->daySchedule->schedule->end)->format('H:i'),
            $assignment->userSubject->subject->code,
            $assignment->userSubject->subject->name,
            $assignment->userSubject->user->name,
            $assignment->group->name,
            $assignment->classroom->name,
            $assignment->classroom->infrastructure->name,
            $assignment->classroom->capacity,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Horarios Semanales';
    }
}
