<?php

namespace Modules\CV\DTO;

class CVDto
{
    public $data;

    public function __construct($request)
    {
        if ($request->has('data') && is_array($request->get('data'))) {
            $this->data = $request->get('data');
        }
    }

    public function dataFromRequest()
    {
        $data = [];
        if ($this->data !== null) {
            $data['data'] = $this->data;
        }

        return $data;
    }
}
