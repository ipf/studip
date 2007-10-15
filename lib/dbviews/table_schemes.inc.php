<?php
//copy to $STUDIP_BASE_PATH/lib/dbviews/table_schemes.inc.php
//generated Sun, 07 Oct 2007 18:07:25 +0200
$GLOBALS['DB_TABLE_SCHEMES']['admission_group']['db_fields']=array (
  'group_id' => 
  array (
    'name' => 'group_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar(255)',
    'key' => '',
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'tinyint(3) unsigned',
    'key' => '',
  ),
  'chdate' => 
  array (
    'name' => 'chdate',
    'type' => 'int(10) unsigned',
    'key' => '',
  ),
  'mkdate' => 
  array (
    'name' => 'mkdate',
    'type' => 'int(10) unsigned',
    'key' => '',
  ),
);
$GLOBALS['DB_TABLE_SCHEMES']['admission_group']['pk']=array (
  0 => 'group_id',
);
$GLOBALS['DB_TABLE_SCHEMES']['comments']['db_fields']=array (
  'comment_id' => 
  array (
    'name' => 'comment_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'object_id' => 
  array (
    'name' => 'object_id',
    'type' => 'varchar(32)',
    'key' => 'MUL',
  ),
  'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'content' => 
  array (
    'name' => 'content',
    'type' => 'text',
    'key' => '',
  ),
  'mkdate' => 
  array (
    'name' => 'mkdate',
    'type' => 'int(20)',
    'key' => '',
  ),
  'chdate' => 
  array (
    'name' => 'chdate',
    'type' => 'int(20)',
    'key' => '',
  ),
);
$GLOBALS['DB_TABLE_SCHEMES']['comments']['pk']=array (
  0 => 'comment_id',
);
$GLOBALS['DB_TABLE_SCHEMES']['dokumente']['db_fields']=array (
  'dokument_id' => 
  array (
    'name' => 'dokument_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'range_id' => 
  array (
    'name' => 'range_id',
    'type' => 'varchar(32)',
    'key' => 'MUL',
  ),
  'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'varchar(32)',
    'key' => 'MUL',
  ),
  'seminar_id' => 
  array (
    'name' => 'seminar_id',
    'type' => 'varchar(32)',
    'key' => 'MUL',
  ),
  'name' => 
  array (
    'name' => 'name',
    'type' => 'varchar(255)',
    'key' => '',
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'text',
    'key' => '',
  ),
  'filename' => 
  array (
    'name' => 'filename',
    'type' => 'varchar(255)',
    'key' => '',
  ),
  'mkdate' => 
  array (
    'name' => 'mkdate',
    'type' => 'int(20)',
    'key' => '',
  ),
  'chdate' => 
  array (
    'name' => 'chdate',
    'type' => 'int(20)',
    'key' => '',
  ),
  'filesize' => 
  array (
    'name' => 'filesize',
    'type' => 'int(20)',
    'key' => '',
  ),
  'autor_host' => 
  array (
    'name' => 'autor_host',
    'type' => 'varchar(20)',
    'key' => '',
  ),
  'downloads' => 
  array (
    'name' => 'downloads',
    'type' => 'int(20)',
    'key' => '',
  ),
  'url' => 
  array (
    'name' => 'url',
    'type' => 'varchar(255)',
    'key' => '',
  ),
  'protected' => 
  array (
    'name' => 'protected',
    'type' => 'tinyint(4)',
    'key' => '',
  ),
);
$GLOBALS['DB_TABLE_SCHEMES']['dokumente']['pk']=array (
  0 => 'dokument_id',
);
$GLOBALS['DB_TABLE_SCHEMES']['news']['db_fields']=array (
  'news_id' => 
  array (
    'name' => 'news_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'topic' => 
  array (
    'name' => 'topic',
    'type' => 'varchar(255)',
    'key' => '',
  ),
  'body' => 
  array (
    'name' => 'body',
    'type' => 'text',
    'key' => '',
  ),
  'author' => 
  array (
    'name' => 'author',
    'type' => 'varchar(255)',
    'key' => '',
  ),
  'date' => 
  array (
    'name' => 'date',
    'type' => 'int(11)',
    'key' => 'MUL',
  ),
  'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'varchar(32)',
    'key' => 'MUL',
  ),
  'expire' => 
  array (
    'name' => 'expire',
    'type' => 'int(11)',
    'key' => '',
  ),
  'allow_comments' => 
  array (
    'name' => 'allow_comments',
    'type' => 'tinyint(1)',
    'key' => '',
  ),
  'chdate_uid' => 
  array (
    'name' => 'chdate_uid',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'chdate' => 
  array (
    'name' => 'chdate',
    'type' => 'int(10) unsigned',
    'key' => 'MUL',
  ),
  'mkdate' => 
  array (
    'name' => 'mkdate',
    'type' => 'int(10) unsigned',
    'key' => '',
  ),
);
$GLOBALS['DB_TABLE_SCHEMES']['news']['pk']=array (
  0 => 'news_id',
);
$GLOBALS['DB_TABLE_SCHEMES']['scm']['db_fields']=array (
  'scm_id' => 
  array (
    'name' => 'scm_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'range_id' => 
  array (
    'name' => 'range_id',
    'type' => 'varchar(32)',
    'key' => 'MUL',
  ),
  'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'tab_name' => 
  array (
    'name' => 'tab_name',
    'type' => 'varchar(20)',
    'key' => '',
  ),
  'content' => 
  array (
    'name' => 'content',
    'type' => 'text',
    'key' => '',
  ),
  'mkdate' => 
  array (
    'name' => 'mkdate',
    'type' => 'int(20)',
    'key' => '',
  ),
  'chdate' => 
  array (
    'name' => 'chdate',
    'type' => 'int(20)',
    'key' => '',
  ),
);
$GLOBALS['DB_TABLE_SCHEMES']['scm']['pk']=array (
  0 => 'scm_id',
);
$GLOBALS['DB_TABLE_SCHEMES']['stm_instances']['db_fields']=array (
  'stm_instance_id' => 
  array (
    'name' => 'stm_instance_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'stm_abstr_id' => 
  array (
    'name' => 'stm_abstr_id',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'semester_id' => 
  array (
    'name' => 'semester_id',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'lang_id' => 
  array (
    'name' => 'lang_id',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'homeinst' => 
  array (
    'name' => 'homeinst',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'creator' => 
  array (
    'name' => 'creator',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'responsible' => 
  array (
    'name' => 'responsible',
    'type' => 'varchar(32)',
    'key' => '',
  ),
  'complete' => 
  array (
    'name' => 'complete',
    'type' => 'tinyint(1)',
    'key' => '',
  ),
);
$GLOBALS['DB_TABLE_SCHEMES']['stm_instances']['pk']=array (
  0 => 'stm_instance_id',
);
$GLOBALS['DB_TABLE_SCHEMES']['stm_instances_elements']['db_fields']=array (
  'stm_instance_id' => 
  array (
    'name' => 'stm_instance_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'element_id' => 
  array (
    'name' => 'element_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
  'sem_id' => 
  array (
    'name' => 'sem_id',
    'type' => 'varchar(32)',
    'key' => 'PRI',
  ),
);
$GLOBALS['DB_TABLE_SCHEMES']['stm_instances_elements']['pk']=array (
  0 => 'stm_instance_id',
  1 => 'element_id',
  2 => 'sem_id',
);
?>