<?php

namespace App\Livewire\SecurityAccess;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserImport extends Component
{
    use WithFileUploads;

    public $file;
    public $importResults = [];
    public $showResults = false;
    public $successCount = 0;
    public $errorCount = 0;
    public $importErrors = [];

    protected $rules = [
        'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048', // 2MB max
    ];

    protected $messages = [
        'file.required' => 'Debe seleccionar un archivo para importar.',
        'file.mimes' => 'El archivo debe ser CSV, TXT, XLSX o XLS.',
        'file.max' => 'El archivo no debe superar los 2MB.',
    ];

    public function render()
    {
        return view('livewire.security-access.user-import');
    }

    public function import()
    {
        $this->validate();

        try {
            $this->reset(['importResults', 'showResults', 'successCount', 'errorCount', 'importErrors']);

            // Leer el archivo
            $path = $this->file->getRealPath();
            $extension = $this->file->getClientOriginalExtension();

            if (in_array($extension, ['csv', 'txt'])) {
                $data = $this->parseCsv($path);
            } else {
                // Para XLSX/XLS necesitarías una librería como PhpSpreadsheet
                $this->addError('file', 'Por favor use formato CSV para esta versión.');
                return;
            }

            // Procesar cada fila
            $this->processImport($data);

            $this->showResults = true;
            
            if ($this->successCount > 0) {
                session()->flash('success', "✅ Importación completada: {$this->successCount} usuarios creados exitosamente.");
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }

    private function parseCsv($path)
    {
        $data = [];
        $handle = fopen($path, 'r');
        
        if ($handle === false) {
            throw new \Exception('No se pudo abrir el archivo.');
        }

        // Detectar y omitir BOM UTF-8 si existe
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle); // Volver al inicio si no hay BOM
        }

        $row = 0;
        while (($line = fgetcsv($handle, 1000, ',')) !== false) {
            $row++;
            
            // Omitir primera fila si parece ser encabezado
            if ($row === 1 && $this->isHeaderRow($line)) {
                continue;
            }

            // Validar que tenga al menos 5 columnas
            if (count($line) < 5) {
                $this->importErrors[] = "Fila {$row}: Formato incorrecto (se esperan 5 columnas).";
                $this->errorCount++;
                continue;
            }

            $data[] = [
                'name' => trim($line[0]),
                'last_name' => trim($line[1]),
                'phone' => trim($line[2]),
                'email' => trim($line[3]),
                'document_number' => trim($line[4]),
                'row' => $row,
            ];
        }

        fclose($handle);
        return $data;
    }

    private function isHeaderRow($line)
    {
        $firstColumn = strtolower(trim($line[0]));
        return in_array($firstColumn, ['name', 'nombre', 'names', 'name']);
    }

    private function processImport($data)
    {
        // Obtener el rol de Docente
        $docenteRole = Role::where('name', 'Docente')->first();

        if (!$docenteRole) {
            throw new \Exception('No se encontró el rol "Docente" en el sistema. Por favor créelo primero.');
        }

        DB::beginTransaction();

        try {
            foreach ($data as $userData) {
                try {
                    // Validar datos
                    $validation = $this->validateUserData($userData);
                    
                    if (!$validation['valid']) {
                        $this->importErrors[] = "Fila {$userData['row']}: {$validation['message']}";
                        $this->errorCount++;
                        continue;
                    }

                    // Verificar si el usuario ya existe
                    $existingUser = User::where('email', $userData['email'])
                        ->orWhere('document_number', $userData['document_number'])
                        ->first();

                    if ($existingUser) {
                        $this->importErrors[] = "Fila {$userData['row']}: Usuario ya existe (email: {$userData['email']} o CI: {$userData['document_number']}).";
                        $this->errorCount++;
                        continue;
                    }

                    // Crear usuario
                    $user = User::create([
                        'name' => $userData['name'],
                        'last_name' => $userData['last_name'],
                        'phone' => $userData['phone'],
                        'email' => $userData['email'],
                        'document_type' => 'CI', // Por defecto CI
                        'document_number' => $userData['document_number'],
                        'password' => Hash::make($userData['document_number']), // Contraseña = CI por defecto
                        'is_active' => true,
                    ]);

                    // Asignar rol de Docente
                    $user->roles()->attach($docenteRole->id);

                    $this->importResults[] = [
                        'success' => true,
                        'row' => $userData['row'],
                        'name' => $userData['name'] . ' ' . $userData['last_name'],
                        'email' => $userData['email'],
                    ];

                    $this->successCount++;

                } catch (\Exception $e) {
                    $this->importErrors[] = "Fila {$userData['row']}: Error al crear usuario - {$e->getMessage()}";
                    $this->errorCount++;
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function validateUserData($data)
    {
        // Validar nombre
        if (empty($data['name'])) {
            return ['valid' => false, 'message' => 'El nombre es requerido.'];
        }

        // Validar apellido
        if (empty($data['last_name'])) {
            return ['valid' => false, 'message' => 'El apellido es requerido.'];
        }

        // Validar email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Email inválido o vacío.'];
        }

        // Validar número de documento
        if (empty($data['document_number'])) {
            return ['valid' => false, 'message' => 'El número de documento es requerido.'];
        }

        // Validar que el documento sea numérico
        if (!is_numeric($data['document_number'])) {
            return ['valid' => false, 'message' => 'El número de documento debe ser numérico.'];
        }

        return ['valid' => true];
    }

    public function resetImport()
    {
        $this->reset(['file', 'importResults', 'showResults', 'successCount', 'errorCount', 'importErrors']);
        session()->forget(['success', 'error']);
    }
}
