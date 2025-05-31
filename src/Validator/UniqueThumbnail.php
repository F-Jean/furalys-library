<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class UniqueThumbnail extends Constraint
{
    /** @var string */
    public string $message = 'Only one thumbnail is allowed per post.';

    /**
     * Spécifie que cette contrainte s'applique à la classe entière.
     *
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
