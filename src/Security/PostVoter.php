<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * PostVoter class
 */
class PostVoter extends Voter
{
    private const AUTHORIZE = 'authorize';
    private Security $security;

    /**
     * PostVoter constructor
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

        // Only vote on `Post` objects.
        if (!$subject instanceof Post) {
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

        // You know $subject is a Post object, thanks to `supports()`.
        /** @var Post $post */
        $post = $subject;

        return match ($attribute) {
            self::AUTHORIZE => $this->canHandle($post, $user),
            default => throw new \LogicException(
                'You don\'t have the rights.'
            )
        };
    }

    /* A post can be handled by their author or an admin
    and an anonyme post can be handle only by an admin */
    /**
     * @param Post $post
     * @param User $user
     * @return boolean
     */
    private function canHandle(Post $post, User $user): bool
    {
        return $user === $post->getUser() || $this->security->isGranted('ROLE_ADMIN');
    }
}