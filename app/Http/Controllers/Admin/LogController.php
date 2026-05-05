<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logPath = storage_path('logs');
        if (!File::isDirectory($logPath)) {
            File::makeDirectory($logPath, 0755, true);
        }
        
        $files = File::glob($logPath . '/*.log');
        $files = $files ?: [];
        $files = array_reverse($files);

        $selectedFile = $request->get('file');
        $currentFile = null;
        $logs = [];

        if ($selectedFile && File::exists($logPath . '/' . $selectedFile)) {
            $currentFile = $selectedFile;
            $rawContent = $this->readLastLines($logPath . '/' . $selectedFile, 500);
            $logs = $this->parseLogs($rawContent);
        } elseif (count($files) > 0) {
            $currentFile = basename($files[0]);
            $rawContent = $this->readLastLines($files[0], 500);
            $logs = $this->parseLogs($rawContent);
        }

        $fileList = array_map(function($file) {
            return basename($file);
        }, $files);

        return view('admin.Settings.logs.index', [
            'title' => 'System Logs',
            'files' => $fileList,
            'currentFile' => $currentFile,
            'logs' => $logs,
        ]);
    }

    private function parseLogs($content)
    {
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*?)(\{.*\}|\[.*\])?(?=\n\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]|\z)/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $parsed = [];
        foreach ($matches as $match) {
            $level = strtoupper($match[3]);
            $parsed[] = [
                'timestamp' => $match[1],
                'env' => $match[2],
                'level' => $level,
                'level_class' => $this->getLevelClass($level),
                'message' => $match[4],
                'context' => $match[5] ?? ''
            ];
        }

        return array_reverse($parsed); // Newest first
    }

    private function getLevelClass($level)
    {
        return match ($level) {
            'ERROR', 'CRITICAL', 'ALERT', 'EMERGENCY' => 'red',
            'WARNING' => 'amber',
            'INFO', 'NOTICE' => 'blue',
            'DEBUG' => 'gray',
            default => 'gray',
        };
    }

    public function download($file)
    {
        $path = storage_path('logs/' . $file);
        if (File::exists($path)) {
            return Response::download($path);
        }
        return redirect()->back()->with('error', 'File not found.');
    }

    public function clear($file)
    {
        $path = storage_path('logs/' . $file);
        if (File::exists($path)) {
            File::put($path, '');
            return redirect()->back()->with('success', 'Log file cleared.');
        }
        return redirect()->back()->with('error', 'File not found.');
    }

    public function delete($file)
    {
        $path = storage_path('logs/' . $file);
        if (File::exists($path) && $file !== 'laravel.log') {
            File::delete($path);
            return redirect()->route('admin.logs.index')->with('success', 'Log file deleted.');
        }
        return redirect()->back()->with('error', 'Cannot delete main log file.');
    }

    private function readLastLines($filePath, $lines = 500)
    {
        if (!File::exists($filePath)) return '';
        
        $file = fopen($filePath, "r");
        if (!$file) return '';

        $lineCount = 0;
        $pos = -2;
        $beginning = false;
        $text = [];

        while ($lineCount < $lines) {
            $t = "";
            while ($t != "\n") {
                if (fseek($file, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($file);
                $pos--;
            }
            $lineCount++;
            if ($beginning) rewind($file);
            $text[] = fgets($file);
            if ($beginning) break;
        }
        fclose($file);

        return implode('', array_reverse($text));
    }
}
