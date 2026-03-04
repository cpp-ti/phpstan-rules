<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules\Data;

use Illuminate\Database\Eloquent\Model;

class ModelWithFillable extends Model
{
    protected $fillable = ['name', 'email'];
}
