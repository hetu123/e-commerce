<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username'=>$this->string()->unique(),
            'firstname'=>$this->string(),
            'lastname' =>$this->string(),
            'phonenumber'=>$this->string()->unique(),
            'email' => $this->string()->unique(),
            'access_token' => $this->string(64),
            'activate_token'=>$this->string(64),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(),
            'password_reset_token' => $this->string()->unique(),
            //'is_active' => $this->boolean()->defaultValue(1),
            'google_account_id'=>$this->integer(30),
            'phonenumber_signin_id'=>$this->integer(30),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),

            'profile_pic' => $this->string(255),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
