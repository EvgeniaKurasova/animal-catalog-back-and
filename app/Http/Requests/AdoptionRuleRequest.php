<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdoptionRuleRequest extends FormRequest
{
    /**
     * Визначає, чи користувач має право на цей запит
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Отримати правила валідації для запиту
     */
    public function rules(): array
    {
        return [
            // Обов'язкові поля
            'rules' => 'required|string', // Правила усиновлення українською
            'rules_en' => 'required|string' // Правила усиновлення англійською
        ];
    }

    /**
     * Отримати повідомлення про помилки валідації
     */
    public function messages(): array
    {
        $locale = app()->getLocale();
        
        if ($locale === 'en') {
            return [
                'rules.required' => 'Please enter the adoption rules',
                'rules_en.required' => 'Please enter the adoption rules in English'
            ];
        }

        return [
            'rules.required' => 'Будь ласка, введіть правила усиновлення',
            'rules_en.required' => 'Будь ласка, введіть правила усиновлення англійською'
        ];
    }
} 