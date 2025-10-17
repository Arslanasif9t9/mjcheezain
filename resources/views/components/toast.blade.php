@props([
    'type' => 'success', // success, error, warning
    'message' => '',
    'id' => null
])

@php
    $id = $id ?? uniqid('toast-');
    
    $config = [
        'success' => [
            'bgColor' => 'bg-green-50 border-green-200',
            'textColor' => 'text-green-800',
            'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            'iconColor' => 'text-green-500'
        ],
        'error' => [
            'bgColor' => 'bg-red-50 border-red-200',
            'textColor' => 'text-red-800',
            'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            'iconColor' => 'text-red-500'
        ],
        'warning' => [
            'bgColor' => 'bg-yellow-50 border-yellow-200',
            'textColor' => 'text-yellow-800',
            'icon' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            'iconColor' => 'text-yellow-500'
        ]
    ];
    
    $style = $config[$type] ?? $config['success'];
@endphp

<div class="abc" id="{{ $id }}" 
     class="fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg border {{ $style['bgColor'] }} transform transition-all duration-300 ease-in-out opacity-0 translate-y-2"
     x-data="{ show: false }"
     x-init="setTimeout(() => { show = true }, 100); setTimeout(() => { show = false; setTimeout(() => { $el.remove() }, 300) }, 3000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2">
    
    <div class="flex items-start p-4">
        <!-- Icon -->
        <div class="flex-shrink-0 {{ $style['iconColor'] }} mt-0.5">
            {!! $style['icon'] !!}
        </div>
        
        <!-- Message -->
        <div class="ml-3 flex-1 {{ $style['textColor'] }}">
            <p class="text-sm font-medium leading-5">
                {{ $message }}
            </p>
        </div>
        
        <!-- Close Button -->
        <button type="button" 
                class="ml-4 flex-shrink-0 inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
                @click="show = false; setTimeout(() => { $el.remove() }, 300)">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    
    <!-- Progress Bar -->
    <div class="w-full h-1 bg-gray-200 rounded-b-lg overflow-hidden">
        <div class="h-full {{ $type === 'success' ? 'bg-green-500' : ($type === 'error' ? 'bg-red-500' : 'bg-yellow-500') }} transition-all duration-3000 ease-linear"
             x-data="{ width: '100%' }"
             x-init="setTimeout(() => width = '0%', 100)"></div>
    </div>
</div>

<script>
    document.querySelector('.abc').classList.add('hidden');
    class ToastManager {
    constructor() {
        this.container = document.getElementById('toast-container') || this.createContainer();
        this.toasts = [];
    }

    createContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-3';
        document.body.appendChild(container);
        return container;
    }

    show(type, message, duration = 5000) {
        const id = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${id}" class="max-w-sm w-full bg-white rounded-lg shadow-lg border transform transition-all duration-300 ease-in-out ${
                type === 'success' ? 'bg-green-50 border-green-200' : 
                type === 'error' ? 'bg-red-50 border-red-200' : 
                'bg-yellow-50 border-yellow-200'
            }">
                <div class="flex items-start p-4">
                    <div class="flex-shrink-0 ${
                        type === 'success' ? 'text-green-500' : 
                        type === 'error' ? 'text-red-500' : 
                        'text-yellow-500'
                    } mt-0.5">
                        ${
                            type === 'success' ? 
                            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>' :
                            type === 'error' ?
                            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>' :
                            '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
                        }
                    </div>
                    <div class="ml-3 flex-1 ${
                        type === 'success' ? 'text-green-800' : 
                        type === 'error' ? 'text-red-800' : 
                        'text-yellow-800'
                    }">
                        <p class="text-sm font-medium leading-5">${message}</p>
                    </div>
                    <button type="button" class="ml-4 flex-shrink-0 inline-flex text-gray-400 hover:text-gray-500 focus:outline-none transition duration-150" onclick="this.closest('[id^=toast-]').remove()">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="w-full h-1 bg-gray-200 rounded-b-lg overflow-hidden">
                    <div class="h-full ${
                        type === 'success' ? 'bg-green-500' : 
                        type === 'error' ? 'bg-red-500' : 
                        'bg-yellow-500'
                    } transition-all duration-${duration} ease-linear" style="width: 100%"></div>
                </div>
            </div>
        `;

        const toastElement = this.createToastElement(toastHtml);
        this.container.appendChild(toastElement);

        // Animate in
        setTimeout(() => {
            toastElement.classList.remove('opacity-0', 'translate-y-2');
        }, 10);

        // Auto remove after duration
        setTimeout(() => {
            this.removeToast(toastElement);
        }, duration);

        this.toasts.push({ id, element: toastElement });
    }

    createToastElement(html) {
        const template = document.createElement('template');
        template.innerHTML = html.trim();
        const element = template.content.firstChild;
        
        // Initial hidden state
        element.classList.add('opacity-0', 'translate-y-2');
        
        return element;
    }

    removeToast(element) {
        element.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }

    success(message, duration = 5000) {
        this.show('success', message, duration);
    }

    error(message, duration = 5000) {
        this.show('error', message, duration);
    }

    warning(message, duration = 5000) {
        this.show('warning', message, duration);
    }
}

// Global instance
window.toastManager = new ToastManager();

// Helper functions for quick access
window.showToast = (type, message) => {
    toastManager.show(type, message);
};

window.showSuccess = (message) => {
    toastManager.success(message);
};

window.showError = (message) => {
    toastManager.error(message);
};

window.showWarning = (message) => {
    toastManager.warning(message);
};
</script>