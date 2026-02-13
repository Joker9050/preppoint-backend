@extends('admin.layout')

@section('title', 'User Details - Admin Panel')

@section('breadcrumb')
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-indigo-600">Users</a>
</li>
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <span class="text-gray-500">User Details</span>
</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img class="h-16 w-16 rounded-full border-4 border-indigo-100" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Users
                    </a>
                    @if($user->status === 'active')
                    <form method="POST" action="{{ route('admin.users.block', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            <i class="fas fa-ban mr-2"></i>
                            Block User
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.users.unblock', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Unblock User
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-trash mr-2"></i>
                            Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Stats Cards -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-check text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Status</dt>
                            <dd class="text-2xl font-bold capitalize">{{ $user->status }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-plus text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Registered On</dt>
                            <dd class="text-lg font-bold">{{ $user->created_at->format('M j, Y') }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-sign-in-alt text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Last Login</dt>
                            <dd class="text-lg font-bold">{{ $user->last_login_at ? $user->last_login_at->format('M j, Y') : 'Never' }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clipboard-list text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Provider</dt>
                            <dd class="text-lg font-bold capitalize">{{ $user->provider }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Information Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 via-white to-blue-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i class="fas fa-user text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Basic Information</h3>
                    <p class="text-sm text-gray-600">User profile details</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Full Name</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Email Address</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg mr-3">
                            <i class="fas fa-phone text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Phone Number</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                            <i class="fas fa-calendar text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Registration Date</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg mr-3">
                            <i class="fas fa-clock text-red-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Last Login</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('d M Y, h:i A') : 'Never' }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-indigo-100 rounded-lg mr-3">
                            <i class="fas fa-shield-alt text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase">Account Status</p>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mock Activity Summary Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 via-white to-blue-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i class="fas fa-clipboard-list text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Mock Activity Summary</h3>
                    <p class="text-sm text-gray-600">User's mock exam performance</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-list-ol text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Total Attempts</dt>
                            <dd class="text-3xl font-bold">{{ $mockSummary->total_attempts ?? 0 }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Completed Mocks</dt>
                            <dd class="text-3xl font-bold">{{ $mockSummary->completed_mocks ?? 0 }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-percentage text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Average Score</dt>
                            <dd class="text-3xl font-bold">{{ $mockSummary->average_score ? number_format($mockSummary->average_score, 1) . '%' : 'N/A' }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-trophy text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Best Score</dt>
                            <dd class="text-3xl font-bold">{{ $mockSummary->best_score ? number_format($mockSummary->best_score, 1) . '%' : 'N/A' }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 via-white to-blue-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i class="fas fa-history text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                    <p class="text-sm text-gray-600">Last 5 mock exam attempts</p>
                </div>
            </div>
        </div>
        
        <!-- Table with Horizontal Scrolling -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span>Exam</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span>Paper</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                <span>Status</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                <span>Questions</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span>Score</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                <span>Date</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentAttempts as $attempt)
                    <tr class="bg-white hover:bg-indigo-50/30 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $attempt->exam->title ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $attempt->paper->title ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($attempt->status === 'completed') bg-green-100 text-green-800
                                @elseif($attempt->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                {{ $attempt->attempted_questions }}/{{ $attempt->total_questions }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $attempt->score ? number_format($attempt->score, 2) . '%' : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span>{{ $attempt->created_at->format('M j, Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $attempt->created_at->format('g:i A') }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No recent activity</h3>
                                <p class="text-gray-500 text-sm">This user hasn't attempted any mock exams yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
