<?php

$installer = $this;
$installer->startSetup();

$installer->run("
    -- DROP TABLE IF EXISTS {$installer->getTable('sgpweb/sgpplp')};
    CREATE TABLE `{$installer->getTable('sgpweb/sgpplp')}` (
      `plp_id` int(11) NOT NULL auto_increment,
      `order_id` int(11),
      `increment_order_id` varchar(255),
      `track_id` varchar(255),
      `shipping_carrier` varchar(255),
      `shipping_method` varchar(255),
      `sgp_service` varchar(255),
      `receiver_name` varchar(255),
      `receiver_address` varchar(255),
      `movimentations` json,
      `created_at` datetime default NULL,
      `updated_at` datetime default NULL,
      PRIMARY KEY  (`plp_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();
