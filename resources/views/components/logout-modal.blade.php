<div id="logoutModal" 
     class="fixed inset-0 z-50 flex items-center justify-center hidden transition-all duration-300 ease-out">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-300 opacity-0"
         id="modalBackdrop"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
         id="modalContent">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full">
                <i class="fas fa-sign-out-alt text-red-500 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Logout</h2>
            <p class="text-gray-600 text-center">Are you sure you want to logout?</p>
        </div>

        <!-- Message -->
        <div class="p-6">
            <div class="flex items-start space-x-3 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <i class="fas fa-info-circle text-blue-500 mt-1 flex-shrink-0"></i>
                <p class="text-blue-700 text-sm">You'll need to sign in again to access your dashboard.</p>
            </div>
        </div>

        <!-- Buttons -->
        <div class="p-6 border-t border-gray-100 flex space-x-3">
            <button id="cancelLogoutBtn"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-times mr-2"></i>No, Cancel
            </button>
            <form id="logoutForm" action="{{ route('logout') }}" method="GET" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i class="fas fa-check mr-2"></i>Yes, Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    class LogoutModal {
        constructor() {
            this.modal = document.getElementById('logoutModal');
            this.backdrop = document.getElementById('modalBackdrop');
            this.content = document.getElementById('modalContent');
            this.cancelBtn = document.getElementById('cancelLogoutBtn');
            this.init();
        }

        init() {
            // Show modal when logout button is clicked
            document.getElementById('logoutBtn')?.addEventListener('click', (e) => {
                e.preventDefault();
                this.show();
            });

            // Hide modal when cancel button is clicked
            this.cancelBtn?.addEventListener('click', () => {
                this.hide();
            });

            // Hide modal when clicking on backdrop
            this.backdrop?.addEventListener('click', () => {
                this.hide();
            });

            // Close modal with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                    this.hide();
                }
            });
        }

        show() {
            this.modal.classList.remove('hidden');
            
            // Trigger reflow
            void this.modal.offsetWidth;
            
            // Animate backdrop
            setTimeout(() => {
                this.backdrop.classList.remove('opacity-0');
                this.backdrop.classList.add('opacity-100');
            }, 10);
            
            // Animate content
            setTimeout(() => {
                this.content.classList.remove('scale-95', 'opacity-0');
                this.content.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        hide() {
            // Animate content out
            this.content.classList.remove('scale-100', 'opacity-100');
            this.content.classList.add('scale-95', 'opacity-0');
            
            // Animate backdrop out
            this.backdrop.classList.remove('opacity-100');
            this.backdrop.classList.add('opacity-0');
            
            // Hide modal after animation
            setTimeout(() => {
                this.modal.classList.add('hidden');
            }, 300);
        }
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        new LogoutModal();
    });
</script>