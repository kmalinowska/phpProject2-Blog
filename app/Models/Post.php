<?php
namespace App\Models;
use Core\Model;
use Core\App;

class Post extends Model {
    protected static $table = 'posts';

    //define the fields every single model
    public $id;
    public $user_id;
    public $title;
    public $content;
    public $views;
    public $created_at;

    public static function getRecent(int $limit) {
        /** @var \Core\Database $db */ //doc block - to use method suggestions below
        $db = App::get('database');
        return $db->fetchAll(
            "SELECT * FROM " . static::$table . " ORDER BY created_at DESC LIMIT ?", 
            [$limit],
            static::class
        );
    }

    public static function incrementViews($id): void {
        $db = App::get('database');
        $db->query(
            "UPDATE posts SET views = views + 1 WHERE id = ?",
            [$id]
        );
    }
}