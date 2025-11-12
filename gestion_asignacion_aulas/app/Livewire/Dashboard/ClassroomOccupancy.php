<?php

namespace App\Livewire\Dashboard;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Module;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ClassroomOccupancy extends Component
{
    use WithPagination;

    public $filterModulo = '';
    public $filterBloque = '';
    public $selectedDay = '';
    public $showDetailsModal = false;
    public $selectedClassroom = null;

    // Filtros de estado
    public $showDisponible = true;
    public $showEnUso = true;
    public $showProxima = true;

    public function mount()
    {
        $this->selectedDay = Carbon::now()->dayOfWeek;
    }

    public function refresh()
    {
        // Este método solo fuerza un re-render del componente
        $this->render();
    }

    // Resetear paginación cuando cambian los filtros
    public function updatedFilterModulo()
    {
        $this->resetPage();
    }

    public function updatedShowDisponible()
    {
        $this->resetPage();
    }

    public function updatedShowEnUso()
    {
        $this->resetPage();
    }

    public function updatedShowProxima()
    {
        $this->resetPage();
    }

    public function updatedSelectedDay()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $modulos = Module::orderBy('code')->get();
        $classrooms = $this->getClassrooms();

        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo'
        ];

        return view('livewire.dashboard.classroom-occupancy', [
            'modulos' => $modulos,
            'classrooms' => $classrooms,
            'dias' => $dias,
        ]);
    }

    private function getClassrooms()
    {
        $query = Classroom::with(['module']);

        if (!empty($this->filterModulo)) {
            $query->where('module_id', $this->filterModulo);
        }

        $classrooms = $query->orderBy('number')->get();

        // Filtrar por estado
        $filteredClassrooms = $classrooms->filter(function ($classroom) {
            $occupancy = $this->getOccupancyStatus($classroom);

            if ($occupancy['status'] === 'disponible' && !$this->showDisponible) {
                return false;
            }
            if ($occupancy['status'] === 'en_uso' && !$this->showEnUso) {
                return false;
            }
            if ($occupancy['status'] === 'proxima' && !$this->showProxima) {
                return false;
            }

            return true;
        });

        // Implementar paginación manual para colecciones
        $perPage = 15;
        $currentPage = $this->paginators['page'] ?? 1;
        $total = $filteredClassrooms->count();

        $items = $filteredClassrooms->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }

    public function getCurrentAssignment($classroomId)
    {
        $now = Carbon::now();

        // Determinar el día a consultar (hoy o día seleccionado)
        $dayOfWeek = $this->selectedDay !== '' ? intval($this->selectedDay) : $now->dayOfWeek;

        // Mapeo de números a nombres de días en inglés
        $dayNames = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        $dayName = $dayNames[$dayOfWeek];

        // Solo verificar la hora actual si estamos viendo el día de hoy
        $isToday = $dayOfWeek === $now->dayOfWeek;
        $currentTime = $now->format('H:i:s');

        $query = Assignment::with([
            'userSubject.subject',
            'userSubject.user',
            'group',
            'daySchedule.day',
            'daySchedule.schedule',
            'academicManagement'
        ])
        ->where('classroom_id', $classroomId)
        ->whereHas('academicManagement', function ($query) {
            $query->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
        })
        ->whereHas('daySchedule', function ($query) use ($dayName, $currentTime, $isToday) {
            $query->whereHas('day', function ($q) use ($dayName) {
                $q->where('name', $dayName);
            });

            // Solo filtrar por hora si es el día actual
            if ($isToday) {
                $query->whereHas('schedule', function ($q) use ($currentTime) {
                    $q->whereTime('start', '<=', $currentTime)
                      ->whereTime('end', '>=', $currentTime);
                });
            }
        });

        // Si no es hoy, devolver la primera asignación del día
        if ($isToday) {
            return $query->first();
        } else {
            // Para otros días, retornar null ya que no hay clase "actual"
            return null;
        }
    }

    public function getUpcomingAssignment($classroomId)
    {
        $now = Carbon::now();

        // Determinar el día a consultar (hoy o día seleccionado)
        $dayOfWeek = $this->selectedDay !== '' ? intval($this->selectedDay) : $now->dayOfWeek;

        // Mapeo de números a nombres de días en inglés
        $dayNames = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];

        $dayName = $dayNames[$dayOfWeek];

        // Solo verificar la hora actual si estamos viendo el día de hoy
        $isToday = $dayOfWeek === $now->dayOfWeek;
        $currentTime = $now->format('H:i:s');
        $futureTime = $now->copy()->addMinutes(30)->format('H:i:s');

        $query = Assignment::with([
            'userSubject.subject',
            'userSubject.user',
            'group',
            'daySchedule.day',
            'daySchedule.schedule',
            'academicManagement'
        ])
        ->where('classroom_id', $classroomId)
        ->whereHas('academicManagement', function ($query) {
            $query->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
        })
        ->whereHas('daySchedule', function ($query) use ($dayName, $currentTime, $futureTime, $isToday) {
            $query->whereHas('day', function ($q) use ($dayName) {
                $q->where('name', $dayName);
            });

            // Solo filtrar por hora próxima si es el día actual
            if ($isToday) {
                $query->whereHas('schedule', function ($q) use ($currentTime, $futureTime) {
                    $q->whereTime('start', '>', $currentTime)
                      ->whereTime('start', '<=', $futureTime);
                });
            }
        });

        // Si es hoy, buscar la próxima clase en 30 minutos
        if ($isToday) {
            return $query->orderBy('id')->first();
        } else {
            // Para otros días, mostrar la primera clase del día
            return $query->orderBy('id')->first();
        }
    }

    public function getOccupancyStatus($classroom)
    {
        $current = $this->getCurrentAssignment($classroom->id);
        $upcoming = $this->getUpcomingAssignment($classroom->id);

        if ($current) {
            return [
                'status' => 'en_uso',
                'color' => 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20',
                'label' => 'En Uso',
                'assignment' => $current
            ];
        }

        if ($upcoming) {
            return [
                'status' => 'proxima',
                'color' => 'border-orange-500 bg-orange-50 dark:bg-orange-900/20',
                'label' => 'Próxima',
                'assignment' => $upcoming
            ];
        }

        return [
            'status' => 'disponible',
            'color' => 'border-green-500 bg-green-50 dark:bg-green-900/20',
            'label' => 'Disponible',
            'assignment' => null
        ];
    }

    public function showClassroomDetails($classroomId)
    {
        $this->selectedClassroom = Classroom::with(['module'])->find($classroomId);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedClassroom = null;
    }
}
