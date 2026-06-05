@if (!$muted)
<div
    x-data="{
        showItem: false,
        autoHideTimer: null,

        init() {
            this.startTimer();

            window.addEventListener('tip-ready', () => {
                this.showItem = true;
                this.startAutoHideTimer();
            });

            document.addEventListener('click', (e) => {
                if (this.showItem && !this.$el.contains(e.target)) {
                    this.showItem = false;
                    this.clearAutoHideTimer();
                    this.startTimer();
                }
            });
        },

        startTimer() {
            this.clearTimer();
            const min = 10;
            const max = 20;
            const delay = Math.floor(Math.random() * (max - min + 1) + min) * 1000;
            this.timer = setTimeout(() => {
                $wire.call('showTip');
            }, delay);
        },

        startAutoHideTimer() {
            this.clearAutoHideTimer();
            this.autoHideTimer = setTimeout(() => {
                this.showItem = false;
                this.startTimer();
            }, 5000);
        },

        resetTimer() {
            this.clearTimer();
            this.clearAutoHideTimer();
            this.startTimer();
        },

        clearTimer() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
        },

        clearAutoHideTimer() {
            if (this.autoHideTimer) {
                clearTimeout(this.autoHideTimer);
                this.autoHideTimer = null;
            }
        },
    }"
    style="position: fixed; bottom: 1.5rem; left: 1.5rem; z-index: 50;"
>
    {{-- Avatar + Tip bubble wrapper --}}
    <div
        x-show="showItem"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        style="display: none;"
    >
        {{-- Tip bubble --}}
        <div style="position: absolute; bottom: calc(100% + 1rem); left: 0; width: 20rem; background: white; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="background: #d97706; color: white; padding: 0.5rem 0.75rem; display: flex; align-items: center; justify-content: space-between; font-size: 0.75rem; font-weight: 600;">
                <span>Mang Haris</span>
                <button x-on:click="showItem = false; resetTimer()" style="color: rgba(255,255,255,0.8); background: none; border: none; cursor: pointer; padding: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width: 1rem; height: 1rem;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div style="padding: 0.75rem 1rem; color: #111827; font-size: 0.875rem; line-height: 1.5;">
                {{ $tip }}
                @if ($contextual)
                    <br><br><span style="font-size: 0.75rem; color: #9ca3af;">💡 {{ $contextual }}</span>
                @endif
            </div>
        </div>

        {{-- Avatar --}}
        <button
            x-on:click="showItem = false; resetTimer()"
            x-on:mouseenter="hover = true"
            x-on:mouseleave="hover = false"
            x-bind:style="hover
                ? 'transform: scale(1.1); display: flex; align-items: center; justify-content: center; width: 4.5rem; height: 4.5rem; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); background: linear-gradient(135deg, #d97706, #b45309); color: white; cursor: pointer; border: none; transition: transform 0.2s;'
                : 'display: flex; align-items: center; justify-content: center; width: 4.5rem; height: 4.5rem; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); background: linear-gradient(135deg, #d97706, #b45309); color: white; cursor: pointer; border: none; transition: transform 0.2s;'"
            style="display: flex; align-items: center; justify-content: center; width: 4.5rem; height: 4.5rem; border-radius: 9999px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); background: linear-gradient(135deg, #d97706, #b45309); color: white; cursor: pointer; border: none; transition: transform 0.2s;"
            title="Mang Haris"
        >
            <img
                src="{{ asset('svg/mang-haris.svg') }}"
                alt="Mang Haris"
                style="width: 3.5rem; height: 3.5rem; border-radius: 9999px; animation: mangharis-float 3s ease-in-out infinite; object-fit: cover;"
                onerror="this.outerHTML='<svg xmlns=\'http://www.w3.org/2000/svg\' style=\'width:3.5rem;height:3.5rem;animation:mangharis-float 3s ease-in-out infinite\' viewBox=\'0 0 100 100\' fill=\'none\'><circle cx=\'50\' cy=\'45\' r=\'30\' fill=\'#fbbf24\'/><circle cx=\'38\' cy=\'40\' r=\'3\' fill=\'#1f2937\'/><circle cx=\'62\' cy=\'40\' r=\'3\' fill=\'#1f2937\'/><path d=\'M35 55 Q50 65 65 55\' stroke=\'#1f2937\' stroke-width=\'2.5\' fill=\'none\' stroke-linecap=\'round\'/><rect x=\'20\' y=\'70\' width=\'60\' height=\'25\' rx=\'8\' fill=\'#d97706\'/></svg>'"
            />
        </button>
    </div>
</div>
<style>
@keyframes mangharis-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
}
</style>
@endif
