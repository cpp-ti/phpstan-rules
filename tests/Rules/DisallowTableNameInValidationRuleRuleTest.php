<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules;

use Cppti\PHPStanRules\Rules\DisallowTableNameInValidationRuleRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DisallowTableNameInValidationRuleRule>
 */
class DisallowTableNameInValidationRuleRuleTest extends RuleTestCase
{
    public function testRuleWithTableNameString(): void
    {
        $this->analyse([__DIR__ . '/data/validation-rule-with-table-name.php'], [
            [
                'Using table name "users" directly in Rule::unique() is not allowed. Use Model::class instead.',
                9,
            ],
            [
                'Using table name "posts" directly in Rule::exists() is not allowed. Use Model::class instead.',
                10,
            ],
        ]);
    }

    public function testRuleWithModelClass(): void
    {
        $this->analyse([__DIR__ . '/data/validation-rule-with-model-class.php'], []);
    }

    protected function getRule(): Rule
    {
        return new DisallowTableNameInValidationRuleRule();
    }
}