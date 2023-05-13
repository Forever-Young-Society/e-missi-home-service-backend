<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\components\commands;

use app\components\TConsoleController;
use app\components\helpers\TArrayHelper;
use app\components\validators\TPasswordValidator;
use app\models\User;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;
use app\models\LoginHistory;
use app\models\Feed;

class UserController extends TConsoleController
{

    public $id = null;

    public $email = null;

    public $password = null;

    public $role_id = null;

    public $length = 8;

    public function options($actionID)
    {
        return TArrayHelper::merge(parent::options($actionID), [
            'id',
            'email',
            'password',
            'role_id'
        ]);
    }

    public function optionAliases()
    {
        return TArrayHelper::merge(parent::optionAliases(), [
            'i' => 'id',
            'e' => 'email',
            'p' => 'password',
            'r' => 'role_id'
        ]);
    }

    /**
     * Reset password using email id
     *
     * @param
     *            -e -p
     * @example php console.php user/password -e=user@jischool.com -p=admin@123
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

        $model = User::find()->where([
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

        $validator = new TPasswordValidator();

        $model->password = $this->password;

        $validator->validateAttribute($model, "password");

        if ($model->errors) {

            self::log($model->errorsString);

            return ExitCode::DATAERR;
        }

        $model->setPassword($this->password);

        if (! $model->save()) {

            self::log('Password not changed ');

            return ExitCode::DATAERR;
        }

        self::log($this->email . ' = Password successfully changed !');

        return ExitCode::OK;
    }

    /**
     * Reset all password
     *
     * @param
     *            -p
     * @example php console.php user/password-all -p=admin@123
     *         
     * @return number
     */
    public function actionPasswordAll()
    {
        if (is_null($this->password)) {
            self::log('Password required ! (Hint -p=)');
            return ExitCode::NOUSER;
        }

        $query = User::findActive();

        foreach ($query->each() as $model) {

            $validator = new TPasswordValidator();

            $model->password = $this->password;

            $validator->validateAttribute($model, "password");

            if ($model->errors) {

                self::log($model->errorsString);

                return ExitCode::DATAERR;
            }

            $model->setPassword($this->password);

            if (! $model->save()) {

                self::log('Password not changed ');

                return ExitCode::DATAERR;
            }
        }

        self::log('All Password successfully changed !');

        return ExitCode::OK;
    }

    /**
     * Update User Role Id using email id
     *
     * @param
     *            -e -r
     * @example php console.php user/role -e=user@jischool.com -r=2
     * @return number
     */
    public function actionRole()
    {
        foreach (USER::getRoleOptions() as $key => $val) {

            self::log("\n" . $val . ' Role-id is ' . $key . "\n");
        }

        if (is_null($this->email)) {

            self::log('Email required ! (Hint -e=)');

            return ExitCode::DATAERR;
        }

        $model = User::findOne([
            'email' => $this->email
        ]);

        if (is_null($model)) {

            self::log('User not found');

            return ExitCode::NOUSER;
        }

        if (is_null($this->role_id)) {

            self::log('Current Role Id is ' . $model->role_id . "\n");
            self::log('Add correct Role id (Hint -r=)');

            return ExitCode::DATAERR;
        }

        if (is_null(ArrayHelper::getValue(USER::getRoleOptions(), $this->role_id))) {

            self::log('Please enter correct role id :');

            return ExitCode::DATAERR;
        }

        $model->role_id = $this->role_id;

        if (! $model->save()) {

            self::log('Please enter valid Role Id ');

            return ExitCode::DATAERR;
        }

        self::log($this->email . ' = Role Id successfully Updated !');

        return ExitCode::OK;
    }

    /**
     * State change using email or id
     *
     * @param
     *            -e -p
     * @tutorial php console.php user/state -e=user@jischool.com
     *          
     * @return number
     */
    public function actionState($state_id)
    {
        if (is_null($this->email) && is_null($this->id)) {
            self::log('User ID or Email required ! (Hint -e=  or -i=)');
            return ExitCode::DATAERR;
        }

        $model = User::find()->andFilterWhere([
            'email' => $this->email,
            'id' => $this->id
        ])->one();

        if (is_null($model)) {
            self::log('User not found');
            return ExitCode::NOUSER;
        }

        self::log($model . ' = ' . $model->getState());
        $model->state_id = $state_id;
        self::log($model . ' =next = ' . $model->getState());

        if ($model->validate()) {
            self::log('Validate Failed' . $model->errorsString);
            return ExitCode::DATAERR;
        }

        if (! $model->save(false)) {
            self::log('state not changed ');
            return ExitCode::DATAERR;
        }

        self::log($model . ' =  successfully changed !');

        return ExitCode::OK;
    }

    /**
     * Deleted User which are inactive
     */
    public function actionClearInvalid()
    {
        $query = User::find()->where([
            'state_id' => User::STATE_INACTIVE
        ])->andWhere([
            '<',
            'created_on',
            date('Y-m-d', strtotime('-1 month'))
        ]);
        foreach ($query->each() as $model) {
            self::log('User-' . $model->id . ':' . $model);
            $model->state_id = User::STATE_DELETED;
            $model->delete();
        }
    }

    /**
     * Automatically Deleted User which are inactive for days
     *
     * @param number $days
     *            usage php console.php user/auto-delete-inactive 10
     */
    public function actionAutoDeleteInactive($days = 30)
    {
        $query = User::find()->where([
            'state_id' => User::STATE_ACTIVE
        ])->andWhere([
            'OR',
            [
                'last_visit_time' => null
            ],
            [
                '<',
                'last_visit_time',
                date('Y-m-d', strtotime('-' . $days . ' days'))
            ]
        ]);

        user::logQuery($query);

        foreach ($query->each() as $model) {
            self::log($model . '==> auto marked deleted');
            if ($this->dryRun == false && $model->role_id != User::ROLE_ADMIN) {
                $model->updateHistory('auto marked deleted');
                $model->state_id = User::STATE_DELETED;

                $model->save();
            }
        }
    }

    /**
     * Update/Create admin account using email and password
     *
     * @param
     *            -e -p
     * @example php console.php user/admin -e=admin@toxsl.in -p=admin@123
     *         
     * @return number
     */
    public function actionAdmin()
    {
        if (is_null($this->email) || is_null($this->password)) {
            self::log('Both email and password required ! (Hint -e= and -p=)');
            return ExitCode::DATAERR;
        }
        $user = User::find()->where([
            'role_id' => 0
        ])->one();

        if (is_null($user)) {
            self::log('Creating Admin account: ');
            $user = new User();
            $user->loadDefaultValues();
            $user->role_id = User::ROLE_ADMIN;
            $user->state_id = User::STATE_ACTIVE;
            $user->email_verified = User::EMAIL_VERIFIED;
            $user->full_name = 'Admin';
        } else {
            self::log('Account exists');
        }
        $validator = new TPasswordValidator();
        $user->email = $this->email;
        $user->password = $this->password;
        $validator->validateAttribute($user, "password");
        if ($user->errors) {
            self::log($user->errorsString);
            return ExitCode::DATAERR;
        }
        if ($user->validate()) {
            $user->setPassword($user->password);
            if ($user->save()) {
                self::log('Account created/updated for: ' . $this->email);
                return ExitCode::OK;
            }
        }
        self::log('Account not updated/created.');
        self::log($user->errorsString);
        return ExitCode::DATAERR;
    }
}

