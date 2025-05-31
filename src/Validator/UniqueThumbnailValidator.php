<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Post;

/**
 * Custom validator for the UniqueThumbnail constraint.
 * This validator ensures that a Post object has only one 
 * image or video marked as a thumbnail.
 */
class UniqueThumbnailValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * 
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        // Check if the constraint is the correct type, otherwise exit
        if (!$constraint instanceof UniqueThumbnail || !$value instanceof Post) {
            return;
        }

        // All images and videos are grouped together in a single array
        $allMedia = array_merge(
            iterator_to_array($value->getImages()),
            iterator_to_array($value->getVideos())
        );

        // Browse & count the number of media that have the ‘isThumbnail’ flag set to true.
        $thumbnailCount = count(array_filter($allMedia, fn($media) => $media->getIsThumbnail()));

        // If more than one thumbnail is found, a constraint violation is added
        if ($thumbnailCount > 1) {
            $this->context
                ->buildViolation($constraint->message) // Error message to be displayed
                ->addViolation(); // Registers the violation in context
        }
    }
}
