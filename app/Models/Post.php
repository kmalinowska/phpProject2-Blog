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

    public static function getRecent(?int $limit = null, ?string $search = null) {
        /** @var \Core\Database $db */ //doc block - to use method suggestions below
        $db = App::get('database');

        $query = "SELECT * FROM " . static::$table;
        $params = [];

        if($search !== null) {
            $query .= " WHERE title LIKE ? OR content LIKE ?";
            $params = ["%$search%", "%$search%"];
        }

        $query .= " ORDER BY created_at DESC";

        if($limit !== null) {
            $query .= " LIMIT ?";
            $params[] = $limit;
        }

        return $db->fetchAll($query, $params, static::class);
    }

    public static function incrementViews($id): void {
        $db = App::get('database');
        $db->query(
            "UPDATE posts SET views = views + 1 WHERE id = ?",
            [$id]
        );
    }
}