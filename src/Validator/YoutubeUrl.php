<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

// PHP8+ attribute: indicates that this class can be used as attribute
// It can be placed on properties or methods and used several times (IS_REPEATABLE).
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
//Declares a final (non-extensible) class that represents a custom constraint
final class YoutubeUrl extends Constraint
{
    // Default error message if validation fails.
    // This message is a translation key (in the ‘validators’ domain)
    public string $message = 'video.url.not_youtube';

    // Initialise custom options
    public function __construct(
        ?string $message = null, // Allows to override the default message if required
        ?array $groups = null, // Validation groups (optional, manages different use cases)
        mixed $payload = null // Free payload that can be used in the validator (e.g. information for debugging or logs)
    ) {
        // Calls the constructor of the parent class (Constraint)
        // The first parameter is an array of options, in this case empty (because the properties are managed manually)
        parent::__construct([], $groups, $payload);

        // If a custom message is supplied, the default value is overwritten
        if ($message !== null) {
            $this->message = $message;
        }
    }
}
