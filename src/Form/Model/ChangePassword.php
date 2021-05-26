<?php
/**
 * Change password helper.
 */

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

/**
 * Class ChangePassword.
 */
class ChangePassword
{
    /**
     * Old password.
     *
     * @SecurityAssert\UserPassword()
     */
    protected $oldPassword;

    /**
     * Password.
     *
     * @var string
     */
    protected $password;

    /**
     * Getter for oldPassword.
     *
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Setter for old Password.
     *
     * @param string $oldPassword
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * Getter for password.
     *
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Setter for password.
     *
     * @param string $newPassword
     */
    public function setPassword($newPassword)
    {
        $this->password = $newPassword;
    }
}
