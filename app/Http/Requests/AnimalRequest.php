<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class AnimalRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            // Перевірте, чи автентифікований користувач має право додавати/оновлювати тварину.
            // Оскільки цей маршрут захищений middleware 'EnsureUserIsAdmin',
            // ми можемо просто повернути true, якщо користувач вже пройшов авторизацію в middleware.
            return true; 
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, mixed>
         */
        public function rules()
        {
            return [
                'name' => 'required|string|max:255', // Ім'я тварини українською
                'name_en' => 'required|string|max:255', // Ім'я тварини англійською
                'type' => 'required|string|max:255', // Вид тварини українською
                'type_en' => 'required|string|max:255', // Вид тварини англійською
                'gender' => 'required|boolean', // Стать тварини (очікуємо true/false або '1'/'0')
                'age_years' => 'required|integer|min:0',
                'age_months' => 'required|integer|min:0|max:11',
                'is_sterilized' => 'required|string|max:255', // Чи стерилізована тварина (якщо це boolean, змініть на 'boolean')
                'size' => 'required|string', // Розмір тварини
                'size_en' => 'required|string', // Розмір тварини англійською
                'additional_information' => 'nullable|string', // Додаткова інформація
                'additional_information_en' => 'nullable|string', // Додаткова інформація англійською
                'age_updated_at' => 'nullable|date', // Це поле буде встановлено на бекенді
                'photos' => 'array|nullable', // Масив файлів
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif', // Кожне фото має бути зображенням підтримуваних форматів
                'photos_data' => 'array|nullable', // Масив даних про фото
                'photos_data.*.is_main' => 'boolean', // Кожен елемент photos_data має містити is_main (boolean)
            ];
        }

        /**
         * Get the error messages for the defined validation rules.
         *
         * @return array
         */
        public function messages()
        {
            $locale = app()->getLocale();
            
            if ($locale === 'en') {
                return [
                    'name.required' => 'Please enter the animal\'s name.',
                    'name_en.required' => 'Please enter the animal\'s name in English.',
                    'type.required' => 'Please enter the animal type.',
                    'type_en.required' => 'Please enter the animal type in English.',
                    'gender.required' => 'Please select the animal\'s gender.',
                    'gender.boolean' => 'The gender field must be true or false.',
                    'age_years.required' => 'Please enter the animal\'s age in years.',
                    'age_years.integer' => 'Age in years must be an integer.',
                    'age_years.min' => 'Age in years cannot be negative.',
                    'age_months.required' => 'Please enter the animal\'s age in months.',
                    'age_months.integer' => 'Age in months must be an integer.',
                    'age_months.min' => 'Age in months cannot be negative.',
                    'age_months.max' => 'Age in months cannot exceed 11.',
                    'is_sterilized.required' => 'Please enter the animal\'s sterilization status.',
                    'size.required' => 'Please enter the animal\'s size.',
                    'size_en.required' => 'Please enter the animal\'s size in English.',
                    'photos.*.image' => 'Each file must be an image.',
                    'photos.*.mimes' => 'Supported image formats: jpeg, png, jpg, gif.',
                    'photos_data.*.is_main.boolean' => 'The "is_main" field for photos must be true or false.',
                ];
            }

            return [
                'name.required' => 'Поле "Ім\'я" є обов\'язковим.',
                'name_en.required' => 'Поле "Name" є обов\'язковим.',
                'type.required' => 'Поле "Вид" є обов\'язковим.',
                'type_en.required' => 'Поле "Type" є обов\'язковим.',
                'gender.required' => 'Поле "Стать" є обов\'язковим.',
                'gender.boolean' => 'Поле "Стать" має бути булевим (true/false).',
                'age_years.required' => 'Поле "Кількість років" є обов\'язковим.',
                'age_years.integer' => 'Поле "Кількість років" має бути цілим числом.',
                'age_years.min' => 'Поле "Кількість років" не може бути від\'ємним.',
                'age_months.required' => 'Поле "Кількість місяців" є обов\'язковим.',
                'age_months.integer' => 'Поле "Кількість місяців" має бути цілим числом.',
                'age_months.min' => 'Поле "Кількість місяців" не може бути від\'ємним.',
                'age_months.max' => 'Поле "Кількість місяців" не може перевищувати 11.',
                'is_sterilized.required' => 'Поле "Стерилізація" є обов\'язковим.',
                'size.required' => 'Поле "Розмір" є обов\'язковим.',
                'size_en.required' => 'Поле "Size (English)" є обов\'язковим.',
                'photos.*.image' => 'Кожен файл має бути зображенням.',
                'photos.*.mimes' => 'Підтримуються тільки формати: jpeg, png, jpg, gif.',
                'photos_data.*.is_main.boolean' => 'Поле "is_main" для фото має бути булевим (true/false).',
            ];
        }
    }
    