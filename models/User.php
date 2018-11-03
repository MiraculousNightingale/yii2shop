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
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $access_token
 */
class User extends ActiveRecord implements IdentityInterface
{
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
            [['username', 'password'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
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
            return $user->accessTokenIsValid($token) ? $user : null;
        }
        return null;*/

        return static::findOne(['access_token' => $token]);
    }

    /**
     * Checks if the given token is valid.
     * @return bool
     */
    public function accessTokenIsValid($token)
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
        $this->access_token = Yii::$app->security->generateRandomString(16);
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
        return $this->password === $password;
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
            }
            return true;
        }
        return false;
    }


}
