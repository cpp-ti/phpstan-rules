<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules\Data;

class NonModelWithGetAttribute
{
    public function getNameAttribute(string $value): string
    {
        return ucfirst($value);
    }
}
