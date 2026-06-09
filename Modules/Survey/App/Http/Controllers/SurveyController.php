<?php

namespace Modules\Survey\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Survey\App\Models\Survey;
use Modules\Survey\App\Models\SurveyAnswer;
use Modules\Survey\App\Models\SurveySubmission;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('survey::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('survey::create');
    }

public function surveys()
{
    $surveys = \Modules\Survey\App\Models\Survey::with([
        'questions'
    ])
    ->where('status', true)
    ->get();

    return response()->json([
        'success' => true,
        'surveys' => $surveys
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     //
    // }

    public function store(Request $request)
{
dd($request);
    // $request->validate([
    //     'survey_id' => 'required|exists:surveys,id',
    //     'answers' => 'required|array|min:1',
    //     'answers.*.question_id' => 'required|exists:survey_questions,id',
    //     'answers.*.answer' => 'required'
    // ]);

    // $submission = SurveySubmission::create([
    //     'student_id'     => auth()->id(),
    //     'answerable_type'=> Survey::class,
    //     'answerable_id'  => $request->survey_id,
    // ]);

    // foreach ($request->answers as $item) {

    //     SurveyAnswer::create([
    //         'survey_submission_id' => $submission->id,
    //         'survey_question_id'   => $item['question_id'],
    //         'answer'               => $item['answer'],
    //     ]);
    // }

    // return response()->json([
    //     'success' => true,
    //     'message' => 'Survey submitted successfully'
    // ]);
}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('survey::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('survey::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
