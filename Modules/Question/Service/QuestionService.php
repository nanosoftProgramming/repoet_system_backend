<?php

namespace Modules\Question\Service;

use Modules\Question\App\Models\Question;

class QuestionService
{
    public function delete(Question $question): void
    {
        $question->delete();
    }
}
