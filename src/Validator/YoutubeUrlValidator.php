<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Validator\YoutubeUrl;

class YoutubeUrlValidator extends ConstraintValidator
{
    /**
     * Main method called by Symfony's Validator component.
     * It receives the value to be validated and the associated constraint instance.
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        // Checks that the constraint is an instance of YoutubeUrl
        if (!$constraint instanceof YoutubeUrl) {
            //Otherwise, throws an exception to indicate misuse
            throw new \LogicException(sprintf('%s can only validate YoutubeUrl constraints.', __CLASS__));
        }

        // If the value is empty or zero, nothing is done (no error raised here)
        // This allows other constraints (such as NotBlank) to manage empty values
        if (null === $value || '' === $value) {
            return;
        }

        // Regular expression that checks whether the URL corresponds to a valid YouTube format
        if (!preg_match(
            // Accepted formats include: youtube.com/watch?v=..., youtube.com/shorts/..., youtube.com/embed/... and youtu.be/...
            '/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|shorts\/|embed\/)|youtu\.be\/)/',
            $value
        )) {
            // If the URL does not match, a validation error is generated
            // The message comes from the ‘message’ property of the YoutubeUrl constraint.
            // The {{ value }} parameter will be replaced by the invalidated value in the error message displayed
            $this->context->buildViolation($constraint->message)
                // Inject the invalid value into the message (if the message uses {{ value }})
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
