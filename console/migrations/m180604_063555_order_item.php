<?php

use yii\db\Migration;

/**
 * Class m180604_063555_order_item
 */
class m180604_063555_order_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }

        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'order_id' =>$this->integer(11)->notNull(),
            'item_name' => $this->string()->notNull(),
            'item_number'=>$this->integer()->notNull(),
            'item_price'=>$this->integer()->notNull(),
            'quantity'=>$this->integer()->notNull(),
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'=> 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->createIndex(
            'idx_order_item-user_id',
            'order_item',
            'user_id'
        );

        $this->addForeignKey(
            'fk_order_item-user_id',
            'order_item',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex(
            'idx_order_item-order_id',
            'order_item',
            'order_id'
        );

        $this->addForeignKey(
            'fk_order_item-user_idorder_id',
            'order_item',
            'order_id',
            'orders',
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
            'idx_order_item-user_id',
            'order_item'
        );
        $this->dropForeignKey(
            'fk_order_item-user_id',
            'order_item'
        );
        $this->dropIndex(
            'idx_order_item-order_id',
            'order_item'
        );
        $this->dropForeignKey(
            'fk_order_item-user_idorder_id',
            'order_item'
        );
        $this->dropTable('{{%order_item}}');
    }


}
