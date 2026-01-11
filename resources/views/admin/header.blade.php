<!-- Header -->
<header class="bg-white shadow-sm border-b border-gray-200 lg:ml-64">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Mobile menu button -->
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <!-- Page title -->
        <div class="flex-1 lg:ml-0 ml-4">
            <h1 class="text-xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
        </div>

        <!-- User menu -->
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown">
                    <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/32" alt="User avatar">
                    <span class="ml-2 text-gray-700 text-sm font-medium hidden sm:block">{{ $admin->username ?? 'Admin' }}</span>
                    <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                </button>
                <!-- Dropdown menu -->
                <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" tabindex="-1" id="user-dropdown">
                    <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
                    <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
