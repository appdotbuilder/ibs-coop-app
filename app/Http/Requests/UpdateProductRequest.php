<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')->id;

        return [
            'sku' => 'required|string|max:50|unique:products,sku,' . $productId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0|gte:purchase_price',
            'member_price' => 'nullable|numeric|min:0|lte:selling_price',
            'stock_quantity' => 'integer|min:0',
            'minimum_stock' => 'integer|min:0',
            'unit' => 'required|string|max:20',
            'is_active' => 'boolean',
            'allow_installment' => 'boolean',
            'points_earned' => 'integer|min:0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sku.required' => 'Product SKU is required.',
            'sku.unique' => 'This SKU is already used by another product.',
            'name.required' => 'Product name is required.',
            'category.required' => 'Product category is required.',
            'purchase_price.required' => 'Purchase price is required.',
            'selling_price.required' => 'Selling price is required.',
            'selling_price.gte' => 'Selling price must be greater than or equal to purchase price.',
            'member_price.lte' => 'Member price must be less than or equal to selling price.',
            'unit.required' => 'Product unit is required.',
        ];
    }
}