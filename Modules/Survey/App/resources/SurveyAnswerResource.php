<?php

namespace Modules\Survey\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'survey_question_id' => $this->survey_question_id,
            'answer' => $this->answer,
            'question' => $this->whenLoaded('question'),
        ];
    }
}
