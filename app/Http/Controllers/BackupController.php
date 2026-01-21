<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('local');
        $files = $disk->files('backups');
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $backups[] = [
                    'filename' => basename($file),
                    'path' => $file,
                    'size' => $this->formatSize($disk->size($file)),
                    'date' => Carbon::createFromTimestamp($disk->lastModified($file))->format('Y-m-d H:i:s'),
                    'timestamp' => $disk->lastModified($file)
                ];
            }
        }

        // Ordenar por fecha descendente
        usort($backups, function ($a, $b) {
            return $b['timestamp'] <=> $a['timestamp'];
        });

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            
            // Usar el disco local para asegurar consistencia con la configuración de filesystems
            // Esto devuelve la ruta absoluta correcta (ej. storage/app/private/backups/archivo.sql)
            $path = Storage::disk('local')->path('backups/' . $filename);
            
            // Asegurar que el directorio existe dentro del disco
            if (!Storage::disk('local')->exists('backups')) {
                Storage::disk('local')->makeDirectory('backups');
            }

            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Comando mysqldump
            // Nota: Se asume que mysqldump está en el PATH o accesible.
            // Es importante no poner la contraseña pegada al flag -p sin espacio si se usa esa sintaxis, 
            // pero en consola la sintaxis es -pPASSWORD (sin espacio).
            // Una forma segura es usar archivo de configuración temporal o variables de entorno, 
            // pero para simplicidad y dado el entorno, usaremos la cadena de comando con cuidado.
            
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($path)
            );

            // Ejecutar comando
            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                return redirect()->route('backups.index')->with('success', 'Respaldo generado exitosamente: ' . $filename);
            } else {
                return redirect()->route('backups.index')->with('error', 'Error al generar el respaldo. Código de salida: ' . $resultCode);
            }

        } catch (\Exception $e) {
            return redirect()->route('backups.index')->with('error', 'Excepción al generar respaldo: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        if (Storage::disk('local')->exists('backups/' . $filename)) {
            return Storage::disk('local')->download('backups/' . $filename);
        }
        return redirect()->route('backups.index')->with('error', 'El archivo de respaldo no existe.');
    }

    public function delete($filename)
    {
        if (Storage::disk('local')->exists('backups/' . $filename)) {
            Storage::disk('local')->delete('backups/' . $filename);
            return redirect()->route('backups.index')->with('success', 'Respaldo eliminado correctamente.');
        }
        return redirect()->route('backups.index')->with('error', 'El archivo no existe.');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string'
        ]);

        $filename = $request->backup_file;
        
        if (!Storage::disk('local')->exists('backups/' . $filename)) {
            return redirect()->route('backups.index')->with('error', 'El archivo de respaldo seleccionado no existe.');
        }

        try {
            // Usar el disco local para obtener la ruta absoluta correcta del archivo
            $path = Storage::disk('local')->path('backups/' . $filename);
            
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Comando mysql para restaurar
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s --port=%s %s < %s',
                escapeshellarg($dbUser),
                escapeshellarg($dbPassword),
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbName),
                escapeshellarg($path)
            );

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                return redirect()->route('backups.index')->with('success', 'Base de datos restaurada exitosamente desde: ' . $filename);
            } else {
                return redirect()->route('backups.index')->with('error', 'Error al restaurar la base de datos. Código de salida: ' . $resultCode);
            }

        } catch (\Exception $e) {
            return redirect()->route('backups.index')->with('error', 'Excepción al restaurar: ' . $e->getMessage());
        }
    }

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
