<?php
require_once __DIR__ . '/php/functions.php';
// "Games" page — uses verified real video-game cover art from Steam CDN
$active = 'games';
render_page_head(t('page_games'));
$games = [
  ['id'=>'gm001','name'=>'Stray Echo',  'tag'=>'Adventure','img'=>'https://cdn.cloudflare.steamstatic.com/steam/apps/1332010/library_600x900.jpg','year'=>2025,'age'=>'12+','rating'=>4.6,'poster'=>'linear-gradient(135deg,#1b0b2e,#e50914)'],
  ['id'=>'gm002','name'=>'Neon Drift',  'tag'=>'Racing',   'img'=>'https://cdn.cloudflare.steamstatic.com/steam/apps/1551360/library_600x900.jpg','year'=>2024,'age'=>'7+', 'rating'=>4.3,'poster'=>'linear-gradient(135deg,#020617,#2563eb)'],
  ['id'=>'gm003','name'=>'Pixel Quest', 'tag'=>'Indie',    'img'=>'https://cdn.cloudflare.steamstatic.com/steam/apps/504230/library_600x900.jpg','year'=>2023,'age'=>'7+', 'rating'=>4.5,'poster'=>'linear-gradient(135deg,#14532d,#65a30d)'],
  ['id'=>'gm004','name'=>'Skyforge',    'tag'=>'RPG',      'img'=>'https://cdn.cloudflare.steamstatic.com/steam/apps/489830/library_600x900.jpg','year'=>2026,'age'=>'12+','rating'=>4.8,'poster'=>'linear-gradient(135deg,#422006,#f59e0b)'],
  ['id'=>'gm005','name'=>'Crown Arena', 'tag'=>'Fighting', 'img'=>'https://cdn.cloudflare.steamstatic.com/steam/apps/1778820/library_600x900.jpg','year'=>2025,'age'=>'16+','rating'=>4.4,'poster'=>'linear-gradient(135deg,#450a0a,#f43f5e)'],
  ['id'=>'gm006','name'=>'Star Relic',  'tag'=>'Sci-Fi',   'img'=>'https://cdn.cloudflare.steamstatic.com/steam/apps/275850/library_600x900.jpg','year'=>2026,'age'=>'12+','rating'=>4.7,'poster'=>'linear-gradient(135deg,#0f172a,#22d3ee)'],
];
?>
<?php render_nav($active); ?>
<main class="page">
  <section class="section">
    <span class="eyebrow"><?php echo e(t('hero_eyebrow')); ?></span>
    <h1 class="display"><?php echo e(t('page_games')); ?></h1>
    <p class="lead"><?php echo e(t('tagline')); ?></p>
  </section>
  <section class="section">
    <div class="grid grid-3">
      <?php foreach ($games as $g):
        $imgUrl    = $g['img'];
        $posterImg = "url('" . addslashes($imgUrl) . "')";
        $detail = [
          'title'      => $g['name'],
          'genre'      => $g['tag'],
          'year'       => $g['year'],
          'duration'   => '—',
          'age'        => $g['age'],
          'description'=> t('empty_list'),
          'poster'     => $g['poster'],
          'poster_url' => $imgUrl,
          'stars'      => stars($g['rating'])
        ];
      ?>
      <article class="card" data-card data-genre="<?php echo e($g['tag']); ?>" data-text="<?php echo e(strtolower($g['name'].' '.$g['tag'])); ?>" style="--poster:<?php echo e($g['poster']); ?>;--poster-img:<?php echo e($posterImg); ?>">
        <img class="card-poster" src="<?php echo e($imgUrl); ?>" alt="<?php echo e($g['name']); ?>" loading="lazy">
        <span class="badge"><?php echo e($g['tag']); ?></span>
        <h3><?php echo e($g['name']); ?></h3>
        <div class="meta"><span class="chip"><?php echo e($g['tag']); ?></span><span class="chip"><?php echo e($g['year']); ?></span><span class="chip"><?php echo e($g['age']); ?></span></div>
        <div class="stars"><?php echo e(stars($g['rating'])); ?></div>
        <button class="btn small gold" data-detail="<?php echo e(json_encode($detail, JSON_UNESCAPED_UNICODE)); ?>"><?php echo e(t('details')); ?></button>
      </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<div class="modal" id="detailsModal"><div class="modal-card"><button class="btn" data-close style="float:right">&times;</button><div class="billboard" id="moviePoster" style="position:relative;width:100%;height:300px;--poster:linear-gradient(135deg,#111,#e50914)"></div><h2 class="title" id="movieTitle"></h2><p class="eyebrow" id="movieMeta"></p><div class="stars" id="movieStars"></div><p class="lead" id="movieDesc"></p></div></div>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
