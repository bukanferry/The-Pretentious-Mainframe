<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The System Monitor</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased selection:bg-ink selection:text-paper">

<div class="min-h-screen p-4 md:p-8 max-w-5xl mx-auto flex flex-col space-y-6">

    <!-- Header Section -->
    <header class="text-center">
        <h1 class="font-chomsky text-4xl sm:text-6xl md:text-8xl tracking-wide mb-4">Hexadella.Space/<wbr>Monitor</h1>
        
        <p class="font-serif text-xs sm:text-sm leading-relaxed max-w-4xl mx-auto text-justify mb-8 px-2">
            Courage is not the absence of fear, but rather the assessment that something else is more important than fear. In the grand tapestry of digital existence, every byte and bit flows like a river toward the endless ocean of data. We stand at the precipice of innovation, watching the system hum with silent vitality. As long as the servers breathe, the legacy continues. Let this monitor be a testament to our enduring presence in the cyberspace.
        </p>

        <h2 class="font-chomsky text-3xl sm:text-4xl md:text-5xl tracking-wide border-b-[3px] border-ink pb-2">THE SYSTEM MONITOR</h2>
        
        <div class="border-b-[1px] border-ink py-1 text-xs font-bold tracking-widest flex justify-between px-2">
            <span>DAILY REPORT</span>
            <span>&bull;</span>
            <span id="current-date">{{ date('F d, Y') }}</span>
            <span>&bull;</span>
            <span>NEW YORK, NY</span>
        </div>
        <div class="border-b-[1px] border-ink py-1 text-xs font-bold tracking-widest text-center">
            INDEPENDENT. RELIABLE. DATA.
        </div>
    </header>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- CPU Section -->
        <div class="md:col-span-2 ascii-box min-h-[150px] flex flex-col mt-3 md:mt-0">
            <span class="ascii-title tracking-widest whitespace-nowrap">cpu &bull; PERFORMANCE HISTORY</span>
            
            <div class="flex-grow flex items-center justify-center relative mt-2 overflow-hidden h-24">
                <canvas id="cpuChart" class="absolute inset-0 w-full h-full"></canvas>
            </div>
            
            <div class="mt-4 text-xs font-bold" id="uptime">
                up 0d 00:00
            </div>
        </div>

        <!-- Cores Section -->
        <div class="ascii-box flex flex-col mt-3 md:mt-0">
            <span class="ascii-title tracking-widest" id="cpu-speed">Common processor &bull; 0.0 GHz</span>
            <div class="mt-4 grid grid-cols-2 gap-x-2 gap-y-1 text-xs flex-grow" id="cpu-cores">
                <!-- Cores will be injected here -->
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Mem Section -->
        <div class="ascii-box text-xs mt-3 md:mt-0">
            <span class="ascii-title tracking-widest whitespace-nowrap">mem</span>
            <div class="mt-2 space-y-1 overflow-x-auto">
                <div class="flex justify-between"><span>Total:</span> <span id="mem-total">0 GiB</span></div>
                <div class="flex justify-between">
                    <span>Used:</span>
                    <span class="flex items-center space-x-2">
                        <span id="mem-used-bar" class="tracking-tighter">░░░░░░░░░░</span>
                        <span id="mem-used-perc" class="w-8 text-right">0%</span>
                    </span>
                </div>
                <div class="flex justify-between"><span>Avai:</span> <span id="mem-avai">0 GiB</span></div>
                <div class="flex justify-between">
                    <span class="text-transparent">____</span>
                    <span class="flex items-center space-x-2">
                        <span id="mem-avai-bar" class="tracking-tighter">░░░░░░░░░░</span>
                        <span id="mem-avai-perc" class="w-8 text-right">0%</span>
                    </span>
                </div>
                <div class="flex justify-between"><span>Cach:</span> <span id="mem-cach">0 MiB</span></div>
                <div class="flex justify-between"><span>Free:</span> <span id="mem-free">0 MiB</span></div>
            </div>
        </div>

        <!-- Disks Section -->
        <div class="ascii-box text-xs mt-3 md:mt-0">
            <span class="ascii-title tracking-widest whitespace-nowrap">disks</span>
            <div class="mt-2 space-y-2 overflow-x-auto" id="disks-container">
                <!-- Disks injected here -->
            </div>
        </div>

        <!-- Network Section -->
        <div class="ascii-box flex flex-col text-xs mt-3 md:mt-0">
            <span class="ascii-title tracking-widest whitespace-nowrap">net &bull; COMMUNICATIONS</span>
            <div class="mt-2 flex-grow flex flex-col justify-center space-y-2 font-bold overflow-x-auto" id="net-container">
                <div>
                    <div class="underline">download</div>
                    <div id="net-rx-sec">▼ 0 B/s</div>
                    <div id="net-rx-total">▼ Total: 0 B</div>
                </div>
                <div>
                    <div class="underline">upload</div>
                    <div id="net-tx-sec">▲ 0 B/s</div>
                    <div id="net-tx-total">▲ Total: 0 B</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processes Section -->
    <div class="ascii-box text-xs mt-3 md:mt-0">
        <span class="ascii-title tracking-widest whitespace-nowrap">proc &bull; filter &bull; tree &lt; cpu lazy &gt;</span>
        <div class="mt-2 w-full overflow-x-auto">
            <table class="w-full text-left font-monospace">
                <thead>
                    <tr class="border-b border-ink">
                        <th class="py-1">Pid:</th>
                        <th class="py-1">Program:</th>
                        <th class="py-1">User:</th>
                        <th class="py-1 text-right">MemB</th>
                        <th class="py-1 text-right">Cpu% &uarr;</th>
                    </tr>
                </thead>
                <tbody id="proc-table">
                    <!-- Processes injected here -->
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="mt-16 mb-12 border-t-4 border-double border-ink pt-6 pb-2 text-center relative">
        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-paper px-4">
            <span class="font-chomsky text-2xl">fin.</span>
        </div>
        <p class="font-serif text-sm leading-relaxed max-w-2xl mx-auto italic opacity-90">
            "The data flows, the circuits hum. Even in the silence of the night, the heartbeat of the machine echoes through the vast network. Let the monitor watch, for it never sleeps."
        </p>
        <div class="mt-6 font-monospace text-xs tracking-widest uppercase border-y border-ink inline-block py-1 px-8">
            Published from the Server Core &bull; EST. 2026
        </div>
    </footer>

</div>

<script>
    const canvas = document.getElementById('cpuChart');
    const ctx = canvas.getContext('2d');
    let cpuData = Array(50).fill(0);

    function resizeCanvas() {
        canvas.width = canvas.parentElement.clientWidth;
        canvas.height = canvas.parentElement.clientHeight;
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    function drawChart() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.beginPath();
        ctx.strokeStyle = '#1A1A1A';
        ctx.lineWidth = 1.5;
        
        const step = canvas.width / (cpuData.length - 1);
        cpuData.forEach((val, i) => {
            const x = i * step;
            // invert Y (100% is top)
            const y = canvas.height - (val / 100 * canvas.height);
            
            // Draw ECG style (add a bit of random noise for realism like btop)
            const noise = (Math.random() - 0.5) * 4;
            const finalY = Math.max(0, Math.min(canvas.height, y + noise));

            if (i === 0) ctx.moveTo(x, finalY);
            else ctx.lineTo(x, finalY);
        });
        ctx.stroke();
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'K', 'M', 'G', 'T'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + sizes[i];
    }

    function getBar(percent) {
        const total = 10;
        const filled = Math.round((percent / 100) * total);
        return '▓'.repeat(filled) + '░'.repeat(total - filled);
    }

    function formatUptime(sec) {
        const d = Math.floor(sec / (3600 * 24));
        const h = Math.floor((sec % (3600 * 24)) / 3600);
        const m = Math.floor((sec % 3600) / 60);
        return `${d}d ${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
    }

    async function fetchStats() {
        try {
            const res = await fetch('/api/stats');
            const data = await res.json();
            
            // CPU Chart
            cpuData.push(data.cpu.avgLoad);
            if (cpuData.length > 50) cpuData.shift();
            drawChart();

            // Uptime
            document.getElementById('uptime').innerText = `up ${formatUptime(data.os.uptime)}`;
            document.getElementById('cpu-speed').innerHTML = `Common processor &bull; ${data.cpu.speed} GHz`;

            // Cores
            document.getElementById('cpu-cores').innerHTML = data.cpu.cores.map((load, i) => 
                `<div class="flex justify-between items-center space-x-1">
                    <span>C${i}</span>
                    <span class="tracking-tighter">${getBar(load)}</span>
                    <span class="w-6 text-right">${Math.round(load)}%</span>
                </div>`
            ).join('');

            // Memory
            document.getElementById('mem-total').innerText = formatBytes(data.mem.total);
            const memUsedPerc = (data.mem.used / data.mem.total) * 100 || 0;
            const memAvaiPerc = (data.mem.free / data.mem.total) * 100 || 0;
            
            document.getElementById('mem-used-bar').innerText = getBar(memUsedPerc);
            document.getElementById('mem-used-perc').innerText = `${Math.round(memUsedPerc)}%`;
            document.getElementById('mem-avai').innerText = formatBytes(data.mem.free + data.mem.cached);
            document.getElementById('mem-avai-bar').innerText = getBar(memAvaiPerc);
            document.getElementById('mem-avai-perc').innerText = `${Math.round(memAvaiPerc)}%`;
            document.getElementById('mem-cach').innerText = formatBytes(data.mem.cached);
            document.getElementById('mem-free').innerText = formatBytes(data.mem.free);

            // Disks
            document.getElementById('disks-container').innerHTML = data.disks.map(d => `
                <div>
                    <div class="flex justify-between font-bold"><span>${d.fs}</span><span>${formatBytes(d.size)}</span></div>
                    <div class="flex justify-between items-center mt-1 space-x-2">
                        <span>IO</span>
                        <span class="tracking-tighter">${getBar(d.use)}</span>
                        <span class="w-8 text-right">${Math.round(d.use)}%</span>
                    </div>
                </div>
            `).join('');

            // Network
            if(data.net && data.net[0]) {
                document.getElementById('net-rx-sec').innerText = `▼ ${formatBytes(data.net[0].rx_sec)}/s`;
                document.getElementById('net-rx-total').innerText = `▼ Total: ${formatBytes(data.net[0].rx_bytes)}`;
                document.getElementById('net-tx-sec').innerText = `▲ ${formatBytes(data.net[0].tx_sec)}/s`;
                document.getElementById('net-tx-total').innerText = `▲ Total: ${formatBytes(data.net[0].tx_bytes)}`;
            }

            // Processes
            document.getElementById('proc-table').innerHTML = data.procs.map(p => `
                <tr class="hover:bg-ink hover:text-paper cursor-pointer transition-colors">
                    <td class="py-1">${p.pid}</td>
                    <td class="py-1 font-bold">${p.name}</td>
                    <td class="py-1">${p.user}</td>
                    <td class="py-1 text-right">${formatBytes(p.memB)}</td>
                    <td class="py-1 text-right">${p.cpu.toFixed(1)}</td>
                </tr>
            `).join('');

        } catch (e) {
            console.error(e);
        }
    }

    // Start polling
    fetchStats();
    setInterval(fetchStats, 2000);
</script>
</body>
</html>
