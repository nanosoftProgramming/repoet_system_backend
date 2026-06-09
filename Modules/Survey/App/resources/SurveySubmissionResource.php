<?php

namespace Modules\Survey\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveySubmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
public function toArray($request): array
{
    return [
        'id' => $this->id,
'student_name' => $this->student_name,
        'academy' => $this->whenLoaded('academy'),
        // إضافة هذا الجزء لعرض بيانات الاستبيان بشكل واضح
        'survey' => [
            'id'   => $this->answerable_id,
            'name' => $this->answerable ? $this->answerable->name : null,
        ],
        'answers' => SurveyAnswerResource::collection($this->whenLoaded('answers')),
        'created_at' => $this->created_at,
    ];
}

}
