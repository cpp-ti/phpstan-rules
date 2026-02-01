<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules\Data;

use App\Models\User;
use App\Models\Post;
use Illuminate\Validation\Rule;

$unique = Rule::unique(User::class);
$exists = Rule::exists(Post::class);

// Using table method is also allowed
$uniqueWithTable = Rule::unique((new User)->getTable());