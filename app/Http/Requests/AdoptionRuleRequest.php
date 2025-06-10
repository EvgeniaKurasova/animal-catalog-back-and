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
            'rules' => 'required|string', // Правила українською
            'rules_en' => 'required|string', // Правила англійською
            'order' => 'required|integer|min:0' // Порядок відображення
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
                'rules_en.required' => 'Please enter the adoption rules in English',
                'order.required' => 'Please enter the display order',
                'order.integer' => 'Order must be a number',
                'order.min' => 'Order cannot be negative'
            ];
        }

        return [
            'rules.required' => 'Будь ласка, введіть правила усиновлення',
            'rules_en.required' => 'Будь ласка, введіть правила усиновлення англійською',
            'order.required' => 'Будь ласка, введіть порядок відображення',
            'order.integer' => 'Порядок має бути числом',
            'order.min' => 'Порядок не може бути від\'ємним'
        ];
    }
} 