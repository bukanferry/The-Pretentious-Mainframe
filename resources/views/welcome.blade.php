<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hexadella.Space</title>
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased selection:bg-ink selection:text-paper min-h-screen flex flex-col justify-center items-center p-6 relative">

    <div class="max-w-2xl w-full text-center space-y-8 animate-fade-in">
        <!-- Logo / Masthead -->
        <h1 class="font-chomsky text-5xl sm:text-6xl md:text-8xl tracking-wide mb-8">Hexadella.Space</h1>
        
        <!-- Typewriter Line / Quote -->
        <div class="border-y border-ink py-6 my-8">
            <p class="font-serif text-lg md:text-xl leading-relaxed italic opacity-90 px-4">
                "We inhabit a digital tapestry—woven with logic, illuminated by data. Pause here, in the quiet architecture of the web, before descending into the machine."
            </p>
        </div>

        <p class="font-monospace text-xs tracking-widest uppercase opacity-70 mb-12">
            <a href="/terminal" class="hover:text-ink hover:bg-paper transition-colors duration-300" title="Open Terminal">EST. 2026</a> &bull; THE FRONT PAGE
        </p>

        <!-- Entrance Link -->
        <div class="pt-8 md:pt-16">
            <a href="/monitor" class="inline-block font-monospace text-xs tracking-[0.2em] uppercase border border-ink px-6 py-3 hover:bg-ink hover:text-paper transition-all duration-300">
                Access System Monitor &rarr;
            </a>
        </div>
    </div>

</body>
</html>
