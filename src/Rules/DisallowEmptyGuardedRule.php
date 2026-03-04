<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Property>
 */
class DisallowEmptyGuardedRule implements Rule
{
    public function getNodeType(): string
    {
        return Property::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        foreach ($node->props as $prop) {
            if ($prop->name->toString() !== 'guarded') {
                continue;
            }

            if (!$prop->default instanceof Array_) {
                continue;
            }

            if ($prop->default->items === []) {
                return [
                    RuleErrorBuilder::message(
                        'Setting $guarded to an empty array disables mass-assignment protection. Use $fillable to explicitly list allowed fields.'
                    )->identifier('cppti.disallowEmptyGuarded')->build(),
                ];
            }
        }

        return [];
    }
}
