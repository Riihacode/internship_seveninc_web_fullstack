<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        // Hanya Manager/Admin boleh update transaksi (approve/reject/dispatch)
        return $this->user()->can('update', $this->route('transaction'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'status'    => ['required', 'in:Diterima,Ditolak,Dikeluarkan'],
            'notes'     => ['nullable', 'string'],
        ];
    }
}
