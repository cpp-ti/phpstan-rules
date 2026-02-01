<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<StaticCall>
 */
class DisallowTableNameInValidationRuleRule implements Rule
{
    private const DISALLOWED_METHODS = ['unique', 'exists'];

    public function getNodeType(): string
    {
        return StaticCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->class instanceof Name) {
            return [];
        }

        $className = $node->class->toString();

        if ($className !== 'Rule' && $className !== 'Illuminate\Validation\Rule') {
            return [];
        }

        if (!$node->name instanceof Identifier) {
            return [];
        }

        $methodName = $node->name->toString();

        if (!in_array($methodName, self::DISALLOWED_METHODS, true)) {
            return [];
        }

        if (count($node->args) === 0) {
            return [];
        }

        $firstArg = $node->args[0];

        if (!$firstArg instanceof Node\Arg) {
            return [];
        }

        if (!$firstArg->value instanceof String_) {
            return [];
        }

        $tableName = $firstArg->value->value;

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Using table name "%s" directly in Rule::%s() is not allowed. Use Model::class instead.',
                    $tableName,
                    $methodName
                )
            )->build(),
        ];
    }
}