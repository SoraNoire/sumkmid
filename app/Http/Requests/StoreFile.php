<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fileUpload.*' => 'mimes:pdf,doc,dot,docx,xlsx,xml,ppt,ppa,pptx,ppsx,mdb,txt,zip,rar'
        ];
    }
}
