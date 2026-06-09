<?php

namespace Modules\Exam\DTO;

use Modules\Exam\App\Http\Requests\ExamRequest;

class ExamDto
{
    public $title;
    public $score;
    public $total;

    public function __construct(ExamRequest $request)
    {
        if ($request->input('title'))
            $this->title = $request->input('title');
        if ($request->input('score'))
            $this->score = $request->input('score');
        if ($request->input('total'))
            $this->total = $request->input('total');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->title == null)
            unset($data['title']);
        if ($this->score == null)
            unset($data['score']);
        if ($this->total == null)
            unset($data['total']);

        return $data;
    }
}
