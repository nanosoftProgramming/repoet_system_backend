<?php

namespace Modules\Course\DTO;

class CourseDto
{
    public $title;
    public $description;
    public $trainer_id;
    public $sessions_no;
    public $price;
    public $date;
    public $student;
    public $instructor;
    public $trainer;

    public function __construct($request)
    {
        if ($request->get('title'))
            $this->title = $request->get('title');
        if ($request->get('description'))
            $this->description = $request->get('description');
        if ($request->get('sessions_no'))
            $this->sessions_no = $request->get('sessions_no');
        if ($request->get('price'))
            $this->price = $request->get('price');
        if ($request->get('date'))
            $this->date = $request->get('date');
        if ($request->input('trainer_id'))
            $this->trainer_id = $request->input('trainer_id');
        if ($request->input('student'))
            $this->student = $request->input('student');
        if ($request->input('instructor'))
            $this->instructor = $request->input('instructor');
        if ($request->input('trainer'))
            $this->trainer = $request->input('trainer');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->title == null)
            unset($data['name']);
        if ($this->description == null)
            unset($data['description']);
        if ($this->sessions_no == null)
            unset($data['sessions_no']);
        if ($this->price == null)
            unset($data['price']);
        if ($this->date == null)
            unset($data['date']);
        if ($this->trainer_id == null)
            unset($data['trainer_id']);
        if ($this->student == null)
            unset($data['student']);
        if ($this->instructor == null)
            unset($data['instructor']);
        if ($this->trainer == null)
            unset($data['trainer']);
        return $data;
    }
}
