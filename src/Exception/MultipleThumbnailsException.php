<?php
// src/Exception/MultipleThumbnailsException.php
namespace App\Exception;

use LogicException;

/**
 * Exception thrown when a post has more than one media marked as a thumbnail.
 */
class MultipleThumbnailsException extends \LogicException
{
    public function __construct()
    {
        parent::__construct('Un post ne peut avoir qu\'une seule miniature.');
    }
}
