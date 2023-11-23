<?php

namespace App\Persistence\Entity;

use Webmozart\Assert\Assert;

enum ExperienceLevel: int
{
    case Junior = 1;
    case Regular = 2;
    case Senior = 3;

    public static function estimateBaseOnSalary(int $salaryInCents): self
    {
        Assert::greaterThan($salaryInCents, 0);

        return match ($salaryInCents) {
            $salaryInCents < 5_000_00 => self::Junior,
            $salaryInCents < 10_000_00 => self::Regular,
            default => self::Senior,
        };
    }
}
