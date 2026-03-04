<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules\Data;

use Illuminate\Database\Eloquent\Model;

class ModelWithoutGuarded extends Model
{
    public function getFullName(): string
    {
        return 'test';
    }
}
