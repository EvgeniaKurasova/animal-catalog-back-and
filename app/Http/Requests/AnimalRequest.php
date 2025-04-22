<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnimalRequest extends FormRequest
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
            'name' => 'required|string|max:255', // Ім'я тварини українською
            'name_en' => 'required|string|max:255', // Ім'я тварини англійською
            'type' => 'required|string|max:255', // Вид тварини українською
            'type_en' => 'required|string|max:255', // Вид тварини англійською
            'gender' => 'required|string|in:чоловіча,жіноча', // Стать тварини
            'gender_en' => 'required|string|in:male,female', // Стать тварини англійською
            'age' => 'required|integer|min:0', // Вік тварини

            // Необов'язкові поля
            'size' => 'nullable|string|in:маленький,середній,великий', // Розмір тварини
            'size_en' => 'nullable|string|in:small,medium,large', // Розмір тварини англійською
            'city' => 'nullable|string|max:255', // Місто
            'city_en' => 'nullable|string|max:255', // Місто англійською
            'description' => 'nullable|string', // Опис тварини
            'description_en' => 'nullable|string', // Опис тварини англійською
            'shelter_id' => 'nullable|exists:shelter_info,id', // ID притулку

            // Фото тварини
            'photos' => 'nullable|array', // Масив фото
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:1024' // Кожне фото має бути зображенням, підтримуваних форматів, не більше 1MB на фото
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
                // Error messages for required fields
                'name.required' => 'Please enter the animal\'s name',
                'name_en.required' => 'Please enter the animal\'s name in English',
                'type.required' => 'Please enter the animal type',
                'type_en.required' => 'Please enter the animal type in English',
                'gender.required' => 'Please select the animal\'s gender',
                'gender.in' => 'Invalid gender',
                'age.required' => 'Please enter the animal\'s age',
                'age.min' => 'Age cannot be negative',

                // Error messages for optional fields
                'size.in' => 'Invalid size',

                // Error messages for photos
                'photos.*.image' => 'File must be an image',
                'photos.*.mimes' => 'Supported formats: jpeg, png, jpg, gif',
                'photos.*.max' => 'Each image size cannot exceed 1MB'
            ];
        }

        return [
            // Повідомлення про помилки для обов'язкових полів
            'name.required' => 'Будь ласка, введіть ім\'я тварини',
            'name_en.required' => 'Будь ласка, введіть ім\'я тварини англійською',
            'type.required' => 'Будь ласка, введіть вид тварини',
            'type_en.required' => 'Будь ласка, введіть вид тварини англійською',
            'gender.required' => 'Будь ласка, виберіть стать тварини',
            'gender.in' => 'Невірна стать тварини',
            'age.required' => 'Будь ласка, введіть вік тварини',
            'age.min' => 'Вік не може бути від\'ємним',

            // Повідомлення про помилки для необов'язкових полів
            'size.in' => 'Невірний розмір тварини',

            // Повідомлення про помилки для фото
            'photos.*.image' => 'Файл має бути зображенням',
            'photos.*.mimes' => 'Підтримуються тільки формати: jpeg, png, jpg, gif',
            'photos.*.max' => 'Розмір кожного зображення не може перевищувати 1MB'
        ];
    }
} 