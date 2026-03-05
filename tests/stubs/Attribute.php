<?php

namespace Illuminate\Database\Eloquent\Casts;

class Attribute
{
    public static function make(?callable $get = null, ?callable $set = null): self
    {
        return new self();
    }
}
