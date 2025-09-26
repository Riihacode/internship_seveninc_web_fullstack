<?php

namespace App\Http\Requests;

use App\Models\StockTransaction;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', StockTransaction::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:Masuk,Keluar'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'date'       => ['required', 'date'],
            'notes'      => ['nullable', 'string'],
            'reference'  => ['nullable', 'string', 'max:64'],
            'unit_cost'  => ['nullable', 'numeric', 'min:0'],
        ];

        // supplier_id hanya wajib jika role Manager & type = Masuk
        if (in_array($this->user()->role, ['Admin', 'Manager Gudang']) && $this->input('type') === 'Masuk' ) {
            $rules['supplier_id'] = ['required', 'exists:suppliers,id'];
        } else {
            $rules['supplier_id'] = ['nullable', 'exists:suppliers,id'];
        }

        return $rules;
    }
}
