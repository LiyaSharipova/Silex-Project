<?php
/**
 * Created by PhpStorm.
 * User: liya
 * Date: 23.05.17
 * Time: 23:36
 */

namespace Model;


use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Model\User;


class UserProvider extends BaseModel implements UserProviderInterface
{

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $stmt = $this->db->executeQuery('SELECT * FROM user WHERE login = ?', array(strtolower($username)));

        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
        $currentUser = new User($user['login'], $user['password'], explode(',', $user['roles']), true, true, true, true);
        $currentUser->setPhoto("PHOTO");
        return $currentUser;


    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instance of "%s" are not supported'), get_class($user));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
        return $class === '\Model\User';
    }
}