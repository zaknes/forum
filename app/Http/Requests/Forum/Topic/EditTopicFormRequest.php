<?php

namespace Forum\Http\Requests\Forum\Topic;

use Forum\Http\Requests\Request;

class EditTopicFormRequest extends Request
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
            'title' => 'required',
            'body' => 'required',
            'section_id' => 'required|exists:sections,id',
        ];
    }
}
