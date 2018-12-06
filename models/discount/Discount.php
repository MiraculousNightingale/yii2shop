<?php

namespace app\models\discount;

use app\models\category\Category;
use app\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "discount".
 *
 * @property int $id
 * @property int $category_id
 * @property int $user_id
 * @property int $percent
 * @property string $created_at
 *
 * @property Category $category
 * @property User $user
 */
class Discount extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'user_id'], 'integer'],
            [['percent'], 'integer', 'min' => 0, 'max' => 100],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['created_at'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'user_id' => 'User ID',
            'percent' => 'Percent',
            'created_at' => 'Created At'
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getCategoryName()
    {
        return $this->category->name;
    }

    public function getUserName()
    {
        return $this->user->username;
    }

    public function getUserEmail()
    {
        return $this->user->email;
    }


    /**
     * @param $userId
     * @param $categoryId
     * @param $percent
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @return bool true Created discount|false Already exists
     */
    public static function forUser($userId, $categoryId, $percent)
    {
        if ($discount = Discount::find()->where(['user_id' => $userId, 'category_id' => $categoryId])->one()) {
            $discount->delete();
        }
        $discount = new Discount([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'percent' => $percent,
        ]);
        return $discount->save();
    }
}
