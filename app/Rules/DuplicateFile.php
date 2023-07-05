<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DuplicateFile implements Rule
{
    private $path    = '';
    private $message = '';

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function passes($attribute, $value): bool
    {
        $fileName = $value->getClientOriginalName();
        if (file_exists($this->path . $fileName)) {
            $this->message = "$fileName は既に使用されています";
            return false;
        }
        return true;
    }

    public function message(): string
    {
        return $this->message;
    }
}
