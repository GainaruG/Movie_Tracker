<?php
require_once __DIR__ . '/functions.php';

function movie_from_post(array $post): array {
    $title = clean_text($post['title'] ?? '', 120);
    $description = clean_text($post['description'] ?? '', 500);
    $genre = clean_text($post['genre'] ?? '', 50);
    $year = (int) ($post['year'] ?? 0);
    $rating = (float) ($post['rating'] ?? 0);
    $section = clean_text($post['section'] ?? 'Recommended', 30);
    if ($title === '' || $description === '' || $genre === '' || $year < 1900 || $year > 2100 || $rating < 1 || $rating > 5) {
        return [];
    }
    $posterUrl = trim((string) ($post['poster_url'] ?? ''));
    if ($posterUrl !== '' && !filter_var($posterUrl, FILTER_VALIDATE_URL)) {
        $posterUrl = '';
    }
    if ($posterUrl === '') {
        $seed = 'mt-' . strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title)) . '-' . strtolower(preg_replace('/[^a-z0-9]+/i', '-', $genre));
        $posterUrl = 'https://picsum.photos/seed/' . rawurlencode($seed) . '/640/960';
    }
    return [
        'title' => $title,
        'description' => $description,
        'genre' => $genre,
        'rating' => $rating,
        'year' => $year,
        'section' => $section,
        'poster' => movie_gradient($title),
        'poster_url' => $posterUrl,
        'duration' => clean_text($post['duration'] ?? '120 min', 30),
        'age' => clean_text($post['age'] ?? '13+', 10),
        'status' => ($post['status'] ?? 'unwatched') === 'watched' ? 'watched' : 'unwatched'
    ];
}
?>
