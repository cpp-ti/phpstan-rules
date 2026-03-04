<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<FuncCall>
 */
class DisallowEnvUsageRule implements Rule
{
    /** @var list<string> */
    private array $allowedPaths;

    /** @param list<string> $allowedPaths */
    public function __construct(array $allowedPaths = ['config/'])
    {
        $this->allowedPaths = $allowedPaths;
    }

    public function getNodeType(): string
    {
        return FuncCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Name) {
            return [];
        }

        if ($node->name->toString() !== 'env') {
            return [];
        }

        $file = $scope->getFile();

        foreach ($this->allowedPaths as $allowedPath) {
            if (str_contains($file, $allowedPath)) {
                return [];
            }
        }

        return [
            RuleErrorBuilder::message(
                'Using env() outside configuration files is not allowed. Use config() instead. env() returns null when config is cached.'
            )->identifier('cppti.disallowEnvUsage')->build(),
        ];
    }
}
