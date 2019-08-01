<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=csv-database;dbname=csv',
    'username' => 'csv',
    'password' => 'insecure_password',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
