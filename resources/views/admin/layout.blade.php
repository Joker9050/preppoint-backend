<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PrepPoint Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-indigo-800 to-indigo-900 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl">
        <!-- Logo/Brand -->
        <div class="flex items-center justify-center h-16 bg-indigo-900 border-b border-indigo-700">
            <h1 class="text-xl font-bold text-white">PrepPoint Admin</h1>
        </div>

        <!-- Navigation -->
        <nav class="mt-8 px-4">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-700 text-white' : '' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Content Management -->
            <div class="mb-4">
                <h3 class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Content Management</h3>

                <!-- Subjects -->
                <a href="{{ route('admin.subjects.index') }}" class="flex items-center py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 mb-1 {{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-700 text-white' : '' }}">
                    <i class="fas fa-book mr-3"></i>
                    <span>Subjects</span>
                </a>

                <!-- Topics -->
                <a href="{{ route('admin.topics.index') }}" class="flex items-center py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 mb-1 {{ request()->routeIs('admin.topics.*') ? 'bg-indigo-700 text-white' : '' }}">
                    <i class="fas fa-list mr-3"></i>
                    <span>Topics</span>
                </a>

                <!-- MCQs -->
                <a href="{{ route('admin.mcqs.index') }}" class="flex items-center py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 mb-1 {{ request()->routeIs('admin.mcqs.*') ? 'bg-indigo-700 text-white' : '' }}">
                    <i class="fas fa-question-circle mr-3"></i>
                    <span>MCQs</span>
                </a>

                <!-- Mock Tests Dropdown -->
                <div class="mb-1">
                    <button onclick="toggleMockDropdown()" class="flex items-center justify-between w-full py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.mock-exams.*') || request()->routeIs('admin.mock-exam-papers.*') ? 'bg-indigo-700 text-white' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-clipboard-list mr-3"></i>
                            <span>Mock Tests</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform duration-200" id="mock-chevron"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="hidden ml-4 mt-1 space-y-1" id="mock-dropdown">
                        <a href="{{ route('admin.mock-exams.index') }}" class="flex items-center py-2 px-4 text-indigo-200 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.mock-exams.*') ? 'bg-indigo-700 text-white' : '' }}">
                            <i class="fas fa-list mr-3"></i>
                            <span>Mock Exams</span>
                        </a>
                        <a href="{{ route('admin.mock-exam-papers.index') }}" class="flex items-center py-2 px-4 text-indigo-200 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('admin.mock-exam-papers.*') ? 'bg-indigo-700 text-white' : '' }}">
                            <i class="fas fa-file-alt mr-3"></i>
                            <span>Mock Exam Papers</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Job Management -->
            <div class="mb-4">
                <h3 class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">Job Management</h3>

                <!-- Job Updates -->
                <a href="{{ route('admin.job-updates.index') }}" class="flex items-center py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 mb-1 {{ request()->routeIs('admin.job-updates.*') ? 'bg-indigo-700 text-white' : '' }}">
                    <i class="fas fa-briefcase mr-3"></i>
                    <span>Job Updates</span>
                </a>

                <!-- Scraped Drafts -->
                <a href="{{ route('admin.scraped-drafts.index') }}" class="flex items-center py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 mb-1 {{ request()->routeIs('admin.scraped-drafts.*') ? 'bg-indigo-700 text-white' : '' }}">
                    <i class="fas fa-file-alt mr-3"></i>
                    <span>Scraped Drafts</span>
                </a>
            </div>

            <!-- System -->
            <div class="mb-4">
                <h3 class="px-4 py-2 text-xs font-semibold text-indigo-300 uppercase tracking-wider">System</h3>

                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}" class="flex items-center py-3 px-4 text-indigo-100 hover:bg-indigo-700 hover:text-white rounded-lg transition-colors duration-200 mb-1 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-700 text-white' : '' }}">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Settings</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="lg:ml-64">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center justify-between h-16 px-6">
                <!-- Mobile menu button -->
                <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Breadcrumb -->
                <div class="flex-1 lg:ml-0 ml-4">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2">
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-home mr-1"></i>Dashboard
                                </a>
                            </li>
                            @hasSection('breadcrumb')
                                @yield('breadcrumb')
                            @endif
                        </ol>
                    </nav>
                </div>

                <!-- User menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition-colors relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                    </button>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all" id="user-menu-button">
                            <img class="h-8 w-8 rounded-full border-2 border-gray-200" src="https://ui-avatars.com/api/?name={{ urlencode($admin->username ?? 'Admin') }}&color=7F9CF5&background=EBF4FF" alt="User avatar">
                            <span class="ml-3 text-gray-700 font-medium hidden sm:block">{{ $admin->username ?? 'Admin' }}</span>
                            <i class="fas fa-chevron-down ml-2 text-gray-400"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none rounded-md" role="menu" id="user-dropdown">
                            <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" role="menuitem">
                                <i class="fas fa-user mr-3"></i>Your Profile
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" role="menuitem">
                                <i class="fas fa-cog mr-3"></i>Settings
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('admin.logout') }}" class="block">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors" role="menuitem">
                                    <i class="fas fa-sign-out-alt mr-3"></i>Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-50 min-h-screen">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    © {{ date('Y') }} PrepPoint. All rights reserved.
                </p>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>v1.0.0</span>
                </div>
            </div>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar.classList.contains('-translate-x-full') || sidebar.classList.contains('lg:translate-x-0')) {
                sidebar.classList.remove('-translate-x-full', 'lg:translate-x-0');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full', 'lg:translate-x-0');
                overlay.classList.add('hidden');
            }
        }

        // User dropdown toggle
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Mock Tests dropdown toggle
        function toggleMockDropdown() {
            const dropdown = document.getElementById('mock-dropdown');
            const chevron = document.getElementById('mock-chevron');

            dropdown.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        // Initialize sidebar state on load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.add('-translate-x-full', 'lg:translate-x-0');

            // Add click handler for user menu button
            const userMenuButton = document.getElementById('user-menu-button');
            if (userMenuButton) {
                userMenuButton.addEventListener('click', toggleUserDropdown);
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('user-dropdown');
                const button = document.getElementById('user-menu-button');
                if (dropdown && button && !button.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
