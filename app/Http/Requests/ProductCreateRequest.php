<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCreateRequest extends FormRequest
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
        switch ($this->getMethod()) {
            //创建
            case 'post':
            case 'POST':
            return [
                'category_id' => 'required',
                'name' => 'required',
            ];

            //修改
            case 'put':
            case 'PUT':
                return [
                    'category_id' => 'required',
                'name' => ['required', Rule::unique('products')->ignore($this->input('id'))],

                ];
        }

        return [];

    }

//    public function messages()
//    {
//        return [
//            'certification.required' => '认证类型未提供',
//            'certification.exists' => '认证类型不存在',
//            'id.required' => '证件号未提供',
//            'contact.required' => '联系方式未提供',
//            'desc.required' => '认证描述未提供',
//            'desc.max' => '认证描述长度最大 250 个字',
//            'files.required' => '证件照片未提供',
//            'files.*.required_with' => '非法提交',
//            'files.*.exists' => '文件不存在或已被使用',
//        ];
//    }
}
