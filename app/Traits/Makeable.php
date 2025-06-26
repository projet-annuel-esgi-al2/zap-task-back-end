<?php

namespace App\Traits;

trait Makeable
{
    public static function make(...$args): static
    {
        /** @phpstan-ignore-next-line  */
        return new static(...$args);
    }
}
