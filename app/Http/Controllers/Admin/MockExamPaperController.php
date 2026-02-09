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

    public function editSection(MockExamPaper $mockExamPaper, MockPaperSection $section)
    {
        return view('admin.mock-exam-papers.sections.edit', compact('mockExamPaper', 'section'));
    }

    public function updateSection(Request $request, MockExamPaper $mockExamPaper, MockPaperSection $section)
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

        $section->update([
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
            ->with('success', 'Section updated successfully.');
    }

    public function destroySection(MockExamPaper $mockExamPaper, MockPaperSection $section)
    {
        // Check if section has questions
        if ($section->questions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete section with existing questions.');
        }

        $section->delete();

        return redirect()->route('admin.mock-exam-papers.sections', $mockExamPaper)
            ->with('success', 'Section deleted successfully.');
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

    public function getTopics($subjectId)
    {
        $topics = MockTopic::where('subject_id', $subjectId)->get(['id', 'name']);
        return response()->json($topics);
    }

    public function getSubtopics($topicId)
    {
        $subtopics = MockSubtopic::where('topic_id', $topicId)->get(['id', 'name']);
        return response()->json($subtopics);
    }

    // Section Questions Management
    public function sectionQuestions(MockExamPaper $mockExamPaper, MockPaperSection $section)
    {
        $questions = $section->questions()->with('question.options')->paginate(20);
        return view('admin.mock-exam-papers.sections.questions.index', compact('mockExamPaper', 'section', 'questions'));
    }

    public function addQuestionsToSection(MockExamPaper $mockExamPaper, MockPaperSection $section)
    {
        $query = MockQuestion::with(['subject', 'topic', 'subtopic']);

        // Filter by subject
        if (request('subject_id')) {
            $query->where('subject_id', request('subject_id'));
        }

        // Filter by topic
        if (request('topic_id')) {
            $query->where('topic_id', request('topic_id'));
        }

        // Filter by subtopic
        if (request('subtopic_id')) {
            $query->where('subtopic_id', request('subtopic_id'));
        }

        // Filter by difficulty
        if (request('difficulty_level')) {
            $query->where('difficulty_level', request('difficulty_level'));
        }

        // Exclude questions already in this section
        $existingQuestionIds = $section->questions()->pluck('question_id')->toArray();
        $query->whereNotIn('id', $existingQuestionIds);

        $questions = $query->paginate(20);
        $subjects = MockSubject::where('status', true)->get();

        return view('admin.mock-exam-papers.sections.questions.add', compact('mockExamPaper', 'section', 'questions', 'subjects'));
    }

    public function storeQuestionsToSection(Request $request, MockExamPaper $mockExamPaper, MockPaperSection $section)
    {
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:mock_questions,id',
            'marks' => 'nullable|array',
            'marks.*' => 'numeric|min:0',
            'negative_marks' => 'nullable|array',
            'negative_marks.*' => 'numeric|min:0',
        ]);

        $questionIds = $request->question_ids;
        $marks = $request->marks ?? [];
        $negativeMarks = $request->negative_marks ?? [];

        // Check if adding these questions would exceed section total
        $currentCount = $section->questions()->count();
        if ($currentCount + count($questionIds) > $section->total_questions) {
            return redirect()->back()
                ->with('error', 'Cannot add these questions. Section would exceed total questions limit (' . $section->total_questions . ').');
        }

        foreach ($questionIds as $questionId) {
            MockPaperQuestion::create([
                'paper_id' => $mockExamPaper->id,
                'section_id' => $section->id,
                'question_id' => $questionId,
                'marks' => $marks[$questionId] ?? $section->positive_marks,
                'negative_marks' => $negativeMarks[$questionId] ?? $section->negative_marks,
            ]);
        }

        return redirect()->route('admin.mock-exam-papers.sections.questions', [$mockExamPaper, $section])
            ->with('success', count($questionIds) . ' questions added to section successfully.');
    }

    public function createQuestionPage(Request $request)
    {
        $subjects = MockSubject::where('status', true)->get();
        $returnUrl = $request->get('return_url', route('admin.question-bank'));

        return view('admin.questions.create', compact('subjects', 'returnUrl'));
    }

    public function storeQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|exists:mock_subjects,id',
            'topic_id' => 'required|exists:mock_topics,id',
            'subtopic_id' => 'nullable|exists:mock_subtopics,id',
            'question_type' => 'required|in:mcq,numeric',
            'difficulty_level' => 'required|in:easy,moderate,hard',
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'required_if:question_type,mcq|array|min:2',
            'options.*' => 'required_if:question_type,mcq|string',
            'correct_option' => 'required_if:question_type,mcq|string|in:A,B,C,D,E,F,G,H',
            'numeric_answer' => 'required_if:question_type,numeric|numeric',
            'explanation' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('question_image')) {
                $imagePath = $request->file('question_image')->store('mock-questions', 'public');
            }

            // Create the question
            $question = MockQuestion::create([
                'subject_id' => $request->subject_id,
                'topic_id' => $request->topic_id,
                'subtopic_id' => $request->subtopic_id,
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'difficulty_level' => $request->difficulty_level,
                'question_image' => $imagePath,
                'explanation' => $request->explanation,
            ]);

            // Create options for MCQ
            if ($request->question_type === 'mcq') {
                foreach ($request->options as $key => $optionText) {
                    $isCorrect = ($request->correct_option === $key);
                    MockQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => $isCorrect,
                        'option_key' => $key,
                    ]);
                }
            } else {
                // For numeric questions, store the answer in options table
                MockQuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => (string) $request->numeric_answer,
                    'is_correct' => true,
                    'option_key' => 'NUMERIC',
                ]);
            }

            DB::commit();

            $returnUrl = $request->get('return_url', route('admin.question-bank'));

            return redirect($returnUrl)
                ->with('success', 'Question created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Question creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to create question. Please try again.')
                ->withInput();
        }
    }

    public function removeQuestionFromSection(MockExamPaper $mockExamPaper, MockPaperSection $section, MockPaperQuestion $question)
    {
        // Ensure the question belongs to this section
        if ($question->section_id !== $section->id) {
            return redirect()->back()->with('error', 'Question does not belong to this section.');
        }

        $question->delete();

        return redirect()->back()
            ->with('success', 'Question removed from section successfully.');
    }

    // Preview functionality
    public function preview(MockExamPaper $mockExamPaper)
    {
        $mockExamPaper->load(['exam', 'sections.questions.question.options']);
        return view('admin.mock-exam-papers.preview', compact('mockExamPaper'));
    }
}
