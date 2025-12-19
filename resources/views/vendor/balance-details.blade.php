<!DOCTYPE html>
<html lang="en">
<head>
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
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
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
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name ?? null"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            page='Balance Details'
        />
        
        <!-- Main Content -->
        <div class="flex-1 ml-0 lg:ml72 p-4 lg:p-8 transition-all duration-300">
            <!-- Page Header -->
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 pb-6 border-b border-gray-200">
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
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl shadow-lg p-6 lg:p-8 mb-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                    <div class="mb-6 lg:mb-0 lg:mr-8">
                        <div class="flex items-center mb-4">
                            <div class="p-3 bg-white/20 rounded-xl mr-4">
                                <i class="fas fa-wallet text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-white/90">Total Balance</h2>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="text-4xl lg:text-5xl font-bold">₹{{ number_format($balance->total_balance, 2) }}</div>
                            <p class="text-white/80 mt-2">Your total earnings from all completed orders</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-white/30 rounded-lg mr-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">₹{{ number_format($balance->available_balance, 2) }}</div>
                                    <div class="text-white/90 text-sm">Available</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white/20 p-4 rounded-xl backdrop-blur-sm">
                            <div class="flex items-center mb-3">
                                <div class="p-2 bg-white/30 rounded-lg mr-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">₹{{ number_format($balance->pending_balance, 2) }}</div>
                                    <div class="text-white/90 text-sm">Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Balance Breakdown -->
                <div class="mt-8 pt-8 border-t border-white/20">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-white/90">Available Balance</span>
                                <span class="text-xl font-bold text-blue-200">₹{{ number_format($balance->available_balance, 2) }}</span>
                            </div>
                            <div class="w-full bg-white/30 rounded-full h-2.5">
                                <div class="bg-blue-200 h-2.5 rounded-full" 
                                     style="width: {{ $balance->total_balance > 0 ? ($balance->available_balance / $balance->total_balance) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-white/90">Pending Balance</span>
                                <span class="text-xl font-bold text-amber-200">₹{{ number_format($balance->pending_balance, 2) }}</span>
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
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex flex-wrap -mb-px">
                        <button id="transactions-tab" 
                                class="mr-4 py-3 px-4 font-medium text-sm border-b-2 border-primary-500 text-primary-600 bg-primary-50 rounded-t-lg">
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Transaction History
                        </button>
                        <button id="withdrawals-tab" 
                                class="mr-4 py-3 px-4 font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-university mr-2"></i>
                            Withdrawal History
                        </button>
                    </nav>
                </div>
            </div>
            
            <!-- Tab Content -->
            <div id="transactions-content" class="tab-content">
                <div class="bg-white rounded-2xl shadow-card overflow-hidden border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                            <div class="flex items-center mb-4 lg:mb-0">
                                <i class="fas fa-exchange-alt text-primary-500 text-xl mr-3"></i>
                                <h3 class="text-lg font-bold text-gray-800">Recent Transactions</h3>
                            </div>
                            <div class="flex items-center space-x-4">
                                <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option>All Types</option>
                                    <option>Credit</option>
                                    <option>Debit</option>
                                    <option>Withdrawal</option>
                                </select>
                                <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option>Last 30 days</option>
                                    <option>Last 7 days</option>
                                    <option>Last 3 months</option>
                                    <option>All time</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
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
                                                    'withdrawal' => 'bg-blue-100 text-blue-800',
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
                                                {{ in_array($transaction->transaction_type, ['credit', 'release']) ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm text-gray-700">₹{{ number_format($transaction->previous_balance, 2) }}</div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($transaction->new_balance, 2) }}</div>
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
                        <div class="px-6 py-4 border-t border-gray-100">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
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
                <div class="bg-white rounded-2xl shadow-card overflow-hidden border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center">
                            <div class="flex items-center mb-4 lg:mb-0">
                                <i class="fas fa-university text-primary-500 text-xl mr-3"></i>
                                <h3 class="text-lg font-bold text-gray-800">Withdrawal History</h3>
                            </div>
                            <div class="flex items-center space-x-4">
                                <select class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option>All Status</option>
                                    <option>Pending</option>
                                    <option>Approved</option>
                                    <option>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
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
                                            <div class="text-lg font-bold text-gray-900">₹{{ number_format($withdrawal->amount, 2) }}</div>
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
                                                    'processing' => 'bg-blue-100 text-blue-800',
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
                        <div class="px-6 py-4 border-t border-gray-100">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
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
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Earnings</p>
                            <p class="text-2xl font-bold">₹{{ number_format($balance->total_balance + ($withdrawals->sum('amount') ?? 0), 2) }}</p>
                        </div>
                        <i class="fas fa-money-bill-wave text-2xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Total Withdrawals</p>
                            <p class="text-2xl font-bold">₹{{ number_format($withdrawals->where('status', 'approved')->sum('amount'), 2) }}</p>
                        </div>
                        <i class="fas fa-hand-holding-usd text-2xl opacity-80"></i>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Pending Withdrawals</p>
                            <p class="text-2xl font-bold">₹{{ number_format($withdrawals->where('status', 'pending')->sum('amount'), 2) }}</p>
                        </div>
                        <i class="fas fa-hourglass-half text-2xl opacity-80"></i>
                    </div>
                </div>
            </div>
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
</body>
</html>