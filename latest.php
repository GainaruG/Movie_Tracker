<?php
require_once __DIR__ . '/php/functions.php';
$movies = load_json('items.json');
// Sort by year desc as "Latest"
usort($movies, fn($a, $b) => ((int)($b['year'] ?? 0)) <=> ((int)($a['year'] ?? 0)));
$active = 'latest';
render_page_head(t('page_latest'));
?>
<?php render_nav($active); ?>
<main class="page">
  <section class="section">
    <span class="eyebrow"><?php echo e(t('hero_eyebrow')); ?></span>
    <h1 class="display"><?php echo e(t('page_latest')); ?></h1>
    <p class="lead"><?php echo e(t('tagline')); ?></p>
  </section>
  <section class="section">
    <div class="filters">
      <input data-search oninput="filterMovies()" placeholder="<?php echo e(t('search')); ?>">
      <span class="panel"><?php echo count($movies) . ' ' . e(t('titles_count')); ?></span>
    </div>
    <div class="library"><?php foreach ($movies as $movie) render_card($movie); ?></div>
    <?php if (empty($movies)) render_empty(); ?>
  </section>
</main>
<div class="modal" id="detailsModal"><div class="modal-card"><button class="btn" data-close style="float:right">&times;</button><div class="billboard" id="moviePoster" style="position:relative;width:100%;height:300px;--poster:linear-gradient(135deg,#111,#e50914)"></div><h2 class="title" id="movieTitle"></h2><p class="eyebrow" id="movieMeta"></p><div class="stars" id="movieStars"></div><p class="lead" id="movieDesc"></p></div></div>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
