<?php
require_once __DIR__ . '/php/functions.php';
$movies = load_json('items.json');
// "TV Shows" view: treat shows as series; we have movies in JSON so we present them grouped by genre as collections
$byGenre = categories($movies);
$active = 'tv_shows';
render_page_head(t('page_series'));
?>
<?php render_nav($active); ?>
<main class="page">
  <section class="section">
    <span class="eyebrow"><?php echo e(t('hero_eyebrow')); ?></span>
    <h1 class="display"><?php echo e(t('page_series')); ?></h1>
    <p class="lead"><?php echo e(t('tagline')); ?></p>
  </section>
  <section class="section">
    <div class="filters">
      <input data-search oninput="filterMovies()" placeholder="<?php echo e(t('search')); ?>">
      <select data-genre onchange="filterMovies()">
        <option value="all"><?php echo e(t('all')); ?></option>
        <?php foreach (array_keys($byGenre) as $genre): ?>
          <option value="<?php echo e($genre); ?>"><?php echo e(tg($genre)); ?></option>
        <?php endforeach; ?>
      </select>
      <span class="panel"><?php echo count($movies) . ' ' . e(t('titles_count')); ?></span>
    </div>
  </section>
  <?php foreach ($byGenre as $genre => $row): ?>
  <section class="section">
    <div class="section-head">
      <h2 class="title"><?php echo e(tg($genre)); ?></h2>
      <span class="eyebrow"><?php echo count($row); ?> <?php echo e(t('titles_count')); ?></span>
    </div>
    <div class="carousel"><?php foreach ($row as $movie) render_card($movie); ?></div>
  </section>
  <?php endforeach; ?>
  <?php if (empty($byGenre)) render_empty(); ?>
</main>
<div class="modal" id="detailsModal"><div class="modal-card"><button class="btn" data-close style="float:right">&times;</button><div class="billboard" id="moviePoster" style="position:relative;width:100%;height:300px;--poster:linear-gradient(135deg,#111,#e50914)"></div><h2 class="title" id="movieTitle"></h2><p class="eyebrow" id="movieMeta"></p><div class="stars" id="movieStars"></div><p class="lead" id="movieDesc"></p></div></div>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
