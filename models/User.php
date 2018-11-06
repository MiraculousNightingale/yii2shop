<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\Cookie;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $hash
 * @property string $salt
 * @property string $auth_key
 * @property string $access_token
 * @property string $verification_token
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'email'],
            [['email', 'username'], 'required'],
            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'username' => 'Username',
            'hash' => 'Hash',
            'salt' => 'Salt',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'verification_token' => 'Confirm Token',
        ];
    }

    /**
     * @param $form SignupForm
     * @return bool true if data received successfully | false if unsuccessful
     */
    public function getDataFromForm($form)
    {
        try {
            $this->email = $form->email;
            $this->username = $form->username;
            $this->salt = Yii::$app->security->generateRandomString();
            $this->hash = Yii::$app->security->generatePasswordHash($form->password . $this->salt);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @param null $type
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        /*if ($user = static::findOne(['access_token' => $token])) {
            return $user->validateAccessToken($token) ? $user : null;
        }
        return null;*/

        return static::findOne(['access_token' => $token]);
    }

    public static function findIdentityByVerificationToken($token)
    {
        return static::findOne(['verification_token' => $token]);
    }

    /**
     * Checks if the given token is valid.
     * @return bool
     */
    public function validateAccessToken($token)
    {
        if ($this->access_token == $token) {
            return true;
        }
        return false;
    }

    /**
     * Generates access token.
     * @param $expireInSeconds
     * @throws \yii\base\Exception
     */
    public function generateAccessToken($expireInSeconds)
    {
        $this->access_token = Yii::$app->security->generateRandomString();
        Yii::$app->getResponse()->getCookies()->add(new Cookie([
            'name' => 'access_token',
            'value' => $this->access_token,
            'expire' => time() + $expireInSeconds,
        ]));
        $this->save();
    }

    /**
     * If access token exists in cookies, deletes it.
     * @returns bool true If cookie existed and was removed | false If cookie wasn't found.
     */
    public function purgeAccessToken()
    {
        if ($token = Yii::$app->getRequest()->getCookies()->get('access_token')) {
            Yii::$app->getResponse()->getCookies()->remove($token->name);
            return true;
        }
        return false;
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password . $this->salt, $this->hash);
    }

    public function sendEmailVerification()
    {
        if ($emailSent = Yii::$app->mailer
            ->compose(['html' => 'user-verify-html'], ['user' => $this])
            ->setTo($this->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Account verification.')
            ->send())
            Yii::$app->session->setFlash('success', 'Check your email to confirm the registration.');
        else throw new \RuntimeException('Sending error.');

    }

    /**
     * Executes before saving a record.
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
                $this->verification_token = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }


}
