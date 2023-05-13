<?php
/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components\ldap;

use Adldap\Models\Attributes\AccountControl;
use app\models\User;
use Yii;
use Adldap\AdldapException;
use Adldap\Models\UserPasswordIncorrectException;
use Adldap\Models\UserPasswordPolicyException;

trait LdapUserTrait
{

    public function ldapStateUpdate()
    {
        if (! defined('ENABLE_LDAP')) {
            return true;
        }
        self::log('ldapStateUpdate : ' . $this->getState());
        $user = $this->ldapFindUser();
        if (! $user) {
            // local user
            return true;
        }
        $ac = $user->getUserAccountControlObject();
        self::log('getUserAccountControlObject  : ' . $ac->getValue());

        // Mark the account as enabled (normal).
        if ($this->state_id == User::STATE_ACTIVE) {
            $ac->remove(AccountControl::ACCOUNTDISABLE);
        } else {
            $ac->add(AccountControl::ACCOUNTDISABLE);
        }

        // Set the account control on the user and save it.
        $user->setUserAccountControl($ac);

        return $user->save();
    }

    /**
     */
    public function ldapAddUser()
    {
        if (! defined('ENABLE_LDAP') || 1 /* skip*/) {
            return true;
        }
        $username = $this->getUsername();

        if (Yii::$app->ldap->connect()) {
            $search = Yii::$app->ldap->getDefaultProvider()->search();

            self::log("Searching user :" . $username);

            $user = $search->findBy('samaccountname', $username);
            if ($user) {
                $this->addError('ldap already exists');
                return $user;
            }

            $user = Yii::$app->ldap->getDefaultProvider()
                ->make()
                ->user();

            // Create the users distinguished name.
            // We're adding an OU onto the users base DN to have it be saved in the specified OU.
            $dn = $user->getDnBuilder()->addOu('Users'); // Built DN will be: "CN=John Doe,OU=Users,DC=acme,DC=org";

            // Set the users DN, account name.
            $user->setDn($dn);
            $user->setAccountName($username);
            $user->setCommonName($this->full_name);
            $user->setEmail($this->email);
            $user->setDepartment((string) $this->department);

            // Set the users password.
            // NOTE: This password must obey your AD servers password requirements
            // (including password history, length, special characters etc.)
            // otherwise saving will fail and you will receive an
            // "LDAP Server is unwilling to perform" message.
            $user->setPassword('Welcome@ToXSL');

            // Get a new account control object for the user.
            $ac = $user->getUserAccountControlObject();

            // Mark the account as enabled (normal).
            $ac->accountIsNormal();

            // Set the account control on the user and save it.
            $user->setUserAccountControl($ac);

            // Save the user.
            $user->save();
            return $user;
        }

        return null;
    }

    public function ldapValidatePassowrd($form)
    {
        $attribute = 'username';
        $username = $this->getUsername();
        try {

            if (! Yii::$app->ldap->getDefaultProvider()
                ->auth()
                ->attempt($username, $form->password)) {
                $form->addError($attribute, 'Incorrect AD email or password.');
                return false;
            }
        } catch (\Adldap\Auth\UsernameRequiredException $e) {
            $form->addError($attribute, 'Email required');
            return false;
            // The user didn't supply a username.
        } catch (\Adldap\Auth\PasswordRequiredException $e) {
            // The user didn't supply a password.
            $form->addError($attribute, 'Password required.');
            return false;
        }
        // TODO Sync local password
        $this->setPassword($form->password);
        return $this->updateAttributes([
            'password'
        ]);
    }

    public function ldapChangepassword($oldPassword, $newPassword)
    {
        $msg = 'Password Changed';
        $user = $this->ldapFindUser();
        if ($user) {
            try {

                $user->changePassword($oldPassword, $newPassword, true);
            } catch (AdldapException $e) {
                $msg = $e->getMessage();
            } catch (UserPasswordIncorrectException $e) {
                $msg = $e->getMessage();
            } catch (UserPasswordPolicyException $e) {
                $msg = $e->getMessage();
            }
        }
        return $msg;
    }

    /**
     */
    public function ldapFindUser()
    {
        $user = null;
        $username = $this->getUsername();

        if (Yii::$app->ldap->connect()) {
            $search = Yii::$app->ldap->getDefaultProvider()->search();

            self::log("Searching user :" . $username);

            $user = $search->findBy('samaccountname', $username);
        }

        return $user;
    }
}