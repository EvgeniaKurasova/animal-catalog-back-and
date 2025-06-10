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
                'name' => 'required|string|max:255',
                'name_en' => 'required|string|max:255',
                'type_id' => 'required|exists:types,type_id',
                'gender' => 'required|boolean',
                'age_years' => 'required|integer|min:0',
                'age_months' => 'required|integer|min:0|max:11',
                'size_id' => 'required|exists:sizes,size_id',
                'sterilization' => 'nullable|string|max:255',
                'sterilization_en' => 'nullable|string|max:255',
                'additional_information' => 'nullable|string',
                'additional_information_en' => 'nullable|string',
                'age_updated_at' => 'nullable|date',
                'photos' => 'array|nullable',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif',
                'photos_data' => 'array|nullable',
                'photos_data.*.is_main' => 'boolean',
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
                    'type_id.required' => 'Please enter the animal type.',
                    'gender.required' => 'Please select the animal\'s gender.',
                    'gender.boolean' => 'The gender field must be true or false.',
                    'age_years.required' => 'Please enter the animal\'s age in years.',
                    'age_years.integer' => 'Age in years must be an integer.',
                    'age_years.min' => 'Age in years cannot be negative.',
                    'age_months.required' => 'Please enter the animal\'s age in months.',
                    'age_months.integer' => 'Age in months must be an integer.',
                    'age_months.min' => 'Age in months cannot be negative.',
                    'age_months.max' => 'Age in months cannot exceed 11.',
                    'size_id.required' => 'Please enter the animal\'s size.',
                    'sterilization.string' => 'The sterilization status in English must be a string.',
                    'sterilization_en.string' => 'The sterilization status in English must be a string.',
                    'photos.*.image' => 'Each file must be an image.',
                    'photos.*.mimes' => 'Supported image formats: jpeg, png, jpg, gif.',
                    'photos_data.*.is_main.boolean' => 'The "is_main" field for photos must be true or false.',
                ];
            }

            return [
                'name.required' => 'Поле "Ім\'я" є обов\'язковим.',
                'name_en.required' => 'Поле "Name" є обов\'язковим.',
                'type_id.required' => 'Поле "Вид" є обов\'язковим.',
                'gender.required' => 'Поле "Стать" є обов\'язковим.',
                'gender.boolean' => 'Поле "Стать" має бути булевим (true/false).',
                'age_years.required' => 'Поле "Кількість років" є обов\'язковим.',
                'age_years.integer' => 'Поле "Кількість років" має бути цілим числом.',
                'age_years.min' => 'Поле "Кількість років" не може бути від\'ємним.',
                'age_months.required' => 'Поле "Кількість місяців" є обов\'язковим.',
                'age_months.integer' => 'Поле "Кількість місяців" має бути цілим числом.',
                'age_months.min' => 'Поле "Кількість місяців" не може бути від\'ємним.',
                'age_months.max' => 'Поле "Кількість місяців" не може перевищувати 11.',
                'size_id.required' => 'Поле "Розмір" є обов\'язковим.',
                'sterilization.string' => 'Поле "Стерилізація (English)" має бути рядком.',
                'sterilization_en.string' => 'Поле "Стерилізація (English)" має бути рядком.',
                'photos.*.image' => 'Кожен файл має бути зображенням.',
                'photos.*.mimes' => 'Підтримуються тільки формати: jpeg, png, jpg, gif.',
                'photos_data.*.is_main.boolean' => 'Поле "is_main" для фото має бути булевим (true/false).',
            ];
        }
    }
    