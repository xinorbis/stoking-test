<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "connections".
 *
 * @property int $id
 * @property string $openedAt
 * @property string $token
 * @property int $userId
 * @property string $userAgent
 * @property string|null $closedAt
 */
class Connection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'connections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openedAt', 'closedAt'], 'safe'],
            [['token', 'userId', 'userAgent'], 'required'],
            [['userId'], 'integer'],
            [['token', 'userAgent'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openedAt' => 'Opened At',
            'token' => 'Token',
            'userId' => 'User ID',
            'userAgent' => 'User Agent',
            'closedAt' => 'Closed At',
        ];
    }
}
