<?php

namespace App\Tests\Unit\Persistence\Entity;

use App\Persistence\Entity\ExperienceLevel;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class ExperienceLevelTest extends TestCase
{

    protected function provideTestCases(): array {
        return [
            'junior' => [
                'salary' => 1_000_00,
                'expected_level' => ExperienceLevel::Junior,
            ],
            'regular-min' => [
                'salary' => 5_000_00,
                'expected_level' => ExperienceLevel::Regular,
            ],
            'regular' => [
                'salary' => 8_000_00,
                'expected_level' => ExperienceLevel::Regular,
            ],
            'regular-max' => [
                'salary' => 9_999_00,
                'expected_level' => ExperienceLevel::Regular,
            ],
            'senior-min' => [
                'salary' => 10_000_00,
                'expected_level' => ExperienceLevel::Senior,
            ],
            'senior' => [
                'salary' => 10_500_00,
                'expected_level' => ExperienceLevel::Senior,
            ],
        ];
    }

    /**
     * @dataProvider provideTestCases
     */
    public function testExperienceIsEstimatedCorrectly(
        int $salary,
        ExperienceLevel $expectedLevel,
    ): void
    {
        $this->assertEquals(
            $expectedLevel,
            ExperienceLevel::estimateBaseOnSalary($salary)
        );
    }

    public function testEstimationThrowsOnNegativeSalary(): void
    {
        $this->expectException(InvalidArgumentException::class);
        ExperienceLevel::estimateBaseOnSalary(-1);
    }
}
