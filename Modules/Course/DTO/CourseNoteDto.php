<?php

namespace Modules\Course\DTO;

class CourseNoteDto
{
    public $note;

    public function __construct($request)
    {
        if ($request->get('note')) {
            $this->note = $request->get('note');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->note == null) {
            unset($data['note']);
        }

        return $data;
    }
}
