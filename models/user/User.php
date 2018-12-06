<?php

namespace app\models\user;

use app\models\comment\Comment;
use app\models\discount\Discount;
use app\models\order\Order;
use app\models\product\Product;
use app\models\rating\Rating;
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
 * @property int $role
 * @property string $hash
 * @property string $salt
 * @property string $auth_key
 * @property string $access_token
 * @property string $verification_token
 * @property string $status
 *
 * @property Order[] $orders
 * @property Order $cart
 *
 * @property Discount[] $discounts
 */
class User extends ActiveRecord implements IdentityInterface
{

    const
        STATUS_INACTIVE = 0,
        STATUS_ACTIVE = 1;

    const
        ROLE_USER = 0,
        ROLE_ADMIN = 1,
        ROLE_OVERLORD = 2;

    public $password;

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
            [['role'], 'in', 'range' => array_keys(self::getRoles())],
            [['status'], 'in', 'range' => array_keys(self::getStatuses())],
            [['password'], 'string'],
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
            'role' => 'Role',
            'hash' => 'Hash',
            'salt' => 'Salt',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'verification_token' => 'Confirm Token',
            'status' => 'Status',
            'password' => 'Change password',
        ];
    }

    public static function getRoles()
    {
        return [
            self::ROLE_USER => 'User',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_OVERLORD => 'Super admin',
        ];
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_ACTIVE => 'Active',
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
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds an identity by the given verification token.
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByVerificationToken($token)
    {
        return static::findOne(['verification_token' => $token]);
    }

    /**
     * Checks if the given token is valid.
     * @param string $token
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
     * @return string current user role
     */
    public function getRoleName()
    {
        return self::getRoles()[$this->role];
    }

    /**
     * @return string current user status
     */
    public function getStatusName()
    {
        return self::getStatuses()[$this->status];
    }

    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    public function getRatings()
    {
        return $this->hasMany(Rating::className(), ['user_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }

    public function getDiscounts()
    {
        return $this->hasMany(Discount::className(), ['user_id' => 'id']);
    }

    /**
     * @return Order Returns cart for current user or null if such was not defined;
     * @throws \Exception
     */
    public function getCart()
    {
        if ($cart = Order::findOne(['user_id' => $this->id, 'status' => Order::STATUS_CART])) {
            return $cart;
        }
        //If no cart exists, create and return it.
        $cart = new Order();
        $cart->link('user', $this);
        return $this->getCart();
    }

    public function getDiscountOn($id)
    {
        $product = Product::findOne($id);
        /** @var Discount $discount */
        if ($discount = $this->getDiscounts()->where(['category_id' => $product->category_id])->one()) {
            return $discount;
        }
        return null;
    }

    public function getOrderCountInCategory($id)
    {
        $count = 0;
        foreach ($this->orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product->category_id == $id) {
                    $count += $item->amount;
                }
            }
        }
        return $count;
    }

    public function assignDiscounts()
    {
        foreach ($this->orders as $order) {
            foreach ($order->items as $item) {
                if ($this->getOrderCountInCategory($item->product->category_id) > 30) {
                    Discount::forUser($this->id, $item->product->category_id, 30);
                } elseif ($this->getOrderCountInCategory($item->product->category_id) > 20) {
                    Discount::forUser($this->id, $item->product->category_id, 20);
                }
            }
        }
        return true;
    }


    /**
     * @param $authKey
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

    public function validateStatus()
    {
        return $this->status > User::STATUS_INACTIVE;
    }

    public function validateVerificationToken($token)
    {
        return $this->verification_token == $token;
    }

    /**
     * Sends mail with verification message
     * @return bool if mail was sent successfully
     */
    public function sendEmailVerification()
    {
        return Yii::$app->mailer
            ->compose(['html' => 'user-verify-html'], ['user' => $this])
            ->setTo($this->email)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject('Account verification.')
            ->send();
    }

    /**
     * @param $token
     * @return bool true if user verified successfully | false if unsuccessfully
     */
    public function verify()
    {
        $this->verification_token = null;
        $this->status = User::STATUS_ACTIVE;
        return $this->save();
    }

    public function isAdmin()
    {
        return $this->role >= self::ROLE_ADMIN;
    }

    public function isOverlord()
    {
        return $this->role >= self::ROLE_OVERLORD;
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
            // Used when creating or editing a user record
            if (!empty($this->password)) {
                $this->salt = Yii::$app->security->generateRandomString();
                $this->hash = Yii::$app->security->generatePasswordHash($this->password . $this->salt);
                $this->password = null;
            }
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->verification_token = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }


}
