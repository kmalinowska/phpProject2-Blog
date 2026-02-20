<h2>All Posts</h2>

<form action ="" method="GET"> <!-- action="" - wysłanie go do bieżącego adresu URL ; metoda get - aby uzyskac parametry zapytania URL -->
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search posts... "/>
    <button>Search</button>
</form>

<?= partial('_posts', ['posts' => $posts]) ?>
<?= partial('_pagination', ['currentPage' => $currentPage, 'totalPages' => $totalPages]) ?>