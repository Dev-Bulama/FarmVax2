<!-- Floating Disease Scanner Bubble -->
<div id="scanner-bubble-container"
     class="fixed bottom-20 right-4 md:bottom-24 md:right-6 z-40 flex flex-col items-end gap-1.5 select-none">

    <!-- Desktop tooltip label (appears on hover, left of bubble) -->
    <div class="scanner-tooltip-wrap relative flex items-center justify-end">

        <!-- Left-side tooltip (desktop only) -->
        <span id="scanner-tooltip"
              class="hidden md:block absolute right-[4.5rem] whitespace-nowrap text-xs font-semibold text-white bg-primary/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-md opacity-0 translate-x-2 transition-all duration-200 pointer-events-none">
            Detect Disease
        </span>

        <!-- Main Bubble Button -->
        <a href="{{ route('disease-detection.create') }}"
           id="scanner-bubble"
           aria-label="Scan for animal disease"
           class="relative w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full
                  bg-gradient-to-br from-secondary to-primary
                  shadow-lg shadow-secondary/40
                  hover:scale-110 hover:shadow-xl hover:shadow-secondary/50
                  active:scale-95
                  transition-all duration-300
                  touch-manipulation
                  scanner-glow">

            <!-- Pulsing ring -->
            <span class="absolute inset-0 rounded-full bg-secondary/25 animate-scanner-ping pointer-events-none"></span>

            <!-- Camera / Scan icon -->
            <svg class="relative w-7 h-7 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <!-- Camera body -->
                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                <!-- Lens -->
                <circle cx="12" cy="13" r="4"/>
            </svg>
        </a>
    </div>

    <!-- Mobile label (always visible) -->
    <span class="md:hidden text-[10px] font-bold tracking-wide text-primary bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded-full shadow-sm text-center">
        Scan
    </span>
</div>

<style>
    /* Pulse ring animation */
    @keyframes scannerPing {
        0%   { transform: scale(1);   opacity: 0.6; }
        70%  { transform: scale(1.5); opacity: 0;   }
        100% { transform: scale(1.5); opacity: 0;   }
    }

    .animate-scanner-ping {
        animation: scannerPing 2.4s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    /* Subtle glow pulse on the bubble itself */
    @keyframes scannerGlow {
        0%, 100% { box-shadow: 0 4px 20px rgba(47, 203, 110, 0.4); }
        50%       { box-shadow: 0 4px 32px rgba(47, 203, 110, 0.7); }
    }

    .scanner-glow {
        animation: scannerGlow 2.4s ease-in-out infinite;
    }

    /* Show tooltip on hover */
    .scanner-tooltip-wrap:hover #scanner-tooltip {
        opacity: 1;
        transform: translateX(0);
    }

    /* Touch devices: no tap highlight */
    #scanner-bubble {
        -webkit-tap-highlight-color: transparent;
        -webkit-user-select: none;
        user-select: none;
    }
</style>
