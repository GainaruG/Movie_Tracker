<?php
require_once __DIR__ . '/php/functions.php';
$movies = load_json('items.json');
$featured = $movies[0] ?? [];
$sections = [
  'Trending'     => t('section_top_searches'),
  'Popular'      => t('section_continue'),
  'New Releases' => t('section_recent'),
  'Recommended'  => t('section_recommended')
];
$byGenre = categories($movies);
render_page_head(t('app_name'));
?>
<!-- Splash Screen -->
<div id="splash" class="splash">
  <div class="splash-logo">
    <span class="splash-icon">🎬</span>
    <span class="splash-text"><?php echo e(APP_BRAND); ?></span>
  </div>
</div>
<?php render_nav('home'); ?>
<header class="hero">
  <div>
    <span class="eyebrow"><?php echo e(t('hero_eyebrow')); ?></span>
    <h1 class="display"><?php echo e(t('hero')); ?></h1>
    <p class="lead"><?php echo e(t('tagline')); ?></p>
    <div class="actions">
      <a class="btn primary" href="#movies">▶ <?php echo e(t('start')); ?></a>
      <a class="btn" href="<?php echo e(url_lang('latest.php')); ?>"><?php echo e(t('more_info')); ?></a>
      <?php if (is_logged_in()): ?>
        <a class="btn" href="<?php echo e(url_lang('account.php')); ?>"><?php echo e(t('account')); ?></a>
      <?php else: ?>
        <a class="btn" href="<?php echo e(url_lang('register.php')); ?>"><?php echo e(t('join')); ?></a>
      <?php endif; ?>
    </div>
  </div>
  <div class="showcase">
    <div class="billboard" style="--poster:<?php echo e($featured['poster'] ?? 'linear-gradient(135deg,#111,#e50914)'); ?>;--poster-img:<?php echo !empty($featured['poster_url']) ? "url('" . e(addslashes($featured['poster_url'])) . "')" : 'none'; ?>">
      <?php if (!empty($featured['poster_url'])): ?>
      <img class="billboard-poster" src="<?php echo e($featured['poster_url']); ?>" alt="<?php echo e($featured['title'] ?? ''); ?>">
      <?php endif; ?>
      <div class="billboard-content">
        <span class="badge"><?php echo e(t('trending')); ?></span>
        <h2><?php echo e($featured['title'] ?? APP_BRAND); ?></h2>
        <div class="meta">
          <span class="chip"><?php echo e(tg($featured['genre'] ?? 'Action')); ?></span>
          <span class="chip"><?php echo e($featured['year'] ?? date('Y')); ?></span>
          <span class="chip"><?php echo e($featured['duration'] ?? '120 min'); ?></span>
        </div>
        <div class="stars"><?php echo e(stars($featured['rating'] ?? 5)); ?></div>
      </div>
    </div>
  </div>
</header>
<main class="page" id="movies">
  <section class="section">
    <div class="filters">
      <input data-search oninput="filterMovies()" placeholder="<?php echo e(t('search')); ?>">
      <select data-genre onchange="filterMovies()">
        <option value="all"><?php echo e(t('all')); ?></option>
        <?php foreach (array_keys($byGenre) as $genre): ?>
          <option value="<?php echo e($genre); ?>"><?php echo e(tg($genre)); ?></option>
        <?php endforeach; ?>
      </select>
      <?php if (!is_logged_in()): ?>
        <a class="btn" href="<?php echo e(url_lang('login.php')); ?>"><?php echo e(t('login')); ?></a>
      <?php endif; ?>
      <a class="btn primary" href="<?php echo e(url_lang('movies.php')); ?>"><?php echo e(t('movies')); ?></a>
    </div>
  </section>
  <?php foreach ($sections as $section => $label):
    $row = array_values(array_filter($movies, fn($m) => ($m['section'] ?? '') === $section));
    if (!$row) continue; ?>
  <section class="section">
    <div class="section-head">
      <h2 class="title"><?php echo e($label); ?></h2>
      <span class="eyebrow"><?php echo count($row); ?> <?php echo e(t('titles_count')); ?></span>
    </div>
    <div class="carousel"><?php foreach ($row as $movie) render_card($movie); ?></div>
  </section>
  <?php endforeach; ?>
</main>
<div class="modal" id="detailsModal"><div class="modal-card"><button class="btn" data-close style="float:right">&times;</button><div class="billboard" id="moviePoster" style="position:relative;width:100%;height:300px;--poster:linear-gradient(135deg,#111,#e50914)"></div><h2 class="title" id="movieTitle"></h2><p class="eyebrow" id="movieMeta"></p><div class="stars" id="movieStars"></div><p class="lead" id="movieDesc"></p></div></div>
<?php render_footer(); ?>
<script src="js/script.js"></script>
<script>
(function(){
  var splash = document.getElementById('splash');
  if (!splash) return;
  if (sessionStorage.getItem('mt-splash-done')) { splash.remove(); return; }
  sessionStorage.setItem('mt-splash-done', '1');
  splash.addEventListener('animationend', function(e) {
    if (e.animationName === 'splashFadeOut') splash.remove();
  });
})();
</script>
</body></html>
