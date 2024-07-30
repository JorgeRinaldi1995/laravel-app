<?php

namespace App\Http\Requests\ProductResources;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        if($user->role === 0 || $user->role === 1){
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:products',
            'price_per_unit' => 'required|numeric',
            'basic_unit' => 'nullable|string',
            'tax_percentage' => 'nullable|numeric',
            'limited' => 'required|boolean',
            'stock' => 'nullable|numeric',
        ];
    }
}
