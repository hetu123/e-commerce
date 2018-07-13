<?php

use yii\db\Migration;

/**
 * Class m180604_063158_order
 */
class m180604_063158_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'card_num'=> $this->integer(11)->notNull(),
            'card_cvc'=> $this->integer(11)->notNull(),
            'card_exp_month'=> $this->integer(11)->notNull(),
            'card_exp_year'=> $this->integer(11)->notNull(),
            'paid_amount'=> $this->float()->notNull(),
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'=> 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->createIndex(
            'idx_orders-user_id',
            'orders',
            'user_id'
        );

        $this->addForeignKey(
            'fk_orders-user_id',
            'orders',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx_orders-user_id',
            'orders'
        );
        $this->dropForeignKey(
            'fk_orders-user_id',
            'orders'
        );
        $this->dropTable('{{%orders}}');
    }


}
