<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdoptionRequest extends FormRequest
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
            'animalID' => 'required|exists:animals,animalID',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'reason' => 'required|string',
            'experience' => 'required|string',
            'living_conditions' => 'required|string',
            'other_pets' => 'required|string',
            'agreement' => 'required|accepted'
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
                'animalID.required' => 'Please select an animal',
                'animalID.exists' => 'Selected animal does not exist',
                'first_name.required' => 'Please enter your first name',
                'last_name.required' => 'Please enter your last name',
                'phone.required' => 'Please enter your phone number',
                'email.required' => 'Please enter your email address',
                'email.email' => 'Please enter a valid email address',
                'address.required' => 'Please enter your address',
                'city.required' => 'Please enter your city',
                'reason.required' => 'Please explain why you want to adopt',
                'experience.required' => 'Please describe your experience with pets',
                'living_conditions.required' => 'Please describe your living conditions',
                'other_pets.required' => 'Please tell us about your other pets',
                'agreement.required' => 'You must agree to the adoption terms',
                'agreement.accepted' => 'You must accept the adoption terms'
            ];
        }

        return [
            // Повідомлення про помилки для обов'язкових полів
            'animalID.required' => 'Будь ласка, виберіть тварину',
            'animalID.exists' => 'Вибрана тварина не існує',
            'first_name.required' => 'Будь ласка, введіть ваше ім\'я',
            'last_name.required' => 'Будь ласка, введіть ваше прізвище',
            'phone.required' => 'Будь ласка, введіть ваш номер телефону',
            'email.required' => 'Будь ласка, введіть вашу електронну адресу',
            'email.email' => 'Введіть коректну електронну адресу',
            'address.required' => 'Будь ласка, введіть вашу адресу',
            'city.required' => 'Будь ласка, введіть ваше місто',
            'reason.required' => 'Будь ласка, обґрунтуйте, чому ви хочете взяти тварину до себе',
            'experience.required' => 'Будь ласка, опишіть ваш досвід з домашніми тваринами',
            'living_conditions.required' => 'Будь ласка, опишіть ваші умови проживання',
            'other_pets.required' => 'Будь ласка, розкажіть нам про ваших інших домашніх тварин',
            'agreement.required' => 'Ви повинні погодитися з умовами узгодження',
            'agreement.accepted' => 'Ви повинні погодитися з умовами узгодження'
        ];
    }

    public function attributes()
    {
        return [
            'animalID' => 'Тварина',
            'first_name' => 'Ім\'я',
            'last_name' => 'Прізвище',
            'phone' => 'Номер телефону',
            'email' => 'Електронна адреса',
            'address' => 'Адреса',
            'city' => 'Місто',
            'reason' => 'Причина узгодження',
            'experience' => 'Досвід з домашніми тваринами',
            'living_conditions' => 'Умови проживання',
            'other_pets' => 'Інші домашні тварини',
            'agreement' => 'Угода узгодження'
        ];
    }
} 