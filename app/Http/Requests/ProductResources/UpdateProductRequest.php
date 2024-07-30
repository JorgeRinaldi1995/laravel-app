<?php

namespace App\Http\Requests\ProductResources;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $product = Product::find($this->route('product')); // Assuming 'product' is the route parameter name

        // Allow admins to update the active_for_sale attribute
        // Allow managers to update their own products (but not the active_for_sale attribute)
        $user = Auth::user();
        if ($user->role === 0 || ($user->role === 1 && $product && $product->manager_id === $user->id)) {
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
            'name' => 'sometimes|required|string|unique:products,name,' . $this->route('product'),
            'price_per_unit' => 'sometimes|required|numeric',
            'basic_unit' => 'sometimes|nullable|string',
            'tax_percentage' => 'sometimes|nullable|numeric',
            'limited' => 'sometimes|required|boolean',
            'stock' => 'sometimes|nullable|numeric',
            'active_for_sale' => 'sometimes|required|boolean',
        ];
    }
}
