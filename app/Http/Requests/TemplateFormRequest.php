<?php

namespace App\Http\Requests;

use App\Template\Template;
use Illuminate\Foundation\Http\FormRequest;

class TemplateFormRequest extends FormRequest
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
        $template = Template::find($this->template);

        switch($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'name' => 'required|min:3|unique:templates',
                    'blocks' => 'required'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'name' => 'required|min:3|unique:templates,name,' . $template->id,
                    'blocks' => 'required'
                ];
            }
            default:
                break;
        }
    }
}
