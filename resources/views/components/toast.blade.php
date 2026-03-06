<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-99999 flex flex-col space-y-3">
    <!-- Toast messages will be inserted here -->
</div>

<!-- Toast Template (Hidden) -->
<template id="toast-template">
    <div class="flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-sm shadow-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:shadow-gray-900"
        role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {icon-color} rounded-lg">
            <span class="toast-icon">{icon}</span>
        </div>
        <div class="ms-3 text-sm font-normal toast-message">{message}</div>
        <button type="button"
            class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
            data-dismiss="{id}">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
        </button>
    </div>
</template>

<script>
    // Toast types with their respective icons and colors
    const toastTypes = {
        success: {
            icon: `<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>`,
            color: 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200'
        },
        error: {
            icon: `<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
            </svg>`,
            color: 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200'
        },
        warning: {
            icon: `<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
            </svg>`,
            color: 'text-yellow-500 bg-yellow-100 dark:bg-yellow-800 dark:text-yellow-200'
        },
        info: {
            icon: `<svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>`,
            color: 'text-blue-500 bg-blue-100 dark:bg-blue-800 dark:text-blue-200'
        }
    };

    // Show a toast notification
    function showToast(message, type = 'info', duration = 5000) {
        const toastContainer = document.getElementById('toast-container');
        const toastTemplate = document.getElementById('toast-template');

        // Generate unique ID
        const toastId = 'toast-' + Date.now();

        const toastConfig = toastTypes[type] || toastTypes.info;

        // Create toast HTML
        let toastHtml = toastTemplate.innerHTML
            .replace(/{icon}/g, toastConfig.icon)
            .replace(/{icon-color}/g, toastConfig.color)
            .replace(/{message}/g, message);

        // Create element
        const toastElement = document.createElement('div');
        toastElement.innerHTML = toastHtml.trim();
        const toastNode = toastElement.firstElementChild;

        // Set ID di sini
        toastNode.id = toastId;

        // Add to container
        toastContainer.appendChild(toastNode);

        // Animate in
        setTimeout(() => {
            toastNode.classList.add('animate-fade-in-down');
        }, 10);

        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => {
                dismissToast(toastId);
            }, duration);
        }

        // Close button
        const closeButton = toastNode.querySelector('[data-dismiss]');
        if (closeButton) {
            closeButton.onclick = () => dismissToast(toastId);
        }

        return toastId;
    }

    // Dismiss a specific toast
    function dismissToast(toastId) {
        const toastElement = document.getElementById(toastId);
        if (toastElement) {
            toastElement.classList.add('animate-fade-out');
            setTimeout(() => {
                if (toastElement.parentNode) {
                    toastElement.parentNode.removeChild(toastElement);
                }
            }, 300); // Harus sesuai durasi animasi
        }
    }

    // Clear all toasts
    function clearToasts() {
        const toastContainer = document.getElementById('toast-container');
        toastContainer.innerHTML = '';
    }

    // Animations
    const style = document.createElement('style');
    style.textContent = `
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-1rem);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-1rem);
        }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out forwards;
    }
    .animate-fade-out {
        animation: fadeOut 0.3s ease-out forwards;
    }
`;
    document.head.appendChild(style);

    // Show session toasts
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('toast'))
            showToast('{{ session('toast.message') }}', '{{ session('toast.type') }}');
        @endif
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
});
</script>