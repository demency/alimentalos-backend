<?php

namespace App\Http\Requests;

class RefreshRequest extends AuthorizedRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @codeCoverageIgnore
     */
    public function rules()
    {
        return [
            'refresh_token' => 'required',
        ];
    }
}
