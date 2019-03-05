<?php

use yii\db\Schema;

class m161219_190101_Yii2_Report extends \yii\db\Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%report_report}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(60)->notNull(),
            'query_select' => $this->text()->notNull(),
            'query_from' => $this->text()->notNull(),
            'query_where' => $this->text(),
            'permissions' => $this->text(),
            'created_at' => $this->timestamp()->defaultValue(null),
                ], $tableOptions);

        $this->createTable('{{%report_filter}}', [
            'id' => $this->primaryKey(),
            'report_id' => $this->integer(11)->notNull(),
            'name' => $this->string(60)->notNull(),
            'widget_class' => $this->string(100),
            'widget_class_data' => $this->text(),
            'created_at' => $this->timestamp()->defaultValue(null),
            'FOREIGN KEY ([[report_id]]) REFERENCES {{%report_report}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
                ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%report_filter}}');
        $this->dropTable('{{%report_report}}');
    }

}
