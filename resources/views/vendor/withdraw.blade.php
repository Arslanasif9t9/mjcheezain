<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Balance | Vendor Panel</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#fdf2f8',
                            100: '#fce7f3',
                            500: '#E85D85',
                            600: '#C94A72',
                            700: '#B03D63',
                        },
                        success: {
                            50: '#f0fdf4',
                            500: '#10b981',
                            600: '#059669',
                        },
                        warning: {
                            50: '#fffbeb',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    boxShadow: {
                        'card': '0 4px 20px rgba(0, 0, 0, 0.08)',
                        'card-lg': '0 10px 40px rgba(0, 0, 0, 0.12)',
                    },
                    borderRadius: {
                        'xl': '12px',
                        '2xl': '16px',
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen" style="background-color: #FFF6F0;">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name ?? null"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            :user="$user"
            page='Withdraw'
        />
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0 transition-all duration-300">
            <x-vendor.app-header title="Withdraw" subtitle="Move your earnings to your bank" />

            <main class="flex-1 p-4 md:p-6 lg:p-8 pb-28 md:pb-8 page-enter">
            <!-- Page Header (desktop only) -->
            <div class="hidden md:flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 pb-6 border-b border-gray-200">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-wallet text-primary-500 mr-3"></i>
                        Withdraw Balance
                    </h1>
                    <p class="text-gray-600 mt-2">Withdraw your available earnings to your bank account</p>
                </div>
                <a href="{{ route('vendor.balance.details') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                    <i class="fas fa-chart-line mr-2"></i>
                    View Balance Details
                </a>
            </div>
            
            <!-- Alerts -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center animate-fade-in">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <div class="flex-1">
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="text-green-500 hover:text-green-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center animate-fade-in">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                    <div class="flex-1">
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            <!-- Balance Summary — mobile wallet hero card -->
            <div class="md:hidden -mt-2 mb-5 relative z-10">
                <div class="brand-gradient brand-shadow rounded-3xl p-5 text-white relative overflow-hidden">
                    <div class="absolute -top-8 -right-8 w-28 h-28 bg-white/10 rounded-full pointer-events-none"></div>
                    <div class="absolute bottom-0 -left-6 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-semibold uppercase tracking-widest text-white/80">Available Balance</p>
                            <span class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <i class="fas fa-wallet text-xs"></i>
                            </span>
                        </div>
                        <p class="text-[34px] leading-tight font-extrabold tracking-tight mt-1">Rs. {{ number_format($balance->available_balance, 2) }}</p>
                        <p class="text-[11px] text-white/75 mt-0.5">Ready for immediate withdrawal</p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/20">
                            <div>
                                <p class="text-[10px] text-white/70 font-semibold uppercase tracking-wider">Pending</p>
                                <p class="text-base font-bold leading-tight">Rs. {{ number_format($balance->pending_balance, 2) }}</p>
                            </div>
                            <a href="{{ route('vendor.balance.details') }}"
                               class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm text-white text-xs font-semibold active:scale-95 transition-transform">
                                <i class="fas fa-chart-line mr-1.5 text-[10px]"></i>
                                Balance Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance Summary (desktop) -->
            <div class="hidden md:grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Available Balance Card -->
                <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="p-3 bg-pink-50 rounded-xl mr-4">
                                <i class="fas fa-check-circle text-[#E85D85] text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Available Balance</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-50 text-[#C94A72] mt-1">
                                    Withdrawable
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="text-3xl lg:text-4xl font-bold text-[#E85D85]">
                            Rs. {{ number_format($balance->available_balance, 2) }}
                        </div>
                        <p class="text-gray-500 text-sm mt-2">This amount is ready for immediate withdrawal</p>
                    </div>
                </div>
                
                <!-- Pending Balance Card -->
                <div class="bg-white rounded-2xl shadow-card p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="p-3 bg-amber-50 rounded-xl mr-4">
                                <i class="fas fa-clock text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Pending Balance</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 mt-1">
                                    Processing
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="text-3xl lg:text-4xl font-bold text-amber-600">
                            Rs. {{ number_format($balance->pending_balance, 2) }}
                        </div>
                        <p class="text-gray-500 text-sm mt-2">Will be available after processing period</p>
                    </div>
                </div>
            </div>
            
            <!-- Withdrawal Form Card -->
            <div class="app-card md:bg-white rounded-3xl md:rounded-2xl md:shadow-card overflow-hidden md:border md:border-gray-100 mb-6 md:mb-8">
                <!-- Card Header -->
                <div class="brand-gradient text-white px-4 md:px-6 py-4 md:py-5">
                    <div class="flex items-center">
                        <span class="w-9 h-9 md:w-auto md:h-auto rounded-full md:rounded-none bg-white/20 md:bg-transparent flex items-center justify-center mr-3">
                            <i class="fas fa-university text-sm md:text-2xl"></i>
                        </span>
                        <h2 class="text-base md:text-xl font-bold">Bank Withdrawal Request</h2>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 md:p-6 lg:p-8">
                    <form action="{{ route('vendor.withdraw.process') }}" method="POST" id="withdrawalForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Amount Input -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Withdrawal Amount <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-bold text-sm">PKR</span>
                                    <input type="number" 
                                           id="amount"
                                           name="amount"
                                           class="w-full pl-14 pr-4 py-3 bg-white border border-pink-100 md:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85] transition-colors duration-200"
                                           placeholder="Enter amount"
                                           min="100"
                                           step="0.01"
                                           max="{{ $balance->available_balance }}"
                                           required>
                                </div>
                                <div class="text-sm text-gray-500 mt-2">
                                    Minimum: Rs. 100 | Maximum: Rs. {{ number_format($balance->available_balance, 2) }}
                                </div>
                            </div>
                            
                            <!-- Bank Name -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Bank Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="bank_name"
                                       name="bank_name"
                                       class="w-full px-4 py-3 bg-white border border-pink-100 md:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85] transition-colors duration-200"
                                       placeholder="e.g., State Bank of India"
                                       required>
                            </div>
                            
                            <!-- Account Holder Name -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Account Holder Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="account_holder_name"
                                       name="account_holder_name"
                                       class="w-full px-4 py-3 bg-white border border-pink-100 md:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85] transition-colors duration-200"
                                       placeholder="As per bank records"
                                       required>
                            </div>
                            
                            <!-- Account Number -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Account Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="account_number"
                                       name="account_number"
                                       class="w-full px-4 py-3 bg-white border border-pink-100 md:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85] transition-colors duration-200"
                                       placeholder="Enter your account number"
                                       required>
                            </div>
                            
                            <!-- IFSC Code -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">
                                    IFSC Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="ifsc_code"
                                       name="ifsc_code"
                                       class="w-full px-4 py-3 bg-white border border-pink-100 md:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85] transition-colors duration-200"
                                       placeholder="e.g., SBIN0001234"
                                       required>
                            </div>
                            
                            <!-- Notes -->
                            <div class="lg:col-span-2">
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Additional Notes (Optional)
                                </label>
                                <textarea id="notes"
                                          name="notes"
                                          rows="3"
                                          class="w-full px-4 py-3 bg-white border border-pink-100 md:border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85] transition-colors duration-200"
                                          placeholder="Any special instructions or reference"></textarea>
                            </div>
                        </div>
                        
                        <!-- Guidelines Box -->
                        <div class="mt-6 md:mt-8 p-4 md:p-6 bg-pink-50 border-l-4 border-primary-500 rounded-2xl">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-info-circle text-primary-500 text-xl mr-3"></i>
                                <h4 class="text-lg font-bold text-gray-800">Important Guidelines</h4>
                            </div>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-primary-500 text-xs mt-2 mr-3"></i>
                                    <span>Minimum withdrawal amount is <strong>Rs. 100</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-primary-500 text-xs mt-2 mr-3"></i>
                                    <span>Withdrawal requests are processed within <strong>24-48 working hours</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-primary-500 text-xs mt-2 mr-3"></i>
                                    <span>Ensure bank details are correct - we are not responsible for transfers to wrong accounts</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-primary-500 text-xs mt-2 mr-3"></i>
                                    <span>Pending balance will become available after order completion period (typically 7-14 days)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-primary-500 text-xs mt-2 mr-3"></i>
                                    <span>Transaction fee of <strong>Rs. 10</strong> applies for withdrawals below Rs. 1000</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-primary-500 text-xs mt-2 mr-3"></i>
                                    <span>All withdrawals are subject to verification and may require additional documentation</span>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Form Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mt-6 md:mt-8">
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 md:py-4 brand-gradient brand-shadow text-white font-semibold rounded-full md:rounded-xl hover:shadow-lg transition-all duration-300 active:scale-[0.98] hover:-translate-y-0.5">
                                <i class="fas fa-paper-plane mr-3"></i>
                                Submit Withdrawal Request
                            </button>
                            <button type="reset"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3.5 md:py-4 bg-gray-100 text-gray-700 font-semibold rounded-full md:rounded-xl hover:bg-gray-200 active:scale-[0.98] transition-all duration-200">
                                <i class="fas fa-redo mr-3"></i>
                                Clear Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Quick Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6">
                <div class="app-card p-4 md:p-5">
                    <div class="flex items-center">
                        <div class="w-11 h-11 rounded-xl brand-gradient-soft flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-shield-alt text-[#E85D85] text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h5 class="font-bold text-gray-800 text-sm md:text-base">Secure Transactions</h5>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Bank-level security for all transactions</p>
                        </div>
                    </div>
                </div>

                <div class="app-card p-4 md:p-5">
                    <div class="flex items-center">
                        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-history text-green-500 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h5 class="font-bold text-gray-800 text-sm md:text-base">Transaction History</h5>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Track all withdrawals in real-time</p>
                        </div>
                    </div>
                </div>

                <div class="app-card p-4 md:p-5">
                    <div class="flex items-center">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-headset text-purple-500 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <h5 class="font-bold text-gray-800 text-sm md:text-base">24/7 Support</h5>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Contact support for any withdrawal issues</p>
                        </div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const maxAmount = parseFloat("{{ $balance->available_balance }}");
            
            // Format amount on blur
            amountInput.addEventListener('blur', function() {
                const value = parseFloat(this.value);
                if (!isNaN(value) && value >= 100 && value <= maxAmount) {
                    this.value = value.toFixed(2);
                }
            });
            
            // Validate amount on input
            amountInput.addEventListener('input', function() {
                const value = parseFloat(this.value);
                
                if (value > maxAmount) {
                    this.value = maxAmount;
                    showToast('Amount cannot exceed your available balance!', 'warning');
                }
                
                if (value < 100 && this.value !== '') {
                    this.value = 100;
                    showToast('Minimum withdrawal amount is Rs. 100', 'info');
                }
            });
            
            // Form submission validation
            document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
                const amount = parseFloat(amountInput.value);
                
                if (isNaN(amount) || amount < 100) {
                    e.preventDefault();
                    showToast('Please enter a valid amount (minimum Rs. 100)', 'error');
                    return false;
                }
                
                if (amount > maxAmount) {
                    e.preventDefault();
                    showToast('Insufficient available balance!', 'error');
                    return false;
                }
                
                // Show confirmation dialog for large amounts
                if (amount > 10000) {
                    if (!confirm(`You are about to withdraw Rs. ${amount.toFixed(2)}. Continue?`)) {
                        e.preventDefault();
                        return false;
                    }
                }
            });
            
            // Toast notification function
            function showToast(message, type = 'info') {
                // Remove existing toast
                const existingToast = document.getElementById('custom-toast');
                if (existingToast) {
                    existingToast.remove();
                }
                
                // Create toast element
                const toast = document.createElement('div');
                toast.id = 'custom-toast';
                toast.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-y-0 opacity-100`;
                
                // Set styles based on type
                if (type === 'error') {
                    toast.className += ' bg-red-50 border-l-4 border-red-500 text-red-800';
                } else if (type === 'warning') {
                    toast.className += ' bg-amber-50 border-l-4 border-amber-500 text-amber-800';
                } else {
                    toast.className += ' bg-pink-50 border-l-4 border-[#E85D85] text-[#C94A72]';
                }
                
                toast.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation-circle' : 'info-circle'} text-xl mr-3"></i>
                        <div class="flex-1 font-medium">${message}</div>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-20px)';
                        setTimeout(() => {
                            if (toast.parentNode) {
                                toast.remove();
                            }
                        }, 300);
                    }
                }, 5000);
            }
            
            // Make showToast globally available
            window.showToast = showToast;
        });
    </script>
    
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <x-vendor.mobile-nav />
</body>
</html>