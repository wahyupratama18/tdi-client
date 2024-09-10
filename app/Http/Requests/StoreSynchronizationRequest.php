<?php

namespace App\Http\Requests;

use App\Enums\SyncList;
use App\Models\Classroom;
use App\Models\Synchronization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSynchronizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', [Synchronization::class, SyncList::tryFrom($this->sync)]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sync' => ['required', 'string', Rule::in(SyncList::cases())],
            'id' => ['required_if:sync,students', 'nullable', Rule::exists(Classroom::class)],
        ];
    }
}
