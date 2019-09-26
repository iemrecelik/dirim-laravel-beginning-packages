<?php
return function ($params) {

extract($params);

$fieldsHtml = '';
foreach ($fields['langFields'] as $flangVal) {
    $fieldsHtml .= str_repeat(' ', 12)
                ."'langs.*.{$flangVal['name']}' => 'required',\n";
}

foreach ($fields['fields'] as $fval) {
    $fieldsHtml .= str_repeat(' ', 12)."'{$fval['name']}' => 'required',\n";
}

if ($fields['images']) {
    $imgHtml = '
            \'images\' => \'array\',
            \'images.*.file\' => \'
                file|max:2500|
                mimes:jpeg,jpg,png,gif|
                nullable
            \',
    ';
    $fieldsHtml .= str_repeat(' ', 12).trim($imgHtml);
}

return '<?php

namespace App\Http\Requests'.$namespace.';

use Illuminate\Foundation\Http\FormRequest;

class '.$className.' extends FormRequest
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
            '.trim($fieldsHtml).'
        ];
    }
}
';
};
