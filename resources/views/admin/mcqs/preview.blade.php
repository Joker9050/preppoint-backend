@extends('admin.layout')

@section('title', 'Preview MCQ')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">MCQ Preview</h1>
            <p class="text-gray-600">Question #{{ $mcq->question_no }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.mcqs.edit', $mcq) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.mcqs.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- MCQ Preview Card -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Question Header -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex space-x-2">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($mcq->difficulty == 'easy') bg-green-100 text-green-800
                        @elseif($mcq->difficulty == 'medium') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($mcq->difficulty) }}
                    </span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                        @if($mcq->status == 'active') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($mcq->status) }}
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    Topic: {{ $mcq->topic->name ?? 'N/A' }}
                    @if($mcq->subtopic)
                        → {{ $mcq->subtopic->name }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Question Content -->
        <div class="p-6 space-y-6">
            <!-- Question Text -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $mcq->question }}</h2>

                <!-- Question Code -->
                @if($mcq->question_code)
                <div class="bg-gray-100 rounded-lg p-4 mb-4">
                    <pre class="text-sm font-mono text-gray-800 overflow-x-auto"><code>{{ $mcq->question_code }}</code></pre>
                </div>
                @endif

                <!-- Question Image -->
                @if($mcq->question_image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $mcq->question_image) }}" alt="Question Image" class="max-w-full h-auto rounded-lg shadow">
                </div>
                @endif
            </div>

            <!-- Options -->
            <div class="space-y-3">
                <h3 class="text-md font-medium text-gray-900">Answer Options:</h3>
                @php
                    $options = is_array($mcq->options) ? $mcq->options : json_decode($mcq->options, true);
                @endphp
                @foreach(['A', 'B', 'C', 'D'] as $letter)
                <div class="flex items-start space-x-3 p-3 rounded-lg border
                    @if($mcq->correct_option == $letter) border-green-300 bg-green-50
                    @else border-gray-200 @endif">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full
                            @if($mcq->correct_option == $letter) bg-green-500 text-white font-bold
                            @else bg-gray-200 text-gray-700 font-medium @endif">
                            {{ $letter }}
                        </span>
                    </div>
                    <div class="flex-1">
                        @if(isset($options[$letter]))
                            @php $option = $options[$letter]; @endphp
                            @if(isset($option['text']) && !empty($option['text']))
                                <p class="text-gray-900">{{ $option['text'] }}</p>
                            @endif
                            @if(isset($option['code']) && !empty($option['code']))
                                <div class="bg-gray-100 rounded p-2 mt-2">
                                    <pre class="text-sm font-mono text-gray-800 overflow-x-auto"><code>{{ $option['code'] }}</code></pre>
                                </div>
                            @endif
                            @if(isset($option['image']) && !empty($option['image']))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $option['image']) }}" alt="Option Image" class="max-w-full h-auto rounded shadow">
                                </div>
                            @endif
                        @endif
                        @if($mcq->correct_option == $letter)
                        <p class="text-sm text-green-600 font-medium mt-1">✓ Correct Answer</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Explanation -->
            @if($mcq->explanation)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-md font-medium text-blue-900 mb-2">Explanation:</h3>
                <p class="text-blue-800">{{ $mcq->explanation }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Metadata -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Question Metadata</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">ID</dt>
                <dd class="text-sm text-gray-900">{{ $mcq->id }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Question No</dt>
                <dd class="text-sm text-gray-900">{{ $mcq->question_no }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Created</dt>
                <dd class="text-sm text-gray-900">{{ $mcq->created_at->format('M d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Updated</dt>
                <dd class="text-sm text-gray-900">{{ $mcq->updated_at->format('M d, Y') }}</dd>
            </div>
        </div>
    </div>
</div>
@endsection
