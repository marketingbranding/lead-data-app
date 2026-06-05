<div
    x-data="{
        showItem: false,
        timer: null,
        autoHideTimer: null,

        init() {
            this.restoreTimer();

            window.addEventListener('tip-ready', () => {
                this.showItem = true;
                const delay = Math.floor(Math.random() * 11 + 10) * 1000;
                localStorage.setItem('mangharis_next_show', (Date.now() + delay).toString());
                this.startAutoHideTimer();
            });

            document.addEventListener('click', (e) => {
                if (this.showItem && !this.$el.contains(e.target)) {
                    this.showItem = false;
                    this.clearAutoHideTimer();
                    this.scheduleNext();
                }
            });
        },

        restoreTimer() {
            this.clearTimer();
            const nextShow = localStorage.getItem('mangharis_next_show');
            if (nextShow) {
                const remaining = parseInt(nextShow, 10) - Date.now();
                if (remaining > 0) {
                    this.timer = setTimeout(() => { $wire.call('showTip'); }, remaining);
                    return;
                }
            }
            $wire.call('showTip');
        },

        scheduleNext() {
            this.clearTimer();
            const delay = Math.floor(Math.random() * 11 + 10) * 1000;
            localStorage.setItem('mangharis_next_show', (Date.now() + delay).toString());
            this.timer = setTimeout(() => { $wire.call('showTip'); }, delay);
        },

        startAutoHideTimer() {
            this.clearAutoHideTimer();
            this.autoHideTimer = setTimeout(() => {
                this.showItem = false;
                this.scheduleNext();
            }, 5000);
        },

        resetTimer() {
            this.clearTimer();
            this.clearAutoHideTimer();
            this.scheduleNext();
        },

        clearTimer() {
            if (this.timer) { clearTimeout(this.timer); this.timer = null; }
        },

        clearAutoHideTimer() {
            if (this.autoHideTimer) { clearTimeout(this.autoHideTimer); this.autoHideTimer = null; }
        },
    }"
    x-show="showItem"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    style="position: fixed; bottom: -15rem; left: -8rem; z-index: 50; display: none;"
>
    {{-- Bubble + Tail --}}
    <div style="position: absolute; bottom: calc(100% - 2rem); left: 13rem; width: 10rem;">
        {{-- Bubble body --}}
        <div class="mangharis-bubble" style="border-radius: 0; border: 2px solid #000; box-shadow: 2px 2px 0 0 #000; overflow: hidden; background: #eddbaf; position: relative;">

            <div style="padding: 0.5rem; color: #111827; font-size: 0.875rem; line-height: 1.5; font-family: 'VT323', monospace;">
                {{ $tip }}
            </div>
        </div>
        {{-- Tail triangle --}}
        <div style="position: absolute; bottom: -9px; left: 1.5rem; width: 18px; height: 18px; background: #eddbaf; border-right: 2px solid #000; border-bottom: 2px solid #000; border-radius: 0; transform: rotate(45deg);"></div>
    </div>

    {{-- Avatar --}}
    <img
        x-on:click="showItem = false; resetTimer()"
        src="{{ asset('svg/mang-haris.svg') }}"
        alt="Mang Haris"
        style="width: auto; height: 30rem; cursor: pointer; transform: rotate(30deg);"
        onerror="this.outerHTML='<svg xmlns=\'http://www.w3.org/2000/svg\' style=\'width:auto;height:30rem;transform:rotate(30deg)\' viewBox=\'0 0 100 100\' fill=\'none\'><circle cx=\'50\' cy=\'45\' r=\'30\' fill=\'#fbbf24\'/><circle cx=\'38\' cy=\'40\' r=\'3\' fill=\'#1f2937\'/><circle cx=\'62\' cy=\'40\' r=\'3\' fill=\'#1f2937\'/><path d=\'M35 55 Q50 65 65 55\' stroke=\'#1f2937\' stroke-width=\'2.5\' fill=\'none\' stroke-linecap=\'round\'/><rect x=\'20\' y=\'70\' width=\'60\' height=\'25\' rx=\'8\' fill=\'#d97706\'/></svg>'"
    />
</div>
<style>
@import url('https://fonts.googleapis.com/css2?family=VT323&display=swap');

.mangharis-bubble::after {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
        0deg,
        rgba(0,0,0,0.08) 0px,
        rgba(0,0,0,0.08) 1px,
        transparent 1px,
        transparent 3px
    );
    pointer-events: none;
}
</style>

