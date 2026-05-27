<?php
require_once __DIR__ . '/php/functions.php';
$movies = load_json('items.json');
// "My List": movies the user has marked as watched (or all if not logged-in user has none stored)
$myList = array_values(array_filter($movies, fn($m) => ($m['status'] ?? '') === 'watched'));
$active = 'my_list';
render_page_head(t('page_mylist'));
?>
<?php render_nav($active); ?>
<main class="page">
  <section class="section">
    <span class="eyebrow"><?php echo e(t('hero_eyebrow')); ?></span>
    <h1 class="display"><?php echo e(t('page_mylist')); ?></h1>
    <p class="lead"><?php echo e(t('tagline')); ?></p>
  </section>
  <section class="section">
    <div class="filters">
      <input data-search oninput="filterMovies()" placeholder="<?php echo e(t('search')); ?>">
      <span class="panel"><?php echo count($myList) . ' ' . e(t('titles_count')); ?></span>
    </div>
    <?php if (empty($myList)): ?>
      <?php render_empty(); ?>
    <?php else: ?>
      <div class="library"><?php foreach ($myList as $movie) render_card($movie); ?></div>
    <?php endif; ?>
  </section>
</main>
<div class="modal" id="detailsModal"><div class="modal-card"><button class="btn" data-close style="float:right">&times;</button><div class="billboard" id="moviePoster" style="position:relative;width:100%;height:300px;--poster:linear-gradient(135deg,#111,#e50914)"></div><h2 class="title" id="movieTitle"></h2><p class="eyebrow" id="movieMeta"></p><div class="stars" id="movieStars"></div><p class="lead" id="movieDesc"></p></div></div>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
