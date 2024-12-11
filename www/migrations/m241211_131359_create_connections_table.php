<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%connections}}`.
 */
class m241211_131359_create_connections_table extends Migration
{
    const TABLE_NAME = '{{%connections}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'openedAt' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'token' => $this->string()->notNull(),
            'userId' => $this->integer()->notNull(),
            'userAgent' => $this->string()->notNull(),
            'closedAt' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
