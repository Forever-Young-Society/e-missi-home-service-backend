<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\modules\smtp\commands;

use app\components\TConsoleController;
use app\components\helpers\TArrayHelper;
use app\modules\smtp\models\Account;
use yii\console\ExitCode;
use app\modules\emailreader\models\EmailAccount;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AccountController extends TConsoleController
{

    public $id = null;

    public $email = null;

    public $password = null;

    public $server = null;

    public function options($actionID)
    {
        return TArrayHelper::merge(parent::options($actionID), [
            'id',
            'email',
            'password',
            'server'
        ]);
    }

    public function optionAliases()
    {
        return TArrayHelper::merge(parent::optionAliases(), [
            'i' => 'id',
            'e' => 'email',
            'p' => 'password',
            'h' => 'server'
        ]);
    }

    /**
     * Reset password using email id
     *
     * @param
     *            -e -p
     * @example php console.php account/password -e=user@jischool.com -p=admin@123
     *         
     * @return number
     */
    public function actionPassword()
    {
        if (is_null($this->password)) {
            self::log('Password required ! (Hint -p=)');
            return ExitCode::NOUSER;
        }

        if (is_null($this->email) && is_null($this->id)) {
            self::log('User ID or Email required ! (Hint -e=  or -i=)');
            return ExitCode::DATAERR;
        }

        $model = Account::find()->where([
            'OR',
            [
                'email' => $this->email
            ],
            [
                'id' => $this->id
            ]
        ])->one();

        if (is_null($model)) {

            self::log('User not found');

            return ExitCode::NOUSER;
        }

        $model->setEncryptedPassword($this->password);

        if (! $model->save()) {

            self::log('Password not changed ');

            return ExitCode::DATAERR;
        }

        self::log($this->email . ' = Password successfully changed !');

        return ExitCode::OK;
    }

    /**
     * Update/Create account using email and password
     *
     * @param
     *            -e -p
     * @example php console.php account/admin -e=admin@toxsl.in -p=admin@123
     *         
     * @return number
     */
    public function actionUpdate()
    {
        if (is_null($this->email) || is_null($this->password)) {
            self::log('Both email and password required ! (Hint -e= and -p=)');
            return ExitCode::DATAERR;
        }
        $model = Account::find()->where([
            'email' => $this->email
        ])->one();

        if (is_null($model)) {
            self::log('Creating  account: ');
            $model = new Account();
            $model->loadDefaultValues();

            $model->state_id = Account::STATE_ACTIVE;
            $model->title = $this->email;
        } else {
            self::log('Account exists');
        }
        $model->email = $this->email;
        $model->password = $this->password;

        if ($model->validate()) {
            $model->setEncryptedPassword($this->password);
            if ($model->save()) {
                self::log('Account created/updated for: ' . $this->email);
                return ExitCode::OK;
            }
        }
        self::log('Account not updated/created.');
        self::log($model->errorsString);
        return ExitCode::DATAERR;
    }

    /**
     * Update Server Name using email
     *
     * @param string $email
     * @param string $server
     * @return number
     */
    public function actionUpdateServer()
    {
        if (is_null($this->email) || is_null($this->server)) {
            self::log('Both email and server required ! (Hint -e= and -h=)');
            return ExitCode::DATAERR;
        }

        $model = Account::find()->where([
            'email' => $this->email
        ])->one();

        if (! is_null($model)) {
            $model->server = $this->server;
            if ($model->save()) {
                self::log('Outgoing Server name updated for: ' . $this->email);
                return ExitCode::OK;
            }
        }

        self::log('Server name not updated.');
        self::log($model->errorsString);
        return ExitCode::DATAERR;
    }

    /**
     * Test email
     *
     * @param
     *
     * @example php console.php account/test -i=1 -e=user@jischool.com
     *         
     * @return number
     */
    public function actionTest()
    {
        if (is_null($this->email) || is_null($this->id)) {
            self::log('Both Account ID and Email required ! (Hint -e=  or -i=)');
            return ExitCode::DATAERR;
        }

        $model = Account::find()->where([

            'id' => $this->id
        ])->one();

        if (is_null($model)) {

            self::log('Account not found');

            return ExitCode::NOUSER;
        }

        if (! $model->test($this->email, $model->email)) {

            self::log('Unable to send email');

            return ExitCode::DATAERR;
        }

        self::log($this->email . ' = Email Send successfully!');

        return ExitCode::OK;
    }
}
