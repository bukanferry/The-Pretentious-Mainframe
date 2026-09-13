<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index()
    {
        return view('monitor');
    }

    public function stats()
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

        if ($isWindows) {
            // Mock Data for Windows Development
            return response()->json([
                'cpu' => [
                    'avgLoad' => rand(10, 80),
                    'cores' => [rand(5,99), rand(5,99), rand(5,99), rand(5,99), rand(5,99), rand(5,99), rand(5,99), rand(5,99)],
                    'speed' => '3.6'
                ],
                'mem' => [
                    'total' => 16 * 1024 * 1024 * 1024,
                    'used' => rand(4, 12) * 1024 * 1024 * 1024,
                    'cached' => 1 * 1024 * 1024 * 1024,
                    'free' => rand(2, 4) * 1024 * 1024 * 1024,
                ],
                'disks' => [
                    ['fs' => 'C:', 'size' => 500 * 1024**3, 'used' => 250 * 1024**3, 'use' => 50],
                    ['fs' => 'D:', 'size' => 1000 * 1024**3, 'used' => 800 * 1024**3, 'use' => 80],
                ],
                'net' => [
                    ['rx_sec' => rand(100, 5000) * 1024, 'tx_sec' => rand(50, 1000) * 1024, 'rx_bytes' => 50 * 1024**3, 'tx_bytes' => 10 * 1024**3]
                ],
                'procs' => [
                    ['pid' => 1024, 'name' => 'chrome.exe', 'user' => 'admin', 'memB' => 500 * 1024 * 1024, 'cpu' => 5.5],
                    ['pid' => 2048, 'name' => 'php.exe', 'user' => 'admin', 'memB' => 50 * 1024 * 1024, 'cpu' => 2.1],
                    ['pid' => 3096, 'name' => 'mysql.exe', 'user' => 'system', 'memB' => 200 * 1024 * 1024, 'cpu' => 0.5],
                ],
                'os' => [
                    'uptime' => rand(100000, 200000)
                ]
            ]);
        }

        // Real Data for Linux VPS
        $load = sys_getloadavg();
        $cpuAvg = ($load[0] ?? 0) * 100 / max((int) shell_exec('nproc'), 1);
        
        $meminfo = @file_get_contents('/proc/meminfo');
        $memTotal = 0; $memFree = 0; $memCached = 0;
        if ($meminfo) {
            preg_match('/MemTotal:\s+(\d+)/', $meminfo, $mt);
            preg_match('/MemFree:\s+(\d+)/', $meminfo, $mf);
            preg_match('/Cached:\s+(\d+)/', $meminfo, $mc);
            $memTotal = ($mt[1] ?? 0) * 1024;
            $memFree = ($mf[1] ?? 0) * 1024;
            $memCached = ($mc[1] ?? 0) * 1024;
        }

        $dt = disk_total_space('/');
        $df = disk_free_space('/');
        $du = $dt - $df;
        $dPerc = $dt > 0 ? ($du / $dt) * 100 : 0;

        $uptimeInfo = @file_get_contents('/proc/uptime');
        $uptime = $uptimeInfo ? (float) explode(' ', $uptimeInfo)[0] : 0;

        return response()->json([
            'cpu' => [
                'avgLoad' => $cpuAvg,
                'cores' => array_fill(0, max((int) shell_exec('nproc'), 1), $cpuAvg),
                'speed' => '2.4'
            ],
            'mem' => [
                'total' => $memTotal,
                'used' => $memTotal - $memFree,
                'cached' => $memCached,
                'free' => $memFree,
            ],
            'disks' => [
                ['fs' => '/', 'size' => $dt, 'used' => $du, 'use' => $dPerc]
            ],
            'net' => [
                ['rx_sec' => 0, 'tx_sec' => 0, 'rx_bytes' => 0, 'tx_bytes' => 0] // Simplified for real
            ],
            'procs' => [
                ['pid' => 1, 'name' => 'systemd', 'user' => 'root', 'memB' => 10 * 1024 * 1024, 'cpu' => 0.1]
            ],
            'os' => [
                'uptime' => $uptime
            ]
        ]);
    }
}
