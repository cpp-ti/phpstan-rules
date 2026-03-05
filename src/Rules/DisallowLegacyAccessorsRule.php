<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Rules;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<ClassMethod>
 */
class DisallowLegacyAccessorsRule implements Rule
{
    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $methodName = $node->name->toString();

        if (! preg_match('/^(get|set)[A-Z].*Attribute$/', $methodName)) {
            return [];
        }

        if (! $this->isInsideModel($scope)) {
            return [];
        }

        $type = str_starts_with($methodName, 'get') ? 'accessor' : 'mutator';

        return [
            RuleErrorBuilder::message(
                sprintf(
                    'Legacy %s "%s" is not allowed. Use the new Attribute accessor syntax instead.',
                    $type,
                    $methodName,
                )
            )->identifier('cppti.disallowLegacyAccessors')->build(),
        ];
    }

    private function isInsideModel(Scope $scope): bool
    {
        $classReflection = $scope->getClassReflection();

        if ($classReflection === null) {
            return false;
        }

        $modelClass = 'Illuminate\Database\Eloquent\Model';

        if (in_array($modelClass, $classReflection->getParentClassesNames(), true)) {
            return true;
        }

        return $classReflection->getName() === $modelClass;
    }
}
