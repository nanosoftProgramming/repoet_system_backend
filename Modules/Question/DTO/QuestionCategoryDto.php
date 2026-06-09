<?php

namespace Modules\Question\DTO;

class QuestionCategoryDto
{
    public $title;

    public $instructor_id;

    public $questions = [];

    public function __construct($request)
    {
        if ($request->get('title')) {
            $this->title = $request->get('title');
        }
        if ($request->input('instructor_id')) {
            $this->instructor_id = $request->input('instructor_id');
        }
        if ($request->get('questions')) {
            $this->questions = $request->get('questions');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->title == null) {
            unset($data['title']);
        }
        if ($this->instructor_id == null) {
            unset($data['instructor_id']);
        }
        if (empty($this->questions)) {
            unset($data['questions']);
        }

        return $data;
    }
}
