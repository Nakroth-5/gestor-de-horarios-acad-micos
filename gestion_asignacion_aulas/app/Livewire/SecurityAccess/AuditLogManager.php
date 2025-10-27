<?php

namespace App\Livewire\SecurityAccess;

use AllowDynamicProperties;
use App\Models\AuditLog;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[AllowDynamicProperties]
class AuditLogManager extends Component
{
    public $search = '';
    use WithPagination;
    protected $pagination_theme = 'tailwind';

    public function render(): View
    {
        $auditLogs = AuditLog::query()
            ->join('users', 'audit_logs.user_id', '=', 'users.id');

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $auditLogs->where(function ($q) use ($searchTerm) {
                $q->where('audit_logs.action', 'ILIKE', $searchTerm)
                    ->orWhere('audit_logs.affected_model', 'ILIKE', $searchTerm)
                    ->orWhere('audit_logs.changes', 'ILIKE', $searchTerm)
                    ->orWhere('audit_logs.ip_address', 'ILIKE', $searchTerm)

                    ->orWhere('users.id', 'ILIKE', $searchTerm)
                    ->orWhere('users.name', 'ILIKE', $searchTerm)
                ;
            });
        }

        $auditLogs->select('audit_logs.*');
        $auditLogs = $auditLogs->orderBy('audit_logs.id')->paginate(10);
        return view('livewire.security-access.audit.audit-log-manager', compact('auditLogs'));
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }
}
