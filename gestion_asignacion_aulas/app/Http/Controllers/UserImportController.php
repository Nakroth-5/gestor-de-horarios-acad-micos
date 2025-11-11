<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserImportController extends Controller
{
    /**
     * Descargar plantilla CSV para importación de usuarios
     */
    public function downloadTemplate()
    {
        $filename = 'plantilla_importacion_usuarios.csv';
        
        return response()->streamDownload(function () {
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8 en Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($output, ['name', 'last_name', 'phone', 'email', 'document_number']);
            
            // Ejemplos
            fputcsv($output, ['Juan', 'Pérez García', '77123456', 'juan.perez@example.com', '1234567']);
            fputcsv($output, ['María', 'López Mendoza', '71234567', 'maria.lopez@example.com', '2345678']);
            fputcsv($output, ['Carlos', 'Gómez Silva', '72345678', 'carlos.gomez@example.com', '3456789']);
            
            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
