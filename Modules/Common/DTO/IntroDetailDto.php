<?php

namespace Modules\Common\DTO;

class IntroDetailDto
{
    public $id;
    public $title_ar;
    public $title_en;
    public $subtitle_ar;
    public $subtitle_en;
    public $description_ar;
    public $description_en;
    public $image;

    public function __construct($detail)
    {
        if (isset($detail['id']))
            $this->id = $detail['id'];
        if (isset($detail['title_ar']))
            $this->title_ar = $detail['title_ar'];
        if (isset($detail['title_en']))
            $this->title_en = $detail['title_en'];
        if (isset($detail['subtitle_ar']))
            $this->subtitle_ar = $detail['subtitle_ar'];
        if (isset($detail['subtitle_en']))
            $this->subtitle_en = $detail['subtitle_en'];
        if (isset($detail['description_ar']))
            $this->description_ar = $detail['description_ar'];
        if (isset($detail['description_en']))
            $this->description_en = $detail['description_en'];
        if (isset($detail['image']))
            $this->image = $detail['image'];
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->id == null)
            unset($data['id']);
        if ($this->title_ar == null)
            unset($data['title_ar']);
        if ($this->title_en == null)
            unset($data['title_en']);
        if ($this->subtitle_ar == null)
            unset($data['subtitle_ar']);
        if ($this->subtitle_en == null)
            unset($data['subtitle_en']);
        if ($this->description_ar == null)
            unset($data['description_ar']);
        if ($this->description_en == null)
            unset($data['description_en']);
        if ($this->image == null)
            unset($data['image']);
        return $data;
    }
}
