<?php
/**
 * Created by PhpStorm.
 * User: wenceslaus
 * Date: 11/6/18
 * Time: 9:05 AM
 */

namespace app\models;


use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $email;
    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'username', 'password'], 'required'],
        ];
    }

    /**
     * Signs up a user.
     * @return bool true if successful | false if unsuccessful
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user->save() && $user->sendEmailVerification()) {
                Yii::$app->session->setFlash('success', 'Check your email to confirm the registration.');
                return true;
            }
        }
        return false;

    }

    /**
     * Creates a user model using the info of the form.
     * @return User
     * @throws \yii\base\Exception
     */
    public function getUser()
    {
        $model = new User();
        $model->email = $this->email;
        $model->username = $this->username;
        $model->salt = Yii::$app->security->generateRandomString();
        $model->hash = Yii::$app->security->generatePasswordHash($this->password . $model->salt);
        return $model;
    }

}