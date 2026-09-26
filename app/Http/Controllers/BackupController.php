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

            // Ruta completa a mysqldump en WAMP
            $mysqldumpPath = env('MYSQLDUMP_PATH', 'C:\\wamp64\\bin\\mysql\\mysql9.1.0\\bin\\mysqldump.exe');
            
            // Construir comando - en Windows no usar escapeshellarg para evitar problemas
            if (empty($dbPassword)) {
                $command = "\"{$mysqldumpPath}\" --user={$dbUser} --host={$dbHost} --port={$dbPort} {$dbName} > \"{$path}\" 2>&1";
            } else {
                $command = "\"{$mysqldumpPath}\" --user={$dbUser} --password={$dbPassword} --host={$dbHost} --port={$dbPort} {$dbName} > \"{$path}\" 2>&1";
            }

            // Ejecutar comando usando shell_exec para Windows
            $result = shell_exec($command);
            
            // Verificar si el archivo se creó y tiene contenido
            if (file_exists($path) && filesize($path) > 0) {
                return redirect()->route('backups.index')->with('success', 'Respaldo generado exitosamente: ' . $filename);
            } else {
                // Limpiar archivo vacío si existe
                if (file_exists($path)) {
                    unlink($path);
                }
                $errorMsg = $result ? $result : 'No se pudo generar el respaldo';
                return redirect()->route('backups.index')->with('error', 'Error al generar el respaldo: ' . $errorMsg);
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

        // Verificación 1: El archivo existe en el servidor
        if (!Storage::disk('local')->exists('backups/' . $filename)) {
            return redirect()->route('backups.index')
                ->with('error', '❌ Error de restauración: El archivo "' . $filename . '" no se encontró en el servidor. Es posible que haya sido eliminado previamente.');
        }

        $path = Storage::disk('local')->path('backups/' . $filename);

        // Verificación 2: El archivo no está vacío
        if (filesize($path) === 0) {
            return redirect()->route('backups.index')
                ->with('error', '❌ Error de restauración: El archivo "' . $filename . '" está vacío. El respaldo puede estar corrupto o haberse interrumpido durante su generación.');
        }

        // Verificación 3: El archivo parece ser un respaldo SQL válido
        $handle = fopen($path, 'r');
        $primeraLinea = fgets($handle);
        fclose($handle);

        $esSqlValido = str_contains($primeraLinea, '--') ||
                       str_contains(strtoupper($primeraLinea), 'CREATE') ||
                       str_contains(strtoupper($primeraLinea), 'SET ') ||
                       str_contains($primeraLinea, '/*!');

        if (!$esSqlValido) {
            return redirect()->route('backups.index')
                ->with('error', '❌ Error de restauración: El archivo "' . $filename . '" no es un respaldo SQL válido. Verifique que el archivo fue generado por este sistema y que no está dañado o es de un formato diferente.');
        }

        try {
            $dbHost = config('database.connections.mysql.host');
            $dbPort = config('database.connections.mysql.port');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            // Ruta completa a mysql en WAMP
            $mysqlPath = env('MYSQL_PATH', 'C:\\wamp64\\bin\\mysql\\mysql9.1.0\\bin\\mysql.exe');

            // Usar --execute con source para evitar problemas con redirección en Windows
            $pathForwardSlash = str_replace('\\', '/', $path);

            if (empty($dbPassword)) {
                $command = "\"{$mysqlPath}\" --user={$dbUser} --host={$dbHost} --port={$dbPort} {$dbName} -e \"source {$pathForwardSlash}\" 2>&1";
            } else {
                $command = "\"{$mysqlPath}\" --user={$dbUser} --password={$dbPassword} --host={$dbHost} --port={$dbPort} {$dbName} -e \"source {$pathForwardSlash}\" 2>&1";
            }

            $result = shell_exec($command);

            // Verificar resultado con mensajes descriptivos por tipo de error
            if ($result === null || $result === '' || stripos($result, 'ERROR') === false) {
                return redirect()->route('backups.index', ['restored' => 1, 'file' => $filename]);
            } else {
                // Detectar tipo de error específico para mensaje más claro
                if (stripos($result, 'Access denied') !== false) {
                    $mensaje = 'El usuario de base de datos no tiene los permisos suficientes para ejecutar la restauración.';
                } elseif (stripos($result, 'syntax error') !== false || stripos($result, 'You have an error in your SQL syntax') !== false) {
                    $mensaje = 'El archivo contiene errores de sintaxis SQL. El respaldo puede estar incompleto o corrupto.';
                } elseif (stripos($result, 'Unknown database') !== false) {
                    $mensaje = 'La base de datos de destino no existe en el servidor MySQL.';
                } elseif (stripos($result, 'Can\'t connect') !== false || stripos($result, 'Connection refused') !== false) {
                    $mensaje = 'No se pudo conectar con el servidor de base de datos durante la restauración.';
                } else {
                    $mensaje = trim($result);
                }
                return redirect()->route('backups.index')
                    ->with('error', '❌ Error al restaurar la base de datos: ' . $mensaje);
            }

        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', '❌ Excepción al restaurar: ' . $e->getMessage());
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
