<?php

declare(strict_types=1);

namespace Cppti\PHPStanRules\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class StrictTypesDeclarationRule implements Rule {

    public function getNodeType(): string {
        return Node\Stmt\Namespace_::class;
    }

    public function processNode(Node $node, Scope $scope): array {
        $file = $scope->getFile();

        $contents = file_get_contents($file);
        if ($contents === false) {
            return [];
        }

        // Check if declare(strict_types=1) exists in the file
        // It should be one of the first statements after <?php
        if (!preg_match('/^<\?php\s+declare\s*\(\s*strict_types\s*=\s*1\s*\)\s*;/m', $contents)) {
            return [
                RuleErrorBuilder::message('File is missing declare(strict_types=1) declaration at the beginning.')
                    ->line(1)
                    ->build()
            ];
        }

        return [];
    }
}
