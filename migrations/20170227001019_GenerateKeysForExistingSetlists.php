<?php

require __DIR__ . '/../config/database.php';

/*
 * Database/Models/Libs Setup
 */
ORM::configure("sqlite:" . __DIR__ . "/../db/{$db_name}");

function autoload_models($class_name) {
    $class_name = strtolower($class_name);
    $file = realpath(__DIR__ . "/../models/{$class_name}.php");
    if (file_exists($file)) {
        require_once($file);
    }
}
function autoload_libs($class_name) {
    $class_name = strtolower($class_name);
    $file = realpath(__DIR__ . "/../lib/{$class_name}.php");
    if (file_exists($file)) {
        require_once($file);
    }
}
spl_autoload_register('autoload_models');
spl_autoload_register('autoload_libs');

use Phpmig\Migration\Migration;

class GenerateKeysForExistingSetlists extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sets = Model::factory('Setlist')->where_null('access_key')->find_many();
        foreach ($sets as $set) {
            $set->save();
        }
    }

    /**
     * Undo the migration
     */
    public function down()
    {

    }
}
