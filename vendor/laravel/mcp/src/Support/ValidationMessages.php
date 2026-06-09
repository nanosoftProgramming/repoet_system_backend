<?php

declare(strict_types=1);

namespace Laravel\Mcp\Support;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidationMessages
{
    public static function from(ValidationException $exception): string
    {
        $messages = collect($exception->errors())->flatten()->all();

        if (count($messages) === 0 || ! is_string($messages[0])) {
            $translator = Validator::getTranslator();

            return $translator->get('البيانات المعطاة غير صالحة.');
        }

        return implode(' ', $messages);
    }
}
