<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bank}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 *
 * @property Course[] $courses
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bank}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['bank_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return BankQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BankQuery(get_called_class());
    }
}
