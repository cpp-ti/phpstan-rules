<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules;

use Cppti\PHPStanRules\Rules\DisallowLegacyAccessorsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class DisallowLegacyAccessorsRuleTest extends RuleTestCase
{
    public function testLegacyAccessorsAreNotAllowed(): void
    {
        $this->analyse([__DIR__ . '/data/model-with-legacy-accessors.php'], [
            [
                'Legacy accessor "getNameAttribute" is not allowed. Use the new Attribute accessor syntax instead.',
                11,
            ],
            [
                'Legacy mutator "setNameAttribute" is not allowed. Use the new Attribute accessor syntax instead.',
                16,
            ],
        ]);
    }

    public function testNewAccessorsAreAllowed(): void
    {
        $this->analyse([__DIR__ . '/data/model-with-new-accessors.php'], []);
    }

    public function testNonModelClassIsAllowed(): void
    {
        $this->analyse([__DIR__ . '/data/non-model-with-get-attribute.php'], []);
    }

    protected function getRule(): Rule
    {
        return new DisallowLegacyAccessorsRule();
    }

    protected function shouldFailOnPhpErrors(): bool
    {
        return false;
    }
}
