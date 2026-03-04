<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules;

use Cppti\PHPStanRules\Rules\DisallowEmptyGuardedRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class DisallowEmptyGuardedRuleTest extends RuleTestCase
{
    public function testEmptyGuardedIsNotAllowed(): void
    {
        $this->analyse([__DIR__ . '/data/model-with-empty-guarded.php'], [
            [
                'Setting $guarded to an empty array disables mass-assignment protection. Use $fillable to explicitly list allowed fields.',
                11,
            ],
        ]);
    }

    public function testNonEmptyGuardedIsAllowed(): void
    {
        $this->analyse([__DIR__ . '/data/model-with-guarded.php'], []);
    }

    public function testFillableIsAllowed(): void
    {
        $this->analyse([__DIR__ . '/data/model-with-fillable.php'], []);
    }

    public function testModelWithoutGuardedIsAllowed(): void
    {
        $this->analyse([__DIR__ . '/data/model-without-guarded.php'], []);
    }

    protected function getRule(): Rule
    {
        return new DisallowEmptyGuardedRule();
    }
}
