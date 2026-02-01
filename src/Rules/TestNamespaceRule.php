<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Node\Stmt\Class_>
 */
class TestNamespaceRule implements Rule
{
    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $file = $scope->getFile();

        if (!$this->isTestFile($file)) {
            return [];
        }

        $namespace = $scope->getNamespace();

        if ($namespace === null || (!str_starts_with($namespace, 'Tests\\') && $namespace !== 'Tests')) {
            return [
                RuleErrorBuilder::message('Test classes must have a namespace starting with "Tests\\".')
                    ->identifier('cppti.testNamespace')
                    ->build(),
            ];
        }

        return [];
    }

    private function isTestFile(string $file): bool
    {
        return str_ends_with($file, 'Test.php');
    }
}