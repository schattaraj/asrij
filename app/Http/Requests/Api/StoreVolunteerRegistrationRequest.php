<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreVolunteerRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'request_for' => $this->input('request_for', 'self'),
        ]);
    }

    public function rules(): array
    {
        $volunteerType = $this->input('volunteer_type');
        $isIndividual = in_array($volunteerType, ['individual', 'student'], true);
        $isOrganization = in_array($volunteerType, ['ngo', 'charity', 'club'], true);

        return [
            'request_for' => ['required', Rule::in(['self', 'other'])],
            'volunteer_type' => ['required', Rule::in(['individual', 'student', 'ngo', 'charity', 'club'])],

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile' => ['required', 'digits_between:10,15'],
            'address' => ['required', 'string', 'max:1000'],
            'volunteer_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'volunteer_longitude' => ['nullable', 'numeric', 'between:-180,180'],

            'blood_group' => [$isIndividual ? 'required' : 'nullable', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'dob' => [$isIndividual ? 'required' : 'nullable', 'date', 'before:today'],

            'institution' => [$volunteerType === 'student' ? 'required' : 'nullable', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:100'],
            'course' => ['nullable', 'string', 'max:150'],
            'year_of_study' => ['nullable', 'string', 'max:50'],

            'organization' => [$isOrganization ? 'required' : 'nullable', 'string', 'max:255'],
            'registration_number' => [$isOrganization ? 'required' : 'nullable', 'string', 'max:150'],
            'group_quantity' => [$isOrganization ? 'required' : 'nullable', 'integer', 'min:1', 'max:100000'],
            'president_name' => ['nullable', 'string', 'max:255'],
            'president_number' => ['nullable', 'digits_between:10,15'],
            'secretary_name' => ['nullable', 'string', 'max:255'],
            'secretary_number' => ['nullable', 'digits_between:10,15'],
            'account_name' => ['nullable', 'string', 'max:255'],
            'account_number' => ['nullable', 'string', 'max:50'],

            'member_name' => ['nullable', 'array'],
            'member_name.*' => ['nullable', 'string', 'max:255'],
            'member_contact_number' => ['nullable', 'array'],
            'member_contact_number.*' => ['nullable', 'digits_between:10,15'],
            'member_position' => ['nullable', 'array'],
            'member_position.*' => ['nullable', 'string', 'max:100'],
            'members' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'volunteer_type.in' => 'Please choose a valid volunteer type.',
            'blood_group.required' => 'Blood group is required for individual and student volunteers.',
            'dob.required' => 'Date of birth is required for individual and student volunteers.',
            'institution.required' => 'Institution is required for student volunteers.',
            'organization.required' => 'Organization name is required.',
            'registration_number.required' => 'Registration number is required.',
            'group_quantity.required' => 'Group size is required.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first() ?: 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
