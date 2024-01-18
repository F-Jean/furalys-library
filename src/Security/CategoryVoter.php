<?php

namespace App\Security;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * CategoryVoter class
 */
class CategoryVoter extends Voter
{
    private const AUTHORIZE = 'authorize';
    private Security $security;

    /**
     * CategoryVoter constructor
     *
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return boolean
     */
    protected function supports(
        string $attribute,
        mixed $subject
    ): bool {
        // If the attribute isn't one we support, return false.
        if (!in_array($attribute, [self::AUTHORIZE])) {
            return false;
        }

        // Only vote on `Category` objects.
        if (!$subject instanceof Category) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return boolean
     */
    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token
    ): bool {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // The user must be logged in; if not, deny access.
            return false;
        }

        // You know $subject is a Category object, thanks to `supports()`.
        /** @var Category $category */
        $category = $subject;

        return match ($attribute) {
            self::AUTHORIZE => $this->canHandle($category, $user),
            default => throw new \LogicException(
                'You don\'t have the rights.'
            )
        };
    }

    /* A category can be handled by their author or an admin
    and an anonyme category can be handle only by an admin */
    /**
     * @param Category $category
     * @param User $user
     * @return boolean
     */
    private function canHandle(Category $category, User $user): bool
    {
        return $user === $category->getUser() || $this->security->isGranted('ROLE_ADMIN');
    }
}