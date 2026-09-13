<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal | Hexadella.Space</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased selection:bg-ink selection:text-paper min-h-screen bg-paper text-ink p-4 md:p-8 flex flex-col items-center">

    <header class="text-center w-full max-w-5xl mx-auto mb-8 animate-fade-in">
        <h1 class="font-chomsky text-4xl sm:text-6xl md:text-8xl tracking-wide mb-4">Hexadella.Space/<wbr>Terminal</h1>
        <p class="font-serif text-xs sm:text-sm leading-relaxed max-w-4xl mx-auto text-justify mb-8 px-2">
            Welcome to the deeper layers of the grid. This secure terminal grants access to the underlying architecture of Hexadella Space. Tread carefully; every keystroke echoes through the mainframe, and the watchers are always listening. Disconnect immediately if you are unauthorized.
        </p>
    </header>

    <div class="w-full max-w-4xl mx-auto font-monospace text-sm md:text-base flex-grow flex flex-col">
        <div class="opacity-70 mb-2">
            Hexadella OS v1.0.0 (tty1)<br>
            Type 'help' to see available commands.<br>
            Warning: All unauthorized access attempts are logged.
        </div>
        
        <div id="terminal" class="border-[2px] border-ink p-4 min-h-[400px] flex-grow flex flex-col bg-paper relative">
            <div id="history" class="space-y-1 whitespace-pre-wrap"></div>
            
            <div class="flex items-center mt-2 relative">
                <span class="mr-2 font-bold whitespace-nowrap">guest@hexadella:~$</span>
                <div class="relative flex-grow flex items-center">
                    <input type="text" id="commandInput" class="w-full bg-transparent border-none outline-none text-ink caret-transparent font-monospace" autocomplete="off" spellcheck="false" autofocus>
                    <span id="cursor" class="absolute left-0 top-0 bottom-0 w-[8px] bg-ink animate-pulse" style="height: 1.2em; top: 0.1em;"></span>
                    <span id="mirror" class="invisible whitespace-pre font-monospace absolute"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-16 mb-12 border-t-4 border-double border-ink pt-6 pb-2 text-center w-full max-w-5xl mx-auto relative">
        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-paper px-4">
            <span class="font-chomsky text-2xl">fin.</span>
        </div>
        <p class="font-serif text-sm leading-relaxed max-w-2xl mx-auto italic opacity-90">
            "Commands executed in the shadows echo forever in the logs. The terminal is a window into the soul of the machine."
        </p>
        <div class="mt-6 font-monospace text-xs tracking-widest uppercase border-y border-ink inline-block py-1 px-8">
            Terminal Access Granted &bull; EST. 2026
        </div>
    </footer>

    <script>
        const input = document.getElementById('commandInput');
        const history = document.getElementById('history');
        const terminal = document.getElementById('terminal');
        const cursor = document.getElementById('cursor');
        const mirror = document.getElementById('mirror');

        function updateCursor() {
            mirror.textContent = input.value;
            const width = mirror.getBoundingClientRect().width;
            cursor.style.left = `${width}px`;
        }

        input.addEventListener('input', updateCursor);
        input.addEventListener('keyup', updateCursor);
        input.addEventListener('click', updateCursor);

        const commands = {
            'help': `Available commands:
  help      - Show this message
  whoami    - Print current user
  date      - Print current date and time
  uname     - Print operating system name
  clear     - Clear the terminal screen
  echo      - Print a message
  ping      - Ping a host
  su        - Change user ID or become superuser
  exit      - Close the terminal session`,
            'whoami': 'guest',
            'uname': 'Hexadella OS 1.0.0-generic x86_64',
            'su': 'su: Authentication failure. You are not worthy.',
            'exit': 'Closing session... [Connection reset by peer]'
        };

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const cmdString = this.value.trim();
                const args = cmdString.split(' ').filter(Boolean);
                const cmd = args.length > 0 ? args[0].toLowerCase() : '';
                
                if (cmdString !== '') {
                    // Echo prompt and command
                    appendLine(`<span class="font-bold font-monospace">guest@hexadella:~$</span> ${escapeHTML(cmdString)}`);
                    
                    // Process command
                    processCommand(cmd, args.slice(1));
                }
                
                this.value = '';
                scrollToBottom();
            }
        });

        function processCommand(cmd, args) {
            if (cmd === 'clear') {
                history.innerHTML = '';
                return;
            }

            if (cmd === 'date') {
                appendLine(new Date().toString());
                return;
            }

            if (cmd === 'echo') {
                appendLine(escapeHTML(args.join(' ')));
                return;
            }

            if (cmd === 'ping') {
                if (args.length === 0) {
                    appendLine("ping: usage error: Destination address required");
                } else {
                    appendLine(`PING ${escapeHTML(args[0])} (127.0.0.1) 56(84) bytes of data.`);
                    setTimeout(() => appendLine(`64 bytes from 127.0.0.1: icmp_seq=1 ttl=64 time=0.042 ms`), 500);
                    setTimeout(() => appendLine(`64 bytes from 127.0.0.1: icmp_seq=2 ttl=64 time=0.038 ms`), 1000);
                    setTimeout(() => appendLine(`64 bytes from 127.0.0.1: icmp_seq=3 ttl=64 time=0.045 ms`), 1500);
                    setTimeout(() => appendLine(`\n--- ${escapeHTML(args[0])} ping statistics ---\n3 packets transmitted, 3 received, 0% packet loss, time 2000ms`), 2000);
                }
                return;
            }
            
            if (cmd === 'exit') {
                appendLine(commands['exit']);
                input.disabled = true;
                setTimeout(() => window.location.href = '/', 1500);
                return;
            }

            if (commands.hasOwnProperty(cmd)) {
                appendLine(commands[cmd]);
            } else {
                appendLine(`${cmd}: command not found`);
            }
        }

        function appendLine(html) {
            const div = document.createElement('div');
            div.innerHTML = html;
            history.appendChild(div);
            scrollToBottom();
        }

        function scrollToBottom() {
            window.scrollTo(0, document.body.scrollHeight);
        }

        function escapeHTML(str) {
            return str.replace(/[&<>'"]/g, 
                tag => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                }[tag])
            );
        }

        // Keep focus on input when clicking anywhere
        document.addEventListener('click', () => {
            input.focus();
        });
    </script>
</body>
</html>
