<?php

namespace Modules\CV\Service;

use Modules\CV\App\Models\CV;
use Modules\CV\App\Models\CVFieldTemplate;
use Modules\User\App\Models\User;

class CVService
{
    public function findAll($data = [], $relations = [])
    {
        $query = CV::with($relations)->latest();

        if (isset($data['user_id'])) {
            $query->where('user_id', $data['user_id']);
        }

        if (isset($data['is_completed'])) {
            $query->where('is_completed', $data['is_completed']);
        }

        return getCaseCollection($query, $data);
    }

    public function findById($id)
    {
        return CV::with('user')->findOrFail($id);
    }

    public function getOrCreateCV(User $user)
    {
        return CV::firstOrCreate(
            ['user_id' => $user->id],
            ['data' => [], 'is_completed' => false]
        );
    }

    public function getCV(User $user)
    {
        return CV::where('user_id', $user->id)->first();
    }

    public function save($data)
    {
        $user = auth('user')->user();
        $cv = $this->getOrCreateCV($user);
            if (!$user) {
        return [];
    }

        $role = $user->role;

        $templates = CVFieldTemplate::forRole($role)->ordered()->get();

        // Get existing data or start with empty array
        $existingData = $cv->data ?? [];
        $processedData = $existingData; // Start with existing data

        // Only update fields that are provided in the request
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($templates as $template) {
                $fieldKey = $template->field_key;

                // Only update if the field is present in the request
                if (isset($data['data'][$fieldKey])) {
                    $value = $data['data'][$fieldKey];
                    $processedData[$fieldKey] = $value ? (string) $value : null;
                }
                // If field not in request, keep existing value (don't overwrite)
            }
        }

        $isCompleted = $this->checkCompletion($role, $processedData);

        $cv->update([
            'data' => $processedData,
            'is_completed' => $isCompleted,
        ]);

        return $cv->fresh();
    }

    private function checkCompletion(string $role, array $data): bool
    {
        $templates = CVFieldTemplate::forRole($role)->ordered()->get();

        foreach ($templates as $template) {
            if ($template->is_required) {
                $value = $data[$template->field_key] ?? null;
                if (empty($value) || trim($value) === '') {
                    return false;
                }
            }
        }

        return true;
    }

    public function getCVFormData(User $user)
    {
        $role = $user->role;
            if (!$user) {
        return [];
    }

        $cv = $this->getCV($user);

        $templates = CVFieldTemplate::forRole($role)->ordered()->get();

        $formData = [];
        foreach ($templates as $template) {
            $formData[] = [
                'id' => $template->id,
                'label' => $template->label,
                'field_key' => $template->field_key,
                'is_required' => $template->is_required,
                'placeholder' => $template->placeholder,
                'value' => $cv?->data[$template->field_key] ?? null,
            ];
        }

        return [
            'fields' => $formData,
            'cv' => $cv,
            'is_completed' => $cv?->is_completed ?? false,
        ];
    }
}
