<?php

namespace App\Security;

use App\Entity\Artist;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * ArtistVoter class
 */
class ArtistVoter extends Voter
{
    private const AUTHORIZE = 'authorize';
    private Security $security;

    /**
     * ArtistVoter constructor
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

        // Only vote on `Artist` objects.
        if (!$subject instanceof Artist) {
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

        // You know $subject is a Artist object, thanks to `supports()`.
        /** @var Artist $artist */
        $artist = $subject;

        return match ($attribute) {
            self::AUTHORIZE => $this->canHandle($artist, $user),
            default => throw new \LogicException(
                'You don\'t have the rights.'
            )
        };
    }

    /* An artist can be handled by their author or an admin
    and an anonyme artist can be handle only by an admin */
    /**
     * @param Artist $artist
     * @param User $user
     * @return boolean
     */
    private function canHandle(Artist $artist, User $user): bool
    {
        return $user === $artist->getUser() || $this->security->isGranted('ROLE_ADMIN');
    }
}