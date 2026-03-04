<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Tests\Rules;

use Cppti\PHPStanRules\Rules\DisallowEnvUsageRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<DisallowEnvUsageRule>
 */
class DisallowEnvUsageRuleTest extends RuleTestCase
{
    public function testEnvOutsideAllowedPaths(): void
    {
        $this->analyse([__DIR__ . '/data/env-outside-config.php'], [
            [
                'Using env() outside configuration files is not allowed. Use config() instead. env() returns null when config is cached.',
                7,
            ],
            [
                'Using env() outside configuration files is not allowed. Use config() instead. env() returns null when config is cached.',
                8,
            ],
        ]);
    }

    public function testEnvInsideAllowedPaths(): void
    {
        $this->analyse([__DIR__ . '/data/config/env-inside-config.php'], []);
    }

    protected function getRule(): Rule
    {
        return new DisallowEnvUsageRule();
    }
}
