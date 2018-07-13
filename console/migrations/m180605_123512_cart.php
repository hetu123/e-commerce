<?php

use yii\db\Migration;

/**
 * Class m180605_123512_cart
 */
class m180605_123512_cart extends Migration
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

        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'anonymous_identifier' =>$this->string(),
            'product_id'=>$this->integer(11),
            'store_price'=>$this->float()->notNull(),
            'discount'=>$this->integer(11),
            'quantity'=>$this->integer(11),
            'total_price'=>$this->float()->notNull(),
            'paid_price'=>$this->float()->notNull(),
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'=> 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->createIndex(
            'idx_cart-product_id',
            'cart',
            'product_id'
        );

        $this->addForeignKey(
            'fk_cart-product_id',
            'cart',
            'product_id',
            'product',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex(
            'idx_cart-user_id',
            'cart',
            'user_id'
        );

        $this->addForeignKey(
            'fk_cart-user_id',
            'cart',
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
            'idx_cart-product_id',
            'cart'
        );
        $this->dropForeignKey(
            'fk_cart-product_id',
            'cart'
        );
        $this->dropIndex(
            'idx_cart-user_id',
            'cart'
        );
        $this->dropForeignKey(
            'fk_cart-user_id',
            'cart'
        );
        $this->dropTable('{{%cart}}');
    }

}
