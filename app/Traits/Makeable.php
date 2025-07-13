<?php

/*
 * Author: Marc Malha
 * Version: 1.0
 */

namespace App\Traits;

trait Makeable
{
    public static function make(...$args): static
    {
        /** @phpstan-ignore-next-line  */
        return new static(...$args);
    }
}
