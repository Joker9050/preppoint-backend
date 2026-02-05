<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MockExam;
use App\Models\MockExamPaper;
use App\Models\MockPaperSection;
use App\Models\MockPaperQuestion;
use App\Models\MockQuestion;
use App\Models\MockSubject;
use App\Models\MockTopic;
use App\Models\MockSubtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MockExamPaperController extends Controller
{
    public function index(Request $request)
    {
        $query = MockExamPaper::with('exam');

        // Filter by exam
        if ($request->has('exam_id') && $request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by paper type
        if ($request->has('paper_type') && $request->paper_type) {
            $query->where('paper_type', $request->paper_type);
        }

        $papers = $query->paginate(15);
        $exams = MockExam::all();

        return view('admin.mock-exam-papers.index', compact('papers', 'exams'));
    }

    public function create(Request $request)
    {
        $exams = MockExam::where('status', true)->get();

        // If exam_id is provided, pre-select it
        $selectedExam = null;
        if ($request->has('exam_id')) {
            $selectedExam = MockExam::find($request->exam_id);
        }

        return view('admin.mock-exam-papers.create', compact('exams', 'selectedExam'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:mock_exams,id',
            'title' => 'required|string|max:150',
            'paper_type' => 'required|in:mock,pyq',
            'year' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'shift' => 'nullable|string|max:50',
            'total_questions' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'difficulty_level' => 'required|in:easy,moderate,hard',
            'instructions' => 'nullable|string',
            'ui_template' => 'nullable|in:eduquity,tcs',
            'status' => 'required|in:draft,live,archived',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MockExamPaper::create($request->all());

        return redirect()->route('admin.mock-exam-papers.index')
            ->with('success', 'Mock exam paper created successfully.');
    }

    public function show(MockExamPaper $mockExamPaper)
    {
        $mockExamPaper->load(['exam', 'sections.questions']);
        return view('admin.mock-exam-papers.show', compact('mockExamPaper'));
    }

    public function edit(MockExamPaper $mockExamPaper)
    {
        $exams = MockExam::where('status', true)->get();
        return view('admin.mock-exam-papers.edit', compact('mockExamPaper', 'exams'));
    }

    public function update(Request $request, MockExamPaper $mockExamPaper)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:mock_exams,id',
            'title' => 'required|string|max:150',
            'paper_type' => 'required|in:mock,pyq',
            'year' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'shift' => 'nullable|string|max:50',
            'total_questions' => 'required|integer|min:1',
            'total_marks' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'difficulty_level' => 'required|in:easy,moderate,hard',
            'instructions' => 'nullable|string',
            'ui_template' => 'nullable|in:eduquity,tcs',
            'status' => 'required|in:draft,live,archived',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $mockExamPaper->update($request->all());

        return redirect()->route('admin.mock-exam-papers.index')
            ->with('success', 'Mock exam paper updated successfully.');
    }

    public function destroy(MockExamPaper $mockExamPaper)
    {
        // Check if paper has sections
        if ($mockExamPaper->sections()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete paper with existing sections.');
        }

        $mockExamPaper->delete();
        return redirect()->route('admin.mock-exam-papers.index')
            ->with('success', 'Mock exam paper deleted successfully.');
    }

    public function toggleStatus(MockExamPaper $mockExamPaper)
    {
        $statuses = ['draft', 'live', 'archived'];
        $currentIndex = array_search($mockExamPaper->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);

        $mockExamPaper->update(['status' => $statuses[$nextIndex]]);

        return redirect()->back()
            ->with('success', 'Paper status updated to ' . $statuses[$nextIndex]);
    }

    // Section Management
    public function sections(MockExamPaper $mockExamPaper)
    {
        $sections = $mockExamPaper->sections()->orderBy('sequence_no')->get();
        return view('admin.mock-exam-papers.sections.index', compact('mockExamPaper', 'sections'));
    }

    public function createSection(MockExamPaper $mockExamPaper)
    {
        return view('admin.mock-exam-papers.sections.create', compact('mockExamPaper'));
    }

    public function storeSection(Request $request, MockExamPaper $mockExamPaper)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'total_questions' => 'required|integer|min:1',
            'section_marks' => 'required|integer|min:1',
            'section_time_minutes' => 'nullable|integer|min:1',
            'sequence_no' => 'required|integer|min:1',
            'positive_marks' => 'required|numeric|min:0',
            'negative_marks' => 'required|numeric|min:0',
            'instructions' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        MockPaperSection::create([
            'paper_id' => $mockExamPaper->id,
            'name' => $request->name,
            'total_questions' => $request->total_questions,
            'section_marks' => $request->section_marks,
            'section_time_minutes' => $request->section_time_minutes,
            'sequence_no' => $request->sequence_no,
            'positive_marks' => $request->positive_marks,
            'negative_marks' => $request->negative_marks,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('admin.mock-exam-papers.sections', $mockExamPaper)
            ->with('success', 'Section created successfully.');
    }

    // Question Bank Integration
    public function questionBank(Request $request)
    {
        $query = MockQuestion::with(['subject', 'topic', 'subtopic']);

        // Filter by subject
        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by topic
        if ($request->has('topic_id') && $request->topic_id) {
            $query->where('topic_id', $request->topic_id);
        }

        // Filter by subtopic
        if ($request->has('subtopic_id') && $request->subtopic_id) {
            $query->where('subtopic_id', $request->subtopic_id);
        }

        // Filter by difficulty
        if ($request->has('difficulty_level') && $request->difficulty_level) {
            $query->where('difficulty_level', $request->difficulty_level);
        }

        $questions = $query->paginate(20);
        $subjects = MockSubject::where('status', true)->get();

        return view('admin.mock-exam-papers.question-bank', compact('questions', 'subjects'));
    }

    public function getTopics(Request $request)
    {
        $topics = MockTopic::where('subject_id', $request->subject_id)->get();
        return response()->json($topics);
    }

    public function getSubtopics(Request $request)
    {
        $subtopics = MockSubtopic::where('topic_id', $request->topic_id)->get();
        return response()->json($subtopics);
    }

    // Preview functionality
    public function preview(MockExamPaper $mockExamPaper)
    {
        $mockExamPaper->load(['exam', 'sections.paperQuestions.question.options']);
        return view('admin.mock-exam-papers.preview', compact('mockExamPaper'));
    }
}
