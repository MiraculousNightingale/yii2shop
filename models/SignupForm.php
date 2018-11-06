<?php
/**
 * Created by PhpStorm.
 * User: wenceslaus
 * Date: 11/6/18
 * Time: 9:05 AM
 */

namespace app\models;


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

}