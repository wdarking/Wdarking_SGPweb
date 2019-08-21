<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    -- DROP TABLE IF EXISTS {$installer->getTable('sgpweb/sgpplp')};
    CREATE TABLE `{$installer->getTable('sgpweb/sgpplp')}` (
      `plp_id` int(11) NOT NULL auto_increment,
      `order_id` int(11),
      `increment_order_id` text,
      `track_id` text,
      `shipping_carrier` text,
      `shipping_method` text,
      `receiver_name` text,
      `receiver_address` text,
      `movimentations` text,
      `created_at` datetime default NULL,
      `updated_at` datetime default NULL,
      PRIMARY KEY  (`plp_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();
