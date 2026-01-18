<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mcq;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class McqController extends Controller
{
    public function index(Request $request)
    {
        $query = Mcq::with('topic', 'subtopic');

        // Filter by topic
        if ($request->has('topic_id') && $request->topic_id) {
            $query->where('topic_id', $request->topic_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by difficulty
        if ($request->has('difficulty') && $request->difficulty) {
            $query->where('difficulty', $request->difficulty);
        }

        $mcqs = $query->paginate(15);
        $topics = Topic::all();

        return view('admin.mcqs.index', compact('mcqs', 'topics'));
    }

    public function create()
    {
        $topics = Topic::all();
        $subtopics = collect();
        return view('admin.mcqs.create', compact('topics', 'subtopics'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:topics,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'question_no' => 'required|integer|min:1',
            'question' => 'required|string',
            'question_code' => 'nullable|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $options = [
            'A' => $request->option_a,
            'B' => $request->option_b,
            'C' => $request->option_c,
            'D' => $request->option_d,
        ];

        $data = [
            'topic_id' => $request->topic_id,
            'subtopic_id' => $request->subtopic_id,
            'question_no' => $request->question_no,
            'question' => $request->question,
            'question_code' => $request->question_code,
            'options' => json_encode($options),
            'correct_option' => $request->correct_option,
            'explanation' => $request->explanation,
            'difficulty' => $request->difficulty,
            'status' => $request->status,
        ];

        // Handle image upload
        if ($request->hasFile('question_image')) {
            $imagePath = $request->file('question_image')->store('mcq_images', 'public');
            $data['question_image'] = $imagePath;
        }

        Mcq::create($data);

        return redirect()->route('admin.mcqs.index')
            ->with('success', 'MCQ created successfully.');
    }



    public function edit(Mcq $mcq)
    {
        $topics = Topic::all();
        $subtopics = Subtopic::where('topic_id', $mcq->topic_id)->get();
        return view('admin.mcqs.edit', compact('mcq', 'topics', 'subtopics'));
    }

    public function update(Request $request, Mcq $mcq)
    {
        $validator = Validator::make($request->all(), [
            'topic_id' => 'required|exists:topics,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'question_no' => 'required|integer|min:1',
            'question' => 'required|string',
            'question_code' => 'nullable|string',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'option_A' => 'required|string',
            'option_B' => 'required|string',
            'option_C' => 'required|string',
            'option_D' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'explanation' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $options = [
            'A' => [
                'text' => $request->option_A,
                'code' => $request->option_A_code ?? '',
                'image' => '',
            ],
            'B' => [
                'text' => $request->option_B,
                'code' => $request->option_B_code ?? '',
                'image' => '',
            ],
            'C' => [
                'text' => $request->option_C,
                'code' => $request->option_C_code ?? '',
                'image' => '',
            ],
            'D' => [
                'text' => $request->option_D,
                'code' => $request->option_D_code ?? '',
                'image' => '',
            ],
        ];

        // Handle option images
        foreach (['A', 'B', 'C', 'D'] as $letter) {
            $imageField = 'option_' . $letter . '_image';
            if ($request->hasFile($imageField)) {
                $imagePath = $request->file($imageField)->store('mcq_option_images', 'public');
                $options[$letter]['image'] = $imagePath;
            } else {
                // Keep existing image if no new one uploaded
                $existingOptions = is_array($mcq->options) ? $mcq->options : json_decode($mcq->options, true);
                $options[$letter]['image'] = $existingOptions[$letter]['image'] ?? '';
            }
        }

        $data = [
            'topic_id' => $request->topic_id,
            'subtopic_id' => $request->subtopic_id,
            'question_no' => $request->question_no,
            'question' => $request->question,
            'question_code' => $request->question_code,
            'options' => json_encode($options),
            'correct_option' => $request->correct_option,
            'explanation' => $request->explanation,
            'difficulty' => $request->difficulty,
            'status' => $request->status,
        ];

        // Handle question image upload
        if ($request->hasFile('question_image')) {
            // Delete old image if exists
            if ($mcq->question_image) {
                Storage::disk('public')->delete($mcq->question_image);
            }
            $imagePath = $request->file('question_image')->store('mcq_images', 'public');
            $data['question_image'] = $imagePath;
        }

        $mcq->update($data);

        return redirect()->route('admin.mcqs.index')
            ->with('success', 'MCQ updated successfully.');
    }

    public function destroy(Mcq $mcq)
    {
        // Delete associated image if exists
        if ($mcq->question_image) {
            Storage::disk('public')->delete($mcq->question_image);
        }

        $mcq->delete();
        return redirect()->route('admin.mcqs.index')
            ->with('success', 'MCQ deleted successfully.');
    }

    public function preview(Mcq $mcq)
    {
        $mcq->load('topic', 'subtopic');
        return view('admin.mcqs.preview', compact('mcq'));
    }

    public function getSubtopics(Request $request)
    {
        $subtopics = Subtopic::where('topic_id', $request->topic_id)->get();
        return response()->json($subtopics);
    }
}
