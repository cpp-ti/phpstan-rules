<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules\Data;

use Illuminate\Validation\Rule;

$unique = Rule::unique('users');
$exists = Rule::exists('posts');