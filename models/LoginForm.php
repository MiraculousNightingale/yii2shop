<?php

namespace app\models;

use app\models\user\User;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['status', 'validateStatus', 'skipOnEmpty' => false]

        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Inline validation for status.
     */
    public function validateStatus()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validateStatus()) {
                $this->addError('username', 'This account is not active.');
//                Flash message can be used instead.
//                Yii::$app->session->setFlash('danger', 'This account is not active.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $user = $this->getUser()) {
//            TODO: Decide what to do with 'access_token'
//            if ($this->rememberMe) $user->generateAccessToken(60);
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 : 0);
        }
        return false;

    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        return User::findOne(['username' => $this->username]);
    }
}
