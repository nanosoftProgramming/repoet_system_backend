<?php

namespace Modules\CV\DTO;

class CVFieldTemplateDto
{
    public $role;

    public $label;

    public $field_key;

    public $is_required;

    public $order;

    public $placeholder;

    public $is_active;

    public function __construct($request)
    {
        if ($request->get('role')) {
            $this->role = $request->get('role');
        }
        if ($request->get('label')) {
            $this->label = $request->get('label');
        }
        if ($request->get('field_key')) {
            $this->field_key = $request->get('field_key');
        }
        if ($request->has('is_required')) {
            $this->is_required = $request->get('is_required');
        }
        if ($request->get('order')) {
            $this->order = $request->get('order');
        }
        if ($request->get('placeholder')) {
            $this->placeholder = $request->get('placeholder');
        }
        if ($request->has('is_active')) {
            $this->is_active = $request->get('is_active');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($this->role == null) {
            unset($data['role']);
        }
        if ($this->label == null) {
            unset($data['label']);
        }
        if ($this->field_key == null) {
            unset($data['field_key']);
        }
        if ($this->is_required == null) {
            unset($data['is_required']);
        }
        if ($this->order == null) {
            unset($data['order']);
        }
        if ($this->placeholder == null) {
            unset($data['placeholder']);
        }
        if ($this->is_active == null) {
            unset($data['is_active']);
        }

        return $data;
    }
}
