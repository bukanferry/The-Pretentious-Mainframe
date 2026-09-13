<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gamble | Hexadella.Space</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .crash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: #0000AA;
            color: white;
            font-family: monospace;
            padding: 2rem;
            z-index: 9999;
            display: none;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>
<body class="antialiased selection:bg-ink selection:text-paper min-h-screen bg-paper text-ink p-4 md:p-8 flex flex-col items-center justify-center relative overflow-x-hidden">

    <div id="game-container" class="text-center w-full max-w-3xl mx-auto space-y-12 animate-fade-in py-8">
        
        <h1 class="font-chomsky text-4xl sm:text-6xl md:text-8xl tracking-wide mb-4">Hexadella.Space/<wbr>Gamble</h1>
        
        <p class="font-serif text-xs sm:text-sm leading-relaxed max-w-4xl mx-auto text-justify mb-8 px-2 opacity-80">
            Welcome to the digital roulette. In this forgotten corner of the mainframe, risk is the only currency. The mechanism is simple: spin the cylinder, test your fate. The watchers record those who survive the click. Survival brings nothing but the right to try again.
        </p>

        <!-- SVG Cylinder -->
        <div class="py-8">
            <svg viewBox="0 0 100 100" class="w-48 h-48 sm:w-64 sm:h-64 mx-auto text-ink" id="cylinder">
                <!-- Base Shape -->
                <circle cx="50" cy="50" r="45" fill="currentColor"/>
                
                <!-- Edge scallops (background color) -->
                <g fill="var(--color-paper)">
                    <circle cx="50" cy="2" r="8" />
                    <circle cx="91.5" cy="26" r="8" />
                    <circle cx="91.5" cy="74" r="8" />
                    <circle cx="50" cy="98" r="8" />
                    <circle cx="8.5" cy="74" r="8" />
                    <circle cx="8.5" cy="26" r="8" />
                </g>

                <!-- 6 Chambers -->
                <g fill="var(--color-paper)">
                    <circle cx="50" cy="22" r="11" />
                    <circle cx="74.2" cy="36" r="11" />
                    <circle cx="74.2" cy="64" r="11" />
                    <circle cx="50" cy="78" r="11" />
                    <circle cx="25.8" cy="64" r="11" />
                    <circle cx="25.8" cy="36" r="11" />
                </g>

                <!-- Bullets (Ink color) -->
                <g fill="currentColor" id="bullet-group">
                    <circle id="b-0" cx="50" cy="22" r="5" />
                    <circle id="b-1" cx="74.2" cy="36" r="5" />
                    <circle id="b-2" cx="74.2" cy="64" r="5" />
                    <circle id="b-3" cx="50" cy="78" r="5" />
                    <circle id="b-4" cx="25.8" cy="64" r="5" />
                    <circle id="b-5" cx="25.8" cy="36" r="5" />
                </g>

                <!-- Center -->
                <circle cx="50" cy="50" r="12" fill="var(--color-paper)"/>
                <circle cx="50" cy="50" r="7" fill="currentColor"/>
            </svg>
        </div>

        <div class="space-y-6 relative z-10">
            <h2 class="font-chomsky text-3xl sm:text-4xl" id="statusText">how brave are you?</h2>
            <p class="font-monospace text-xs opacity-70 uppercase tracking-widest" id="ammoText">6 ROUNDS REMAINING</p>
            
            <button id="clickBtn" class="font-chomsky text-4xl sm:text-5xl hover:bg-ink hover:text-paper transition-all duration-300 focus:outline-none uppercase tracking-widest cursor-pointer border-2 border-ink px-8 py-3">
                PULL TRIGGER
            </button>
        </div>

        <div class="font-monospace text-xs opacity-50 mt-12 mb-8">
            Survivals: <span id="score">0</span>
        </div>

        <!-- Footer -->
        <footer class="mt-8 border-t-4 border-double border-ink pt-6 pb-2 text-center relative w-full">
            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-paper px-4">
                <span class="font-chomsky text-2xl">fin.</span>
            </div>
            <p class="font-serif text-sm leading-relaxed max-w-2xl mx-auto italic opacity-90">
                "We play not to win, but to feel the cold breath of chance upon our necks. Every spin is a heartbeat; every click, a lifetime."
            </p>
            <div class="mt-6 font-monospace text-xs tracking-widest uppercase border-y border-ink inline-block py-1 px-8">
                Gamblers Anonymous &bull; EST. 2026
            </div>
        </footer>
    </div>

    <!-- Blue Screen of Death overlay -->
    <div id="bsod" class="crash-screen">
        <h1 class="text-4xl mb-4 bg-white text-[#0000AA] inline-block px-2 font-bold w-max">FATAL ERROR</h1>
        <p class="mb-4">A fatal exception 0E has occurred at 0028:C0011E36 in VXD VMM(01).</p>
        <p class="mb-4">The current application will be terminated.</p>
        <p class="mb-8">* Press any key to terminate the current application.<br>* Press CTRL+ALT+DEL again to restart your computer.</p>
        <p class="text-center animate-pulse">Press any key to continue _</p>
    </div>

    <!-- Flash overlay -->
    <div id="flash" class="fixed inset-0 bg-red-600 opacity-0 pointer-events-none transition-opacity duration-75 z-50"></div>

    <script>
        const btn = document.getElementById('clickBtn');
        const cylinder = document.getElementById('cylinder');
        const statusText = document.getElementById('statusText');
        const ammoText = document.getElementById('ammoText');
        const scoreSpan = document.getElementById('score');
        const bsod = document.getElementById('bsod');
        const flash = document.getElementById('flash');
        
        let score = parseInt(localStorage.getItem('hexadella_gamble_score')) || 0;
        scoreSpan.innerText = score;
        
        let currentRotation = 0;
        let isSpinning = false;
        
        // Game State
        let bulletsRemaining = 6;
        
        function updateVisuals() {
            ammoText.innerText = `${bulletsRemaining} ROUNDS REMAINING`;
            for(let i = 0; i < 6; i++) {
                document.getElementById('b-' + i).style.opacity = (i < bulletsRemaining) ? '1' : '0';
            }
        }

        btn.addEventListener('click', () => {
            if (isSpinning || bulletsRemaining === 0) return;
            isSpinning = true;
            btn.style.opacity = '0.5';
            btn.style.cursor = 'not-allowed';
            statusText.innerText = "spinning...";

            // Determine if bullet fires: 1 fatal bullet among the remaining ones
            const isDead = Math.random() < (1.0 / bulletsRemaining);

            // Calculate final rotation
            const spins = 10 * 360;
            const extraDegrees = Math.floor(Math.random() * 6) * 60;
            currentRotation += spins + extraDegrees;

            // Apply spin animation
            cylinder.style.transition = 'transform 2s cubic-bezier(0.25, 0.1, 0.25, 1)';
            cylinder.style.transform = `rotate(${currentRotation}deg)`;

            setTimeout(() => {
                if (isDead) {
                    triggerDeath();
                } else {
                    bulletsRemaining--;
                    triggerSurvival();
                }
            }, 2000);
        });

        function triggerSurvival() {
            score++;
            localStorage.setItem('hexadella_gamble_score', score);
            scoreSpan.innerText = score;
            
            updateVisuals();
            statusText.innerText = "click... blank round.";
            
            if (bulletsRemaining > 0) {
                btn.style.opacity = '1';
                btn.style.cursor = 'pointer';
                isSpinning = false;
            } else {
                statusText.innerText = "you survived them all.";
            }
        }

        function triggerDeath() {
            flash.style.opacity = '1';
            
            score = 0;
            localStorage.setItem('hexadella_gamble_score', 0);
            
            setTimeout(() => {
                bsod.style.display = 'flex';
                
                setTimeout(() => {
                    const reloadHandler = () => {
                        window.location.reload();
                    };
                    document.addEventListener('keydown', reloadHandler);
                    document.addEventListener('click', reloadHandler);
                }, 1000);
            }, 100);
        }
        
        // Initial setup
        updateVisuals();
    </script>
</body>
</html>
