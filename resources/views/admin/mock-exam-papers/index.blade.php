@extends('admin.layout')

@section('title', 'Mock Exam Papers')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mock Exam Papers</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mock-exam-papers.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Paper
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="exam_id" class="form-control">
                                    <option value="">All Exams</option>
                                    @foreach($exams as $exam)
                                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                            {{ $exam->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="paper_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="mock" {{ request('paper_type') == 'mock' ? 'selected' : '' }}>Mock</option>
                                    <option value="pyq" {{ request('paper_type') == 'pyq' ? 'selected' : '' }}>PYQ</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                                <a href="{{ route('admin.mock-exam-papers.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Exam</th>
                                    <th>Type</th>
                                    <th>Year</th>
                                    <th>Questions</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($papers as $paper)
                                    <tr>
                                        <td>{{ $paper->id }}</td>
                                        <td>{{ $paper->title }}</td>
                                        <td>{{ $paper->exam->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $paper->paper_type == 'mock' ? 'primary' : 'success' }}">
                                                {{ strtoupper($paper->paper_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $paper->year ?? 'N/A' }}</td>
                                        <td>{{ $paper->total_questions }}</td>
                                        <td>{{ $paper->duration_minutes }} min</td>
                                        <td>
                                            <span class="badge badge-{{ $paper->status == 'live' ? 'success' : ($paper->status == 'draft' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($paper->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.mock-exam-papers.show', $paper) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.mock-exam-papers.edit', $paper) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.mock-exam-papers.preview', $paper) }}" class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-search"></i> Preview
                                                </a>
                                                <form action="{{ route('admin.mock-exam-papers.toggle-status', $paper) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-toggle-on"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.mock-exam-papers.destroy', $paper) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No papers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $papers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
