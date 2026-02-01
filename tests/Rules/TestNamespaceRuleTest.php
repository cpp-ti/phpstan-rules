<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules;

use Cppti\PHPStanRules\Rules\TestNamespaceRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<TestNamespaceRule>
 */
class TestNamespaceRuleTest extends RuleTestCase
{
    public function testRuleWithWrongNamespace(): void
    {
        $this->analyse([__DIR__ . '/data/WrongNamespaceTest.php'], [
            [
                'Test classes must have a namespace starting with "Tests\".',
                7,
            ],
        ]);
    }

    public function testRuleWithCorrectNamespace(): void
    {
        $this->analyse([__DIR__ . '/data/CorrectNamespaceTest.php'], []);
    }

    public function testRuleIgnoresNonTestFiles(): void
    {
        $this->analyse([__DIR__ . '/data/regular-class-not-test.php'], []);
    }

    protected function getRule(): Rule
    {
        return new TestNamespaceRule();
    }
}