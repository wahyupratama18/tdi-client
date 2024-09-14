<?php

namespace App\Http\Requests;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Attendance::class);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'student' => Student::query()
                ->where('qr', $this->qr)
                ->with('classroom')
                ->first(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'nim' => ['required', 'string', Rule::exists(Student::class, 'nim')],
            'qr' => ['required', 'string', Rule::exists(Student::class, 'qr')],
        ];
    }
}
