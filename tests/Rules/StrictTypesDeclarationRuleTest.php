<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules;

use Cppti\PHPStanRules\Rules\StrictTypesDeclarationRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class StrictTypesDeclarationRuleTest extends RuleTestCase {

    public function testRuleWithMissingStrictTypes(): void {
        $this->analyse([__DIR__ . '/data/missing-strict-types.php'], [
            [
                'File is missing declare(strict_types=1) declaration at the beginning.',
                1,
            ],
        ]);
    }

    public function testRuleWithStrictTypes(): void {
        $this->analyse([__DIR__ . '/data/with-strict-types.php'], []);
    }

    protected function getRule(): Rule {
        return new StrictTypesDeclarationRule();
    }
}
