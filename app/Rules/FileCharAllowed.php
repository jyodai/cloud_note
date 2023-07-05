<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileCharAllowed implements Rule
{
    private $message = '';

    public function __construct()
    {
    }

    public function passes($attribute, $value): bool
    {
        if (gettype($value) !== 'string') {
            $fileName = $value->getClientOriginalName();
        } else {
            $fileName = $value;
        }
        if (preg_match('#[\\\:?<>|]|\.{1,2}/#', $fileName)) {
            $this->message = $fileName . 'に使用できな文字が含まれています。「\,:,?,<,>,|」、「./」、「../」';
            return false;
        }
        return true;
    }

    public function message(): string
    {
        return $this->message;
    }
}
