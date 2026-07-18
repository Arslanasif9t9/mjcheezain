<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Details | Vendor Panel</title>
    
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
                        },
                        danger: {
                            50: '#fef2f2',
                            500: '#ef4444',
                            600: '#dc2626',
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
            :user="$user ?? null"
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name ?? null"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            page='Balance Details'
        />
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0 transition-all duration-300">
            <x-vendor.app-header title="Balance Details" subtitle="Earnings, withdrawals & transactions" :back="route('vendor.withdraw')" />

            <main class="flex-1 p-4 md:p-6 lg:p-8 pb-28 md:pb-8 page-enter">
            <!-- Page Header (desktop only) -->
            <div class="hidden md:flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 pb-6 border-b border-gray-200">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-chart-line text-primary-500 mr-3"></i>
                        Balance Details
                    </h1>
                    <p class="text-gray-600 mt-2">Track your earnings, withdrawals, and transaction history</p>
                </div>
                <a href="{{ route('vendor.withdraw') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Withdrawal
                </a>
            </div>
            
            <!-- Balance Overview -->
            <div class="brand-gradient brand-shadow text-white rounded-3xl md:rounded-2xl shadow-lg p-5 md:p-6 lg:p-8 mb-6 md:mb-8 relative overflow-hidden -mt-2 md:mt-0 z-10">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
                <div class="absolute bottom-0 -left-8 w-24 h-24 bg-white/10 rounded-full pointer-events-none"></div>
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center relative z-10">
                    <div class="mb-5 md:mb-6 lg:mb-0 lg:mr-8">
                        <div class="flex items-center mb-3 md:mb-4">
                            <div class="p-2.5 md:p-3 bg-white/20 rounded-xl mr-3 md:mr-4 backdrop-blur-sm">
                                <i class="fas fa-wallet text-base md:text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-[11px] md:text-lg font-semibold text-white/90 uppercase md:normal-case tracking-widest md:tracking-normal">Total Balance</h2>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="text-[34px] md:text-4xl lg:text-5xl font-extrabold md:font-bold tracking-tight leading-tight">Rs. {{ number_format($balance->total_balance, 2) }}</div>
                            <p class="text-white/80 mt-1.5 md:mt-2 text-xs md:text-base">Your total earnings from all completed orders</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 md:gap-6 w-full lg:w-auto">
                        <div class="bg-white/20 p-3.5 md:p-4 rounded-2xl md:rounded-xl backdrop-blur-sm">
                            <div class="flex items-center">
                                <div class="p-2 bg-white/30 rounded-lg mr-3 flex-shrink-0">
                                    <i class="fas fa-check-circle text-sm md:text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-base md:text-2xl font-bold truncate">Rs. {{ number_format($balance->available_balance, 2) }}</div>
                                    <div class="text-white/90 text-[11px] md:text-sm">Available</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/20 p-3.5 md:p-4 rounded-2xl md:rounded-xl backdrop-blur-sm">
                            <div class="flex items-center">
                                <div class="p-2 bg-white/30 rounded-lg mr-3 flex-shrink-0">
                                    <i class="fas fa-clock text-sm md:text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-base md:text-2xl font-bold truncate">Rs. {{ number_format($balance->pending_balance, 2) }}</div>
                                    <div class="text-white/90 text-[11px] md:text-sm">Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance Breakdown -->
                <div class="mt-6 pt-6 md:mt-8 md:pt-8 border-t border-white/20 relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-white/90">Available Balance</span>
                                <span class="text-xl font-bold text-pink-100">Rs. {{ number_format($balance->available_balance, 2) }}</span>
                            </div>
                            <div class="w-full bg-white/30 rounded-full h-2.5">
                                <div class="bg-pink-200 h-2.5 rounded-full" 
                                     style="width: {{ $balance->total_balance > 0 ? ($balance->available_balance / $balance->total_balance) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-white/90">Pending Balance</span>
                                <span class="text-xl font-bold text-amber-200">Rs. {{ number_format($balance->pending_balance, 2) }}</span>
                            </div>
                            <div class="w-full bg-white/30 rounded-full h-2.5">
                                <div class="bg-amber-200 h-2.5 rounded-full" 
                                     style="width: {{ $balance->total_balance > 0 ? ($balance->pending_balance / $balance->total_balance) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="mb-4 md:mb-6">
                <div class="border-b border-pink-100 md:border-gray-200">
                    <nav class="flex flex-wrap -mb-px">
                        <button id="transactions-tab"
                                class="mr-2 md:mr-4 py-3 px-3 md:px-4 font-semibold text-xs md:text-sm whitespace-nowrap border-b-2 border-primary-500 text-primary-600 bg-primary-50 rounded-t-lg">
                            <i class="fas fa-exchange-alt mr-1.5 md:mr-2"></i>
                            Transaction History
                        </button>
                        <button id="withdrawals-tab"
                                class="mr-2 md:mr-4 py-3 px-3 md:px-4 font-semibold text-xs md:text-sm whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 rounded-t-lg">
                            <i class="fas fa-university mr-1.5 md:mr-2"></i>
                            Withdrawal History
                        </button>
                    </nav>
                </div>
            </div>
            
            <!-- Tab Content -->
            <div id="transactions-content" class="tab-content">
                <div class="app-card md:bg-white md:rounded-2xl md:shadow-card overflow-hidden md:border md:border-gray-100">
                    <div class="p-4 md:px-6 md:py-5 border-b border-pink-50 md:border-gray-100">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                            <div class="flex items-center mb-3 lg:mb-0">
                                <i class="fas fa-exchange-alt text-primary-500 text-base md:text-xl mr-3"></i>
                                <h3 class="text-base md:text-lg font-bold text-gray-800">Recent Transactions</h3>
                            </div>
                            <div class="flex items-center gap-2 md:gap-4 flex-wrap">
                                <select class="px-3 md:px-4 py-2 border border-pink-100 md:border-gray-300 rounded-xl md:rounded-lg text-xs md:text-sm bg-white focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85]">
                                    <option>All Types</option>
                                    <option>Credit</option>
                                    <option>Debit</option>
                                    <option>Withdrawal</option>
                                </select>
                                <select class="px-3 md:px-4 py-2 border border-pink-100 md:border-gray-300 rounded-xl md:rounded-lg text-xs md:text-sm bg-white focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85]">
                                    <option>Last 30 days</option>
                                    <option>Last 7 days</option>
                                    <option>Last 3 months</option>
                                    <option>All time</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile transaction cards -->
                    <div class="md:hidden divide-y divide-pink-50">
                        @forelse($transactions as $transaction)
                            @php
                                $isIn = in_array($transaction->transaction_type, ['credit', 'release']);
                                $mTypeClasses = [
                                    'credit' => 'bg-green-100 text-green-700',
                                    'debit' => 'bg-red-100 text-red-700',
                                    'withdrawal' => 'bg-pink-100 text-[#C94A72]',
                                    'hold' => 'bg-amber-100 text-amber-700',
                                    'release' => 'bg-purple-100 text-purple-700',
                                ];
                                $mTypeClass = $mTypeClasses[$transaction->transaction_type] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mr-3 flex-shrink-0 {{ $isIn ? 'bg-green-50' : 'bg-red-50' }}">
                                            <i class="fas {{ $isIn ? 'fa-arrow-down text-green-500' : 'fa-arrow-up text-red-500' }} text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-800 truncate">{{ $transaction->description ?: ucfirst($transaction->transaction_type) }}</p>
                                            <p class="text-[11px] text-gray-400">{{ date('d M Y', strtotime($transaction->transaction_date)) }} · {{ date('h:i A', strtotime($transaction->transaction_date)) }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right ml-2 flex-shrink-0">
                                        <p class="text-sm font-extrabold {{ $isIn ? 'text-green-600' : 'text-red-600' }}">{{ $isIn ? '+' : '-' }}Rs. {{ number_format($transaction->amount, 2) }}</p>
                                        <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $mTypeClass }}">{{ ucfirst($transaction->transaction_type) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-pink-50 text-[11px] text-gray-400">
                                    <span>Balance: Rs. {{ number_format($transaction->previous_balance, 2) }} <i class="fas fa-arrow-right text-[8px] mx-0.5"></i> <span class="font-semibold text-gray-600">Rs. {{ number_format($transaction->new_balance, 2) }}</span></span>
                                    @if($transaction->reference_id)
                                        <span class="truncate ml-2">Ref: {{ $transaction->reference_id }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center px-4">
                                <div class="w-16 h-16 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-3">
                                    <i class="fas fa-exchange-alt text-2xl text-[#E85D85]"></i>
                                </div>
                                <p class="text-gray-600 font-semibold">No transactions found</p>
                                <p class="text-sm text-gray-400 mt-1">Your transaction history will appear here</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full min-w-[800px]">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date & Time</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Previous Balance</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">New Balance</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ date('d M Y', strtotime($transaction->transaction_date)) }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ date('h:i A', strtotime($transaction->transaction_date)) }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @php
                                                $typeClasses = [
                                                    'credit' => 'bg-green-100 text-green-800',
                                                    'debit' => 'bg-red-100 text-red-800',
                                                    'withdrawal' => 'bg-pink-100 text-[#C94A72]',
                                                    'hold' => 'bg-amber-100 text-amber-800',
                                                    'release' => 'bg-purple-100 text-purple-800',
                                                ];
                                                $typeClass = $typeClasses[$transaction->transaction_type] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $typeClass }}">
                                                <i class="fas fa-{{ $transaction->transaction_type == 'credit' ? 'plus' : ($transaction->transaction_type == 'debit' ? 'minus' : 'exchange-alt') }} mr-1"></i>
                                                {{ ucfirst($transaction->transaction_type) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-lg font-semibold {{ in_array($transaction->transaction_type, ['credit', 'release']) ? 'text-green-600' : 'text-red-600' }}">
                                                {{ in_array($transaction->transaction_type, ['credit', 'release']) ? '+' : '-' }}Rs. {{ number_format($transaction->amount, 2) }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm text-gray-700">Rs. {{ number_format($transaction->previous_balance, 2) }}</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">Rs. {{ number_format($transaction->new_balance, 2) }}</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm text-gray-700">{{ $transaction->description }}</div>
                                            @if($transaction->reference_id)
                                                <div class="text-xs text-gray-500 mt-1">Ref: {{ $transaction->reference_id }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-exchange-alt text-4xl mb-4 text-gray-300"></i>
                                                <p class="text-lg font-medium">No transactions found</p>
                                                <p class="text-sm mt-2">Your transaction history will appear here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($transactions->hasPages())
                        <div class="px-4 md:px-6 py-4 border-t border-pink-50 md:border-gray-100">
                            <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                                <div class="text-xs md:text-sm text-gray-500">
                                    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} results
                                </div>
                                <div class="flex space-x-2">
                                    @if($transactions->onFirstPage())
                                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Previous</span>
                                    @else
                                        <a href="{{ $transactions->previousPageUrl() }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Previous</a>
                                    @endif
                                    
                                    @foreach(range(1, min(5, $transactions->lastPage())) as $page)
                                        <a href="{{ $transactions->url($page) }}" 
                                           class="px-3 py-1 rounded-lg {{ $transactions->currentPage() == $page ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach
                                    
                                    @if($transactions->hasMorePages())
                                        <a href="{{ $transactions->nextPageUrl() }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Next</a>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Next</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div id="withdrawals-content" class="tab-content hidden">
                <div class="app-card md:bg-white md:rounded-2xl md:shadow-card overflow-hidden md:border md:border-gray-100">
                    <div class="p-4 md:px-6 md:py-5 border-b border-pink-50 md:border-gray-100">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                            <div class="flex items-center mb-3 lg:mb-0">
                                <i class="fas fa-university text-primary-500 text-base md:text-xl mr-3"></i>
                                <h3 class="text-base md:text-lg font-bold text-gray-800">Withdrawal History</h3>
                            </div>
                            <div class="flex items-center gap-2 md:gap-4 flex-wrap">
                                <select class="px-3 md:px-4 py-2 border border-pink-100 md:border-gray-300 rounded-xl md:rounded-lg text-xs md:text-sm bg-white focus:ring-2 focus:ring-[#E85D85]/40 focus:border-[#E85D85]">
                                    <option>All Status</option>
                                    <option>Pending</option>
                                    <option>Approved</option>
                                    <option>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile withdrawal cards -->
                    <div class="md:hidden divide-y divide-pink-50">
                        @forelse($withdrawals as $withdrawal)
                            @php
                                $mStatusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                    'processing' => 'bg-pink-100 text-[#C94A72]',
                                ];
                                $mStatusClass = $mStatusClasses[$withdrawal->status] ?? 'bg-gray-100 text-gray-700';
                                $mStatusIcon = [
                                    'pending' => 'fa-clock',
                                    'approved' => 'fa-check-circle',
                                    'rejected' => 'fa-times-circle',
                                ][$withdrawal->status] ?? 'fa-spinner';
                            @endphp
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <div class="w-10 h-10 rounded-xl brand-gradient-soft flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-university text-[#E85D85] text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-800 truncate">{{ $withdrawal->bank_name }}</p>
                                            <p class="text-[11px] text-gray-400">Acc: {{ substr($withdrawal->account_number, 0, 4) }}***{{ substr($withdrawal->account_number, -4) }} · {{ $withdrawal->ifsc_code }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right ml-2 flex-shrink-0">
                                        <p class="text-sm font-extrabold text-gray-800">Rs. {{ number_format($withdrawal->amount, 2) }}</p>
                                        <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $mStatusClass }}">
                                            <i class="fas {{ $mStatusIcon }} mr-1 text-[9px]"></i>{{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between mt-2.5 pt-2.5 border-t border-pink-50 text-[11px] text-gray-400">
                                    <span>Requested {{ date('d M Y, h:i A', strtotime($withdrawal->requested_at)) }}</span>
                                    @if($withdrawal->processed_at)
                                        <span class="ml-2">Processed {{ date('d M Y', strtotime($withdrawal->processed_at)) }}</span>
                                    @endif
                                </div>
                                @if($withdrawal->transaction_id || $withdrawal->notes || $withdrawal->admin_notes)
                                    <div class="mt-1.5 text-[11px] text-gray-400 space-y-0.5">
                                        @if($withdrawal->transaction_id)
                                            <p>Txn ID: {{ $withdrawal->transaction_id }}</p>
                                        @endif
                                        @if($withdrawal->notes)
                                            <p class="text-gray-500">{{ $withdrawal->notes }}</p>
                                        @endif
                                        @if($withdrawal->admin_notes)
                                            <p>Admin: {{ $withdrawal->admin_notes }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="py-12 text-center px-4">
                                <div class="w-16 h-16 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-3">
                                    <i class="fas fa-university text-2xl text-[#E85D85]"></i>
                                </div>
                                <p class="text-gray-600 font-semibold">No withdrawal requests found</p>
                                <p class="text-sm text-gray-400 mt-1">Your withdrawal history will appear here</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full min-w-[800px]">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Request Date</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bank Details</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Processed Date</th>
                                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($withdrawals as $withdrawal)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ date('d M Y', strtotime($withdrawal->requested_at)) }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ date('h:i A', strtotime($withdrawal->requested_at)) }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-lg font-bold text-gray-900">Rs. {{ number_format($withdrawal->amount, 2) }}</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">{{ $withdrawal->bank_name }}</div>
                                            <div class="text-sm text-gray-600 mt-1">Acc: {{ substr($withdrawal->account_number, 0, 4) }}***{{ substr($withdrawal->account_number, -4) }}</div>
                                            <div class="text-xs text-gray-500 mt-1">{{ $withdrawal->ifsc_code }}</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'bg-amber-100 text-amber-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                    'processing' => 'bg-pink-100 text-[#C94A72]',
                                                ];
                                                $statusClass = $statusClasses[$withdrawal->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                @if($withdrawal->status == 'pending')
                                                    <i class="fas fa-clock mr-1"></i>
                                                @elseif($withdrawal->status == 'approved')
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                @elseif($withdrawal->status == 'rejected')
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                @else
                                                    <i class="fas fa-spinner mr-1"></i>
                                                @endif
                                                {{ ucfirst($withdrawal->status) }}
                                            </span>
                                            @if($withdrawal->transaction_id)
                                                <div class="text-xs text-gray-500 mt-2">Txn ID: {{ $withdrawal->transaction_id }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($withdrawal->processed_at)
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ date('d M Y', strtotime($withdrawal->processed_at)) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ date('h:i A', strtotime($withdrawal->processed_at)) }}
                                                </div>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm text-gray-700 max-w-xs">
                                                {{ $withdrawal->notes ?: 'No notes' }}
                                            </div>
                                            @if($withdrawal->admin_notes)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Admin: {{ $withdrawal->admin_notes }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-university text-4xl mb-4 text-gray-300"></i>
                                                <p class="text-lg font-medium">No withdrawal requests found</p>
                                                <p class="text-sm mt-2">Your withdrawal history will appear here</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($withdrawals->hasPages())
                        <div class="px-4 md:px-6 py-4 border-t border-pink-50 md:border-gray-100">
                            <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                                <div class="text-xs md:text-sm text-gray-500">
                                    Showing {{ $withdrawals->firstItem() }} to {{ $withdrawals->lastItem() }} of {{ $withdrawals->total() }} results
                                </div>
                                <div class="flex space-x-2">
                                    @if($withdrawals->onFirstPage())
                                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Previous</span>
                                    @else
                                        <a href="{{ $withdrawals->previousPageUrl() }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Previous</a>
                                    @endif
                                    
                                    @foreach(range(1, min(5, $withdrawals->lastPage())) as $page)
                                        <a href="{{ $withdrawals->url($page) }}" 
                                           class="px-3 py-1 rounded-lg {{ $withdrawals->currentPage() == $page ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach
                                    
                                    @if($withdrawals->hasMorePages())
                                        <a href="{{ $withdrawals->nextPageUrl() }}" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Next</a>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Next</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Summary Cards — mobile compact stats -->
            <div class="md:hidden grid grid-cols-3 gap-3 mt-5">
                <div class="app-card p-3 text-center">
                    <div class="w-9 h-9 mx-auto rounded-xl brand-gradient flex items-center justify-center brand-shadow">
                        <i class="fas fa-money-bill-wave text-white text-xs"></i>
                    </div>
                    <p class="text-sm font-extrabold text-gray-800 mt-2 leading-none truncate">Rs. {{ number_format($balance->total_balance + ($withdrawals->sum('amount') ?? 0), 0) }}</p>
                    <p class="text-[10px] text-gray-500 font-medium mt-1">Earnings</p>
                </div>
                <div class="app-card p-3 text-center">
                    <div class="w-9 h-9 mx-auto rounded-xl bg-gradient-to-br from-emerald-400 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-200">
                        <i class="fas fa-hand-holding-usd text-white text-xs"></i>
                    </div>
                    <p class="text-sm font-extrabold text-gray-800 mt-2 leading-none truncate">Rs. {{ number_format($withdrawals->where('status', 'approved')->sum('amount'), 0) }}</p>
                    <p class="text-[10px] text-gray-500 font-medium mt-1">Withdrawn</p>
                </div>
                <div class="app-card p-3 text-center">
                    <div class="w-9 h-9 mx-auto rounded-xl bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center shadow-lg shadow-purple-200">
                        <i class="fas fa-hourglass-half text-white text-xs"></i>
                    </div>
                    <p class="text-sm font-extrabold text-gray-800 mt-2 leading-none truncate">Rs. {{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 0) }}</p>
                    <p class="text-[10px] text-gray-500 font-medium mt-1">Pending</p>
                </div>
            </div>

            <!-- Summary Cards (desktop) -->
            <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-gradient-to-r from-[#FF7DA0] to-[#FFC275] text-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-pink-100 text-sm">Total Earnings</p>
                            <p class="text-2xl font-bold">Rs. {{ number_format($balance->total_balance + ($withdrawals->sum('amount') ?? 0), 2) }}</p>
                        </div>
                        <i class="fas fa-money-bill-wave text-2xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Total Withdrawals</p>
                            <p class="text-2xl font-bold">Rs. {{ number_format($withdrawals->where('status', 'approved')->sum('amount'), 2) }}</p>
                        </div>
                        <i class="fas fa-hand-holding-usd text-2xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Pending Withdrawals</p>
                            <p class="text-2xl font-bold">Rs. {{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 2) }}</p>
                        </div>
                        <i class="fas fa-hourglass-half text-2xl opacity-80"></i>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const transactionsTab = document.getElementById('transactions-tab');
            const withdrawalsTab = document.getElementById('withdrawals-tab');
            const transactionsContent = document.getElementById('transactions-content');
            const withdrawalsContent = document.getElementById('withdrawals-content');
            
            // Set initial state
            updateTabUI('transactions');
            
            // Tab click events
            transactionsTab.addEventListener('click', () => {
                updateTabUI('transactions');
            });
            
            withdrawalsTab.addEventListener('click', () => {
                updateTabUI('withdrawals');
            });
            
            function updateTabUI(activeTab) {
                // Reset all tabs
                transactionsTab.classList.remove('border-primary-500', 'text-primary-600', 'bg-primary-50');
                transactionsTab.classList.add('border-transparent', 'text-gray-500');
                
                withdrawalsTab.classList.remove('border-primary-500', 'text-primary-600', 'bg-primary-50');
                withdrawalsTab.classList.add('border-transparent', 'text-gray-500');
                
                // Hide all content
                transactionsContent.classList.remove('hidden');
                withdrawalsContent.classList.add('hidden');
                
                // Activate selected tab
                if (activeTab === 'transactions') {
                    transactionsTab.classList.remove('border-transparent', 'text-gray-500');
                    transactionsTab.classList.add('border-primary-500', 'text-primary-600', 'bg-primary-50');
                    transactionsContent.classList.remove('hidden');
                } else {
                    withdrawalsTab.classList.remove('border-transparent', 'text-gray-500');
                    withdrawalsTab.classList.add('border-primary-500', 'text-primary-600', 'bg-primary-50');
                    transactionsContent.classList.add('hidden');
                    withdrawalsContent.classList.remove('hidden');
                }
            }
            
            // Status filter functionality
            const statusFilters = document.querySelectorAll('select');
            statusFilters.forEach(select => {
                select.addEventListener('change', function() {
                    // Add loading state
                    const tableBody = this.closest('.tab-content').querySelector('tbody');
                    const originalContent = tableBody.innerHTML;
                    
                    // Show loading
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-spinner fa-spin text-2xl text-primary-500 mb-3"></i>
                                    <p class="text-gray-600">Loading filtered data...</p>
                                </div>
                            </td>
                        </tr>
                    `;
                    
                    // Simulate API call delay
                    setTimeout(() => {
                        tableBody.innerHTML = originalContent;
                    }, 500);
                });
            });
        });
    </script>
    
    <style>
        .tab-content {
            transition: opacity 0.3s ease-in-out;
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
        
        /* Table row hover animation */
        tbody tr {
            transition: all 0.2s ease;
        }
        
        tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
    <x-vendor.mobile-nav />
</body>
</html>