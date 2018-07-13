<?php

use yii\db\Migration;

/**
 * Class m180604_061735_product
 */
class m180604_061735_product extends Migration
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

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'category_id'=> $this->integer(11)->notNull(),
           // 'sub_category_id'=> $this->integer(11)->notNull(),
            'name' => $this->string()->notNull(),
            'price'=> $this->float()->notNull(),
            'image'=>$this->string(255),
            'description'=>$this->text(),
            'short_description'=>$this->text(),
            'brand'=>$this->string(),
            'size'=>$this->string(),
           // 'favorite_cnt'=>$this->boolean()->defaultValue(0),
            'wishlist_cnt'=>$this->boolean()->defaultValue(0),
            'total_stock'=>$this->integer(),
            'use_stock'=>$this->integer(),
            'left_stock'=>$this->integer(),
            'created_at' => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at'=> 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->createIndex(
            'idx_product-category_id',
            'product',
            'category_id'
        );

        $this->addForeignKey(
            'fk_product-category_id',
            'product',
            'category_id',
            'category',
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
            'idx_product-category_id',
            'product'
        );
        $this->dropForeignKey(
            'fk_product-category_id',
            'product'
        );

        $this->dropTable('{{%product}}');
    }


}
