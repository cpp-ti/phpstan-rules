<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules\Data;

use Illuminate\Database\Eloquent\Model;

class ModelWithLegacyAccessors extends Model
{
    public function getNameAttribute(string $value): string
    {
        return ucfirst($value);
    }

    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = strtolower($value);
    }
}
