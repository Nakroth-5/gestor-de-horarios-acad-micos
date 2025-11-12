<?php

namespace App\Livewire\Dashboard;

use App\Models\AttendanceRecord;
use App\Models\User;
use App\Models\Subject;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AttendanceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filterDocente = '';
    public $filterGrupo = '';
    public $filterMateria = '';
    public $filterFechaInicio = '';
    public $filterFechaFin = '';
    public $perPage = 10;
    public $showModal = false;
    public $selectedDocenteId = null;

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['search', 'perPage'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $attendanceData = $this->getAttendanceData();
        $docentes = User::whereHas('roles', function ($query) {
            $query->where('name', 'Docente');
        })->orderBy('name')->get();
        $grupos = Group::orderBy('name')->get();
        $materias = Subject::orderBy('name')->get();
        $docenteDetails = $this->showModal ? $this->getDocenteDetails() : collect();
        $selectedDocente = $this->selectedDocenteId ? User::find($this->selectedDocenteId) : null;

        return view('livewire.dashboard.attendance-table.attendance-table', [
            'attendanceRecords' => $attendanceData,
            'docentes' => $docentes,
            'grupos' => $grupos,
            'materias' => $materias,
            'docenteDetails' => $docenteDetails,
            'selectedDocente' => $selectedDocente,
        ]);
    }

    private function getAttendanceData()
    {
        // Subconsulta para obtener datos consolidados por docente
        $subquery = DB::table('users')
            ->join('user_subjects', 'users.id', '=', 'user_subjects.user_id')
            ->join('subjects', 'user_subjects.subject_id', '=', 'subjects.id')
            ->join('assignments', 'user_subjects.id', '=', 'assignments.user_subject_id')
            ->join('groups', 'assignments.group_id', '=', 'groups.id')
            ->leftJoin('attendance_records', function ($join) {
                $join->on('assignments.id', '=', 'attendance_records.assignment_id')
                    ->on('users.id', '=', 'attendance_records.user_id');
            })
            ->select(
                'users.id as docente_id',
                'users.name as docente_name',
                'assignments.id as assignment_id',
                'attendance_records.status'
            );

        // Aplicar filtros en la subconsulta
        if (!empty($this->filterDocente)) {
            $subquery->where('users.id', $this->filterDocente);
        }

        if (!empty($this->filterGrupo)) {
            $subquery->where('groups.id', $this->filterGrupo);
        }

        if (!empty($this->filterMateria)) {
            $subquery->where('subjects.id', $this->filterMateria);
        }

        if (!empty($this->filterFechaInicio) && !empty($this->filterFechaFin)) {
            $subquery->whereBetween('attendance_records.scan_time', [
                Carbon::parse($this->filterFechaInicio)->startOfDay(),
                Carbon::parse($this->filterFechaFin)->endOfDay()
            ]);
        }

        // Consulta principal agrupada solo por docente
        $query = DB::table(DB::raw("({$subquery->toSql()}) as data"))
            ->mergeBindings($subquery)
            ->select(
                'docente_id',
                'docente_name',
                DB::raw('COUNT(DISTINCT assignment_id) as total_sesiones'),
                DB::raw('COUNT(CASE WHEN status IN (\'on_time\', \'late\') THEN 1 END) as asistencias'),
                DB::raw('COUNT(CASE WHEN status = \'late\' THEN 1 END) as retrasos'),
                DB::raw('COUNT(CASE WHEN status = \'absent\' THEN 1 END) as inasistencias'),
                DB::raw('ROUND((COUNT(CASE WHEN status IN (\'on_time\', \'late\') THEN 1 END)::numeric / NULLIF(COUNT(DISTINCT assignment_id), 0)) * 100, 1) as porcentaje_asistencia')
            )
            ->groupBy('docente_id', 'docente_name');

        // Aplicar filtro de búsqueda
        if (!empty($this->search)) {
            $query->where('docente_name', 'ILIKE', '%' . $this->search . '%');
        }

        return $query->orderBy('porcentaje_asistencia', 'DESC')->paginate($this->perPage);
    }

    public function getDocenteDetails()
    {
        if (!$this->selectedDocenteId) {
            return collect();
        }

        return DB::table('users')
            ->join('user_subjects', 'users.id', '=', 'user_subjects.user_id')
            ->join('subjects', 'user_subjects.subject_id', '=', 'subjects.id')
            ->join('assignments', 'user_subjects.id', '=', 'assignments.user_subject_id')
            ->join('groups', 'assignments.group_id', '=', 'groups.id')
            ->leftJoin('attendance_records', function ($join) {
                $join->on('assignments.id', '=', 'attendance_records.assignment_id')
                    ->on('users.id', '=', 'attendance_records.user_id');
            })
            ->select(
                'subjects.name as materia_name',
                'subjects.code as materia_code',
                'groups.name as grupo_name',
                DB::raw('COUNT(DISTINCT assignments.id) as total_sesiones'),
                DB::raw('COUNT(CASE WHEN attendance_records.status IN (\'on_time\', \'late\') THEN 1 END) as asistencias'),
                DB::raw('COUNT(CASE WHEN attendance_records.status = \'late\' THEN 1 END) as retrasos'),
                DB::raw('COUNT(CASE WHEN attendance_records.status = \'absent\' THEN 1 END) as inasistencias'),
                DB::raw('ROUND((COUNT(CASE WHEN attendance_records.status IN (\'on_time\', \'late\') THEN 1 END)::numeric / NULLIF(COUNT(DISTINCT assignments.id), 0)) * 100, 1) as porcentaje_asistencia')
            )
            ->where('users.id', $this->selectedDocenteId)
            ->groupBy('subjects.id', 'subjects.name', 'subjects.code', 'groups.id', 'groups.name')
            ->orderBy('subjects.name')
            ->get();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterDocente', 'filterGrupo', 'filterMateria', 'filterFechaInicio', 'filterFechaFin']);
        $this->resetPage();
    }

    public function showDetails($docenteId)
    {
        $this->selectedDocenteId = $docenteId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedDocenteId = null;
    }
}
