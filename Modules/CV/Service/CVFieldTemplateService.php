<?php

namespace Modules\CV\Service;

use Modules\CV\App\Models\CVFieldTemplate;

class CVFieldTemplateService
{
    public function findAll($data = [], $relations = [])
    {
        $query = CVFieldTemplate::query();

        if (isset($data['role'])) {
            $query->where('role', $data['role']);
        }

        if (isset($data['is_active'])) {
            $query->where('is_active', $data['is_active']);
        }

        return getCaseCollection($query->latest(), $data);
    }

    public function findById($id)
    {
        return CVFieldTemplate::findOrFail($id);
    }

    public function save(string $role, array $templates)
    {
        $createdTemplates = [];

        foreach ($templates as $template) {
            $createdTemplates[] = CVFieldTemplate::create([
                'role' => $role,
                'label' => $template['label'],
                'field_key' => $template['field_key'],
                'is_required' => $template['is_required'] ?? false,
                'order' => $template['order'] ?? 0,
                'placeholder' => $template['placeholder'] ?? null,
                'is_active' => $template['is_active'] ?? true,
            ]);
        }

        return collect($createdTemplates);
    }

    public function update(string $role, array $templates)
    {
        // Get all existing templates for this role
        $existingTemplates = CVFieldTemplate::where('role', $role)->get()->keyBy('field_key');
        $result = [];

        foreach ($templates as $template) {
            $fieldKey = $template['field_key'];

            if ($existingTemplates->has($fieldKey)) {
                // Update existing template
                $existing = $existingTemplates->get($fieldKey);
                $existing->update([
                    'label' => $template['label'],
                    'is_required' => $template['is_required'] ?? false,
                    'order' => $template['order'] ?? 0,
                    'placeholder' => $template['placeholder'] ?? null,
                    'is_active' => $template['is_active'] ?? true,
                ]);
                $result[] = $existing->fresh();
            } else {
                // Create new template
                $result[] = CVFieldTemplate::create([
                    'role' => $role,
                    'label' => $template['label'],
                    'field_key' => $fieldKey,
                    'is_required' => $template['is_required'] ?? false,
                    'order' => $template['order'] ?? 0,
                    'placeholder' => $template['placeholder'] ?? null,
                    'is_active' => $template['is_active'] ?? true,
                ]);
            }
        }

        return collect($result);
    }

    public function delete($template)
    {
        $template->delete();
    }

    public function toggleActivate($template)
    {
        $template->update([
            'is_active' => ! $template->is_active,
        ]);

        return $template->fresh();
    }

    public function getFieldsForRole(string $role)
    {
        return CVFieldTemplate::forRole($role)->ordered()->get();
    }
}
