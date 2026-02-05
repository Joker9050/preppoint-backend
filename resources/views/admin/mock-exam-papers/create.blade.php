@extends('admin.layout')

@section('title', 'Create Mock Exam Paper')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Mock Exam Paper</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.mock-exam-papers.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.mock-exam-papers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exam_id">Exam <span class="text-danger">*</span></label>
                                    <select name="exam_id" id="exam_id" class="form-control @error('exam_id') is-invalid @enderror" required>
                                        <option value="">Select Exam</option>
                                        @foreach($exams as $exam)
                                            <option value="{{ $exam->id }}" {{ (isset($selectedExam) && $selectedExam->id == $exam->id) ? 'selected' : '' }}>
                                                {{ $exam->name }} ({{ $exam->short_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('exam_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paper_type">Paper Type <span class="text-danger">*</span></label>
                                    <select name="paper_type" id="paper_type" class="form-control @error('paper_type') is-invalid @enderror" required>
                                        <option value="">Select Type</option>
                                        <option value="mock">Mock Test</option>
                                        <option value="pyq">Previous Year Question</option>
                                    </select>
                                    @error('paper_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" placeholder="e.g., SSC CGL 2024 Tier-1 Mock Test 1" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="year">Year (for PYQ)</label>
                                    <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror"
                                           value="{{ old('year') }}" min="2000" max="{{ date('Y') + 1 }}" placeholder="2024">
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="shift">Shift (optional)</label>
                                    <input type="text" name="shift" id="shift" class="form-control @error('shift') is-invalid @enderror"
                                           value="{{ old('shift') }}" placeholder="Morning/Evening">
                                    @error('shift')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_questions">Total Questions <span class="text-danger">*</span></label>
                                    <input type="number" name="total_questions" id="total_questions" class="form-control @error('total_questions') is-invalid @enderror"
                                           value="{{ old('total_questions', 100) }}" min="1" required>
                                    @error('total_questions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_marks">Total Marks <span class="text-danger">*</span></label>
                                    <input type="number" name="total_marks" id="total_marks" class="form-control @error('total_marks') is-invalid @enderror"
                                           value="{{ old('total_marks', 200) }}" min="1" required>
                                    @error('total_marks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="duration_minutes">Duration (minutes) <span class="text-danger">*</span></label>
                                    <input type="number" name="duration_minutes" id="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror"
                                           value="{{ old('duration_minutes', 60) }}" min="1" max="300" required>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="difficulty_level">Difficulty Level <span class="text-danger">*</span></label>
                                    <select name="difficulty_level" id="difficulty_level" class="form-control @error('difficulty_level') is-invalid @enderror" required>
                                        <option value="">Select Difficulty</option>
                                        <option value="easy" {{ old('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="moderate" {{ old('difficulty_level') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                        <option value="hard" {{ old('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                                    </select>
                                    @error('difficulty_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="live" {{ old('status') == 'live' ? 'selected' : '' }}>Live</option>
                                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ui_template">UI Template</label>
                                    <select name="ui_template" id="ui_template" class="form-control @error('ui_template') is-invalid @enderror">
                                        <option value="">Default</option>
                                        <option value="eduquity" {{ old('ui_template') == 'eduquity' ? 'selected' : '' }}>Eduquity</option>
                                        <option value="tcs" {{ old('ui_template') == 'tcs' ? 'selected' : '' }}>TCS</option>
                                    </select>
                                    @error('ui_template')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="instructions">Instructions</label>
                            <textarea name="instructions" id="instructions" class="form-control @error('instructions') is-invalid @enderror"
                                      rows="4" placeholder="Enter paper instructions...">{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Paper
                        </button>
                        <a href="{{ route('admin.mock-exam-papers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
