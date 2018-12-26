<?php

namespace App\Http\Requests\Goods;

use Dingo\Api\Http\FormRequest;

class Store extends FormRequest
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
            'id' => 'required|integer',
            // 'pid' => 'required|integer',
            // 'content' => ['required', new Comment],
        ];
    }

    /**
     * 定义字段名中文
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'id' => '货柜id',
            // 'pid' => '父级id',
            // 'content' => '内容',
        ];
    }
}
