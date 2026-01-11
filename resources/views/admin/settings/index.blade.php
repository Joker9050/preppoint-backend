@extends('admin.layout')

@section('title', 'Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-600">Manage site configuration and preferences</p>
        </div>
    </div>

    <!-- Settings Groups -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- General Settings -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-cog text-blue-400 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900 truncate">General Settings</dt>
                            <dd class="mt-1 text-sm text-gray-500">Site title, description, contact info</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-5">
                    <a href="{{ route('admin.settings.edit', 'general') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-edit mr-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-search text-green-400 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900 truncate">SEO Settings</dt>
                            <dd class="mt-1 text-sm text-gray-500">Meta tags, Open Graph, Twitter cards</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-5">
                    <a href="{{ route('admin.settings.edit', 'seo') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-edit mr-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-share-alt text-purple-400 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900 truncate">Social Media</dt>
                            <dd class="mt-1 text-sm text-gray-500">Social links, WhatsApp, Telegram</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-5">
                    <a href="{{ route('admin.settings.edit', 'social') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <i class="fas fa-edit mr-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- Email Configuration -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-envelope text-red-400 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900 truncate">Email Config</dt>
                            <dd class="mt-1 text-sm text-gray-500">SMTP, Mailgun, SendGrid settings</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-5">
                    <a href="{{ route('admin.settings.edit', 'email') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-edit mr-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- API Keys -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-key text-yellow-400 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900 truncate">API Keys</dt>
                            <dd class="mt-1 text-sm text-gray-500">Google, reCAPTCHA, social APIs</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-5">
                    <a href="{{ route('admin.settings.edit', 'api') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                        <i class="fas fa-edit mr-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>

        <!-- Analytics -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-line text-indigo-400 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900 truncate">Analytics</dt>
                            <dd class="mt-1 text-sm text-gray-500">Google Analytics, Facebook Pixel</dd>
                        </dl>
                    </div>
                </div>
                <div class="mt-5">
                    <a href="{{ route('admin.settings.edit', 'analytics') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-edit mr-2"></i>Configure
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Settings Overview -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Current Settings Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Site Title</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Setting::get('site_title', 'Not set') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Site Description</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Setting::get('site_description', 'Not set') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Contact Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Setting::get('contact_email', 'Not set') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Google Analytics</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Setting::get('google_analytics_tracking_id', 'Not configured') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Email Driver</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Setting::get('mail_driver', 'Not configured') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Maintenance Mode</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ \App\Models\Setting::get('maintenance_mode') ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ \App\Models\Setting::get('maintenance_mode') ? 'Enabled' : 'Disabled' }}
                    </span>
                </dd>
            </div>
        </div>
    </div>
</div>
@endsection
