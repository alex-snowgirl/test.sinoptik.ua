<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%course}}".
 *
 * @property integer $id
 * @property integer $bank_id
 * @property integer $currency_id
 * @property double $sell
 * @property double $buy
 * @property string $pub_date
 *
 * @property Bank $bank
 * @property Currency $currency
 */
class Course extends \yii\db\ActiveRecord
{
    const DATE_FORMAT = 'Y-m-d_His';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank_id', 'currency_id', 'sell', 'buy'], 'required'],
            [['bank_id', 'currency_id'], 'integer'],
            [['sell', 'buy'], 'number'],
            [['pub_date'], 'safe'],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::className(), 'targetAttribute' => ['bank_id' => 'id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'bank_id' => Yii::t('app', 'Bank ID'),
            'currency_id' => Yii::t('app', 'Currency ID'),
            'sell' => Yii::t('app', 'Sell'),
            'buy' => Yii::t('app', 'Buy'),
            'pub_date' => Yii::t('app', 'Pub Date'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::className(), ['id' => 'bank_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @inheritdoc
     * @return CourseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CourseQuery(get_called_class());
    }

    public function pubDateToUrl()
    {
        return date_create_from_format('Y-m-d H:i:s', $this->pub_date)->format(static::DATE_FORMAT);
    }

    public static function pubDateFromUrl($date)
    {
        return date_create_from_format(static::DATE_FORMAT, $date);
    }
}
