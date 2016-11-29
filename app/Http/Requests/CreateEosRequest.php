<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'name' => 'required',
          'description'=> 'required',
          'dimX'=> 'required',
          'dimY'=> 'required',
          'dimZ'=> 'required',
          'number_of_parts'=> 'required',
          // 'project_id'=> 'required',
          'stl'=> 'required'
        ];
    }
}
