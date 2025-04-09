<?php

$id = $_POST['id'];

/** @var \App\Libs\ViewTemplate $this */

$pdo = $this->db->getPDO();

$statement = $pdo->prepare('SELECT * FROM blogs WHERE id = ?');
$statement->execute([$id]);

$blog = $statement->fetchObject();

if ($blog->hero_image) {
  @unlink('..' . $blog->hero_image);
}

if ($blog->meta_image) {
  @unlink('..' . $blog->meta_image);
}

$statement = $pdo->prepare('DELETE FROM tags_blogs WHERE blog_id = ?');
$statement->execute([$id]);

$statement = $pdo->prepare('DELETE FROM blogs WHERE id = ?');
$statement->execute([$id]);

header('Location: /admin/blogs');
exit;