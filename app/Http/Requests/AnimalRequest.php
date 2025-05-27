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

            'name' => 'required|string|max:255', // Ім'я тварини українською
            'name_en' => 'required|string|max:255', // Ім'я тварини англійською
            'type' => 'required|string|max:255', // Вид тварини українською
            'type_en' => 'required|string|max:255', // Вид тварини англійською
            'gender' => 'required|string|in:чоловіча,жіноча', // Стать тварини
            'age_years' => 'required|integer|min:0',
            'age_months' => 'required|integer|min:0|max:11',

            'is_sterilized' => 'nullable|string|max:255', // Чи стерилізована тварина
            'size' => 'nullable|string|in:маленький,середній,великий', // Розмір тварини
            'size_en' => 'nullable|string|in:small,medium,large', // Розмір тварини англійською
            'additional_information' => 'nullable|string', // Додаткова інформація
            'additional_information_en' => 'nullable|string', // Додаткова інформація англійською

            // Фото тварини
            'photos' => 'nullable|array', // Масив фото
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Кожне фото має бути зображенням, підтримуваних форматів, не більше 2MB на фото
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
                'age_years.required' => 'Please enter the animal\'s age in years',
                'age_years.min' => 'Age in years cannot be negative',
                'age_months.required' => 'Please enter the animal\'s age in months',
                'age_months.min' => 'Age in months cannot be negative',
                'age_months.max' => 'Age in months cannot exceed 11',

                // Error messages for optional fields
                'is_sterilized.in' => 'Sterilization status must be "так" or "ні"',
                'size.in' => 'Invalid size',

                // Error messages for photos
                'photos.*.image' => 'File must be an image',
                'photos.*.mimes' => 'Supported formats: jpeg, png, jpg, gif',
                'photos.*.max' => 'Each image size cannot exceed 2MB'
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
            'age_years.required' => 'Будь ласка, введіть вік тварини в роках',
            'age_years.min' => 'Вік в роках не може бути від\'ємним',
            'age_months.required' => 'Будь ласка, введіть вік тварини в місяцях',
            'age_months.min' => 'Вік в місяцях не може бути від\'ємним',
            'age_months.max' => 'Вік в місяцях не може перевищувати 11',

            // Повідомлення про помилки для необов'язкових полів
            'is_sterilized.in' => 'Статус стерилізації має бути "так" або "ні"',
            'size.in' => 'Невірний розмір тварини',

            // Повідомлення про помилки для фото
            'photos.*.image' => 'Файл має бути зображенням',
            'photos.*.mimes' => 'Підтримуються тільки формати: jpeg, png, jpg, gif',
            'photos.*.max' => 'Розмір кожного зображення не може перевищувати 2MB'
        ];
    }
} 