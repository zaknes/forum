<?php

namespace App\Http\Requests\Topic;

use App\Models\Topic;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTopic extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $topic = $this->route('topic');

        return $topic && request()->user()->can('update', $topic);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:8|max:64',
            'body' => 'required|min:16',
        ];
    }
}
