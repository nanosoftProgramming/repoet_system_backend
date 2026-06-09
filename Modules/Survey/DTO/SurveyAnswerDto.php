<?php

namespace Modules\Survey\DTO;

class SurveyAnswerDto
{
    public $answers;

    public $answerable_type;

    public $answerable_id;

    public function __construct($request)
    {
        if ($request->get('answers')) {
            $this->answers = $request->get('answers');
        }
        if ($request->get('answerable_type')) {
            $this->answerable_type = $request->get('answerable_type');
        }
        if ($request->get('answerable_id')) {
            $this->answerable_id = $request->get('answerable_id');
        }
    }

    public function dataFromRequest(): array
    {
        $data = json_decode(json_encode($this), true);
        if ($this->answers == null) {
            unset($data['answers']);
        }
        if ($this->answerable_type == null) {
            unset($data['answerable_type']);
        }
        if ($this->answerable_id == null) {
            unset($data['answerable_id']);
        }

        return $data;
    }
}


