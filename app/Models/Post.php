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

    public static function getRecent(?int $limit = null, ?int $page = null, ?string $search = null) {
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

        //PAGINATION
        if($page !== null && $limit !== null) {
            $offset = ($page - 1) * $limit; // page 1 = 0, page 2 = 10
            $query .= " OFFSET ?";
            $params[] = $offset;
        }

        return $db->fetchAll($query, $params, static::class);
    }

    public static function count(?string $search = null): int {
        /** @var \Core\Database $db */ //doc block - to use method suggestions below
        $db = App::get('database');

        $query = "SELECT COUNT(*) FROM " . static::$table;
        $params = [];

        if($search !== null) {
            $query .= " WHERE title LIKE ? OR content LIKE ?";
            $params = ["%$search%", "%$search%"];
        }

        return (int) $db->query($query, $params)->fetchColumn();
    }

    public static function incrementViews($id): void {
        $db = App::get('database');
        $db->query(
            "UPDATE posts SET views = views + 1 WHERE id = ?",
            [$id]
        );
    }
}