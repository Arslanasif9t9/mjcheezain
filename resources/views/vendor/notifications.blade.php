<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications | Multivendor Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-item.active {
            background-color: #fdf2f8;
            border-right: 3px solid #E85D85;
            color: #E85D85;
        }
        
        /* Notification animations */
        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .notification-item {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Custom checkbox style */
        input[type="checkbox"]:checked + .checkmark {
            background-color: #E85D85;
            border-color: #E85D85;
        }
        
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
            left: 5px;
            top: 1px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        input[type="checkbox"]:checked + .checkmark:after {
            display: block;
        }
    </style>
</head>
<body class="min-h-screen" style="background-color: #FFF6F0;">
    <div class="flex min-h-screen">
        <!-- Sidebar Component -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? $user->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            :user="$user"
            page='Notifications'
        />
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0">
            <x-vendor.app-header title="Notifications" subtitle="Stay up to date with your store" />

            <!-- Top Navigation (desktop only) -->
            <header class="hidden md:flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Notifications</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('vendor.notifications') }}" class="p-2 text-gray-500 rounded-full hover:bg-gray-100 relative">
                        <i class="fas fa-bell"></i>
                        @php
                            $unreadCount = DB::table('notifications')
                                ->where('user_id', Auth::id())
                                ->where('is_read', 0)
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </a>
                    {{-- <button class="p-2 text-gray-500 rounded-full hover:bg-gray-100">
                        <i class="fas fa-search"></i>
                    </button> --}}
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center focus:outline-none">
                            <span class="mr-2 text-sm font-medium text-gray-700">{{ $basic_info->name ?? Auth::user()->name }}</span>
                            <img class="w-8 h-8 rounded-full" src="{{ $basic_info->profile_picture ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="User">
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6 pb-28 md:pb-8 page-enter min-w-0" style="background-color: #FFF6F0;">
                <div class="max-w-4xl mx-auto">
                    <!-- Notification Header (desktop only) -->
                    <div class="hidden md:flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Your Notifications</h2>
                        <div class="flex items-center space-x-2">
                            {{-- <button class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button> --}}
                        </div>
                    </div>

                    @php
                        // Brand theme mapping: DB-stored color classes may contain blue variants.
                        // Rewrite them to brand pink equivalents before echoing (view-only fix, no DB changes).
                        $brandizeClasses = function ($classes) {
                            return str_replace(
                                ['bg-blue-100', 'bg-blue-500', 'bg-blue-50', 'text-blue-800', 'text-blue-600', 'text-blue-500'],
                                ['bg-pink-100', 'bg-[#FF7DA0]', 'bg-pink-50', 'text-[#C94A72]', 'text-[#E85D85]', 'text-[#E85D85]'],
                                (string) $classes
                            );
                        };
                    @endphp

                    <!-- Notification List -->
                    <div class="app-card overflow-hidden -mt-2 md:mt-0 relative z-10">
                        @forelse($notifications as $dateGroup => $groupedNotifications)
                            <div class="@if(!$loop->last) border-b border-pink-100 @endif">
                                <div class="px-4 py-2.5 brand-gradient-soft">
                                    <h3 class="text-xs font-bold text-[#E85D85] uppercase tracking-wide">{{ $dateGroup }}</h3>
                                </div>

                                <div class="divide-y divide-pink-50">
                                    @foreach($groupedNotifications as $notification)
                                        <div class="notification-item flex items-start px-4 py-3.5 hover:bg-pink-50/50 transition-colors {{ $notification->is_read ? '' : 'bg-pink-50' }}"
                                             data-notification-id="{{ $notification->id }}">
                                            {{-- <label class="inline-flex items-center mt-1 mr-3">
                                                <input type="checkbox" 
                                                       class="notification-checkbox form-checkbox h-4 w-4 text-[#E85D85] sr-only" 
                                                       {{ $notification->is_read ? 'checked' : '' }}>
                                                <span class="checkmark h-4 w-4 border border-gray-300 rounded-sm flex-shrink-0"></span>
                                            </label> --}}
                                            <div class="flex-shrink-0 mr-3">
                                                <div class="p-2 rounded-full {!! $brandizeClasses($notification->icon_color) !!}">
                                                    <i class="{{ $notification->icon_class }}"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                                <p class="text-sm text-gray-500 mt-1">{{ $notification->message }}</p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="ml-4 flex-shrink-0">
                                                <span class="w-2 h-2 rounded-full {{ $notification->is_read ? 'bg-gray-300' : $brandizeClasses($notification->dot_color) }}"></span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-12 text-center">
                                <div class="w-16 h-16 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-4">
                                    <i class="fas fa-bell-slash text-2xl text-[#E85D85]"></i>
                                </div>
                                <p class="text-gray-600 font-semibold">No notifications yet</p>
                                <p class="text-sm text-gray-400 mt-1">We'll notify you when something arrives</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Load More Button -->
                    @if($notifications->count() > 0)
                    <div class="mt-6 text-center">
                        <button class="px-5 py-2.5 bg-white border border-pink-200 rounded-full md:rounded-md text-sm font-semibold text-[#E85D85] md:text-gray-700 md:border-gray-300 hover:bg-pink-50 md:hover:bg-gray-50 active:scale-95 transition-transform">
                            Load More Notifications
                        </button>
                    </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <x-logout-modal />

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.querySelector('.hidden.md\\:flex');
            if (sidebar) {
                sidebar.classList.toggle('hidden');
            }
        }
        
        // Mark notification as read when clicked
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking on checkbox
                    if (e.target.type === 'checkbox' || e.target.classList.contains('checkmark')) {
                        return;
                    }
                    
                    const notificationId = this.dataset.notificationId;
                    const checkbox = this.querySelector('.notification-checkbox');
                    const dot = this.querySelector('.rounded-full:last-child');

                    // If already read, do nothing
                    if (checkbox && checkbox.checked) return;

                    // Mark as read visually
                    if (checkbox) checkbox.checked = true;
                    if (dot) {
                        dot.classList.remove('bg-pink-500', 'bg-[#FF7DA0]', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500');
                        dot.classList.add('bg-gray-300');
                    }
                    this.classList.remove('bg-pink-50');

                    // Mark as read in database
                    fetch(`/vendor/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                });
            });
            
            // Handle checkbox clicks
            document.querySelectorAll('.checkmark').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const checkbox = this.previousElementSibling;
                    const notificationId = this.closest('.notification-item').dataset.notificationId;
                    const dot = this.closest('.notification-item').querySelector('.rounded-full:last-child');
                    
                    checkbox.checked = !checkbox.checked;
                    
                    if (checkbox.checked) {
                        // Mark as read
                        dot.classList.remove('bg-pink-500', 'bg-[#FF7DA0]', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500');
                        dot.classList.add('bg-gray-300');
                        
                        fetch(`/vendor/notifications/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                    } else {
                        // Mark as unread
                        const iconDiv = this.closest('.notification-item').querySelector('.rounded-full:first-child');
                        const iconClass = iconDiv.querySelector('i').className;
                        
                        // Determine dot color based on icon type
                        if (iconClass.includes('fa-shipping-fast') || iconClass.includes('fa-comment-alt')) {
                            dot.classList.add('bg-pink-500');
                        } else if (iconClass.includes('fa-check-circle') || iconClass.includes('fa-thumbs-up')) {
                            dot.classList.add('bg-green-500');
                        } else if (iconClass.includes('fa-exclamation-circle')) {
                            dot.classList.add('bg-yellow-500');
                        } else if (iconClass.includes('fa-gift')) {
                            dot.classList.add('bg-purple-500');
                        } else if (iconClass.includes('fa-times-circle') || iconClass.includes('fa-tag')) {
                            dot.classList.add('bg-red-500');
                        }
                    }
                });
            });


            fetch(`/customer/notifications/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                // if (!response.ok) {
                //     throw new Error(`HTTP error! status: ${response.status}`);
                // }
                return response.json(); // or response.text() if not JSON
            })
            .then(data => {
                console.log('Successfully read notifications', data);
                const notiNum = document.getElementById('noti-num');
                if (notiNum) notiNum.style.display = 'none';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
    <x-vendor.mobile-nav />
</body>
</html>