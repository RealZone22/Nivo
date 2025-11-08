<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))" @class([
    'fixed z-50 p-4 w-full flex flex-col pointer-events-none sm:p-6',
    'bottom-0' => $alignment->is('bottom'),
    'top-1/2 -translate-y-1/2' => $alignment->is('middle'),
    'top-0' => $alignment->is('top'),
    'items-start rtl:items-end' => $position->is('left'),
    'items-center' => $position->is('center'),
    'items-end rtl:items-start' => $position->is('right'),
 ])>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.isVisible"
             x-data="{ progress: 100 }"
             x-init="(() => {
                $nextTick(() => {
                    setTimeout(() => {
                        toast.show($el);
                        const duration = toast.duration || 6000;
                        const interval = 50;
                        const step = (100 / duration) * interval;
                        const timer = setInterval(() => {
                            progress -= step;
                            if (progress <= 0) {
                                progress = 0;
                                clearInterval(timer);
                            }
                        }, interval);
                    }, 0);
                });
            })()"
             @if($alignment->is('bottom'))
                 x-transition:enter-start="translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @elseif($alignment->is('top'))
                 x-transition:enter-start="-translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @else
                 x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             @endif
             x-transition:leave-end="opacity-0 scale-90"
             class="relative duration-300 mt-2 transform transition ease-in-out max-w-sm w-full pointer-events-auto overflow-hidden rounded-xl border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark"
        >
            <div class="flex w-full gap-2 p-4 items-center">
                <span class="flex items-center justify-center self-center text-lg">
                    <template x-if="toast.type === 'success'">
                        <i class="icon-check text-success text-lg leading-none flex items-center justify-center"></i>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <i class="icon-x text-danger text-lg leading-none flex items-center justify-center"></i>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <i class="icon-info text-info text-lg leading-none flex items-center justify-center"></i>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <i class="icon-triangle-alert text-warning text-lg leading-none flex items-center justify-center"></i>
                    </template>
                </span>
                <div class="mb-0.5 grid flex-1">
                    <div class="text-sm" x-text="toast.message"></div>
                </div>
                @if($closeable)
                    <span class="cursor-pointer flex items-center" @click="toast.dispose()">
                        <i class="icon-x"></i>
                    </span>
                @endif
            </div>
            <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700">
                <div class="h-full transition-all duration-100 ease-linear"
                     :class="{
                         'bg-success': toast.type === 'success',
                         'bg-danger': toast.type === 'error',
                         'bg-info': toast.type === 'info',
                         'bg-warning': toast.type === 'warning'
                     }"
                     :style="`width: ${progress}%`">
                </div>
            </div>
        </div>
    </template>
</div>
