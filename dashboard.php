<?php
require_once __DIR__ . '/php/save_data.php';
require_login();
$movies = load_json('items.json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action_type'] ?? '';
    if ($action === 'add') {
        $movie = movie_from_post($_POST);
        if ($movie) {
            $movie['id'] = uniqid('mv_', true);
            $movies[] = $movie;
            save_json('items.json', $movies);
            set_flash('success', t('success_add'));
        } else { set_flash('error', t('required')); }
    }
    if ($action === 'edit') {
        $id = $_POST['id'] ?? '';
        $movie = movie_from_post($_POST);
        foreach ($movies as &$item) {
            if (($item['id'] ?? '') === $id && $movie) { $item = array_merge($item, $movie, ['id' => $id]); set_flash('success', t('success_edit')); break; }
        }
        unset($item);
        save_json('items.json', $movies);
    }
    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        $movies = array_values(array_filter($movies, fn($m) => ($m['id'] ?? '') !== $id));
        save_json('items.json', $movies);
        set_flash('success', t('success_delete'));
    }
    header('Location: dashboard.php?lang=' . current_lang());
    exit;
}
$genres = array_keys(categories($movies));
function admin_card(array $movie): void {
    $desc = movie_desc($movie);
    $detail = ['title'=>$movie['title'],'genre'=>$movie['genre'],'year'=>$movie['year'],'duration'=>$movie['duration'],'age'=>$movie['age'],'description'=>$desc,'poster'=>$movie['poster'],'poster_url'=>$movie['poster_url'] ?? '','stars'=>stars($movie['rating'])];
    $posterImg = !empty($movie['poster_url']) ? "url('" . addslashes($movie['poster_url']) . "')" : 'none';
    echo '<article class="card" data-card data-genre="' . e($movie['genre']) . '" data-text="' . e(strtolower($movie['title'] . ' ' . $movie['genre'])) . '" style="--poster:' . e($movie['poster']) . ';--poster-img:' . e($posterImg) . '">';
    echo '<span class="badge">' . e($movie['section']) . '</span>';
    echo '<h3>' . e($movie['title']) . '</h3>';
    echo '<div class="meta"><span class="chip">' . e(tg($movie['genre'])) . '</span><span class="chip">' . e($movie['year']) . '</span><span class="chip">' . e($movie['status'] === 'watched' ? t('watched') : t('unwatched')) . '</span></div>';
    echo '<div class="stars">' . e(stars($movie['rating'])) . '</div>';
    echo '<p>' . e($desc) . '</p>';
    echo '<div class="actions">';
    echo '<button class="btn small gold" data-detail="' . e(json_encode($detail, JSON_UNESCAPED_UNICODE)) . '">' . e(t('details')) . '</button>';
    echo '<button class="btn small green" onclick="fillMovieForm(JSON.parse(this.dataset.movie))" data-movie="' . e(json_encode($movie, JSON_UNESCAPED_UNICODE)) . '">' . e(t('edit')) . '</button>';
    echo '<form method="POST"><input type="hidden" name="action_type" value="delete"><input type="hidden" name="id" value="' . e($movie['id']) . '"><button class="btn small danger" onclick="return confirm(\'' . e(t('delete')) . '?\')">' . e(t('delete')) . '</button></form>';
    echo '</div></article>';
}
render_page_head(t('dashboard'));
?>
<?php render_nav('dashboard'); ?>
<main class="page">
  <section class="section">
    <span class="eyebrow"><?php echo e(t('protected')); ?></span>
    <h1 class="display"><?php echo e(t('dashboard')); ?></h1>
    <p class="lead"><?php echo e(t('welcome_back')); ?>, <?php echo e($_SESSION['name'] ?? $_SESSION['user']); ?>. <?php echo e(t('dashboard_intro')); ?></p>
    <?php echo flash(); ?>
    <button class="btn primary" onclick="fillMovieForm(null)"><?php echo e(t('add')); ?></button>
  </section>
  <section class="section">
    <div class="filters">
      <input data-search oninput="filterMovies()" placeholder="<?php echo e(t('search')); ?>">
      <select data-genre onchange="filterMovies()">
        <option value="all"><?php echo e(t('all')); ?></option>
        <?php foreach ($genres as $genre): ?>
          <option value="<?php echo e($genre); ?>"><?php echo e(tg($genre)); ?></option>
        <?php endforeach; ?>
      </select>
      <span class="panel"><?php echo e(t('movies_count')); ?>: <?php echo count($movies); ?></span>
      <span class="panel"><?php echo e(t('users_count')); ?>: <?php echo count(load_json('users.json')); ?></span>
    </div>
    <div class="library"><?php foreach ($movies as $movie) admin_card($movie); ?></div>
  </section>
</main>
<div class="modal" id="movieFormModal"><div class="modal-card"><button class="btn" data-close style="float:right">&times;</button>
  <h2 class="title"><?php echo e(t('add')); ?></h2>
  <form id="movieForm" method="POST">
    <input type="hidden" name="action_type" value="add"><input type="hidden" name="id">
    <div class="modal-grid">
      <div class="field"><label><?php echo e(t('title_label')); ?></label><input name="title" required></div>
      <div class="field"><label><?php echo e(t('genre')); ?></label><input name="genre" required></div>
      <div class="field"><label><?php echo e(t('year')); ?></label><input name="year" type="number" min="1900" max="2100" required></div>
      <div class="field"><label><?php echo e(t('rating')); ?></label><input name="rating" type="number" min="1" max="5" step="0.1" required></div>
      <div class="field"><label><?php echo e(t('section_label')); ?></label><select name="section"><option>Trending</option><option>Popular</option><option>New Releases</option><option>Recommended</option></select></div>
      <div class="field"><label><?php echo e(t('status')); ?></label><select name="status"><option value="unwatched"><?php echo e(t('unwatched')); ?></option><option value="watched"><?php echo e(t('watched')); ?></option></select></div>
      <div class="field"><label><?php echo e(t('duration')); ?></label><input name="duration" value="120 min"></div>
      <div class="field"><label><?php echo e(t('age_rating')); ?></label><input name="age" value="13+"></div>
    </div>
    <div class="field"><label><?php echo e(t('poster_url')); ?></label><input name="poster_url" placeholder="https://..."></div>
    <div class="field"><label><?php echo e(t('description')); ?></label><textarea name="description" rows="4" required></textarea></div>
    <button class="btn primary" type="submit"><?php echo e(t('save')); ?></button>
  </form>
</div></div>
<div class="modal" id="detailsModal"><div class="modal-card"><button class="btn" data-close style="float:right">&times;</button><div class="billboard" id="moviePoster" style="position:relative;width:100%;height:300px;--poster:linear-gradient(135deg,#111,#e50914)"></div><h2 class="title" id="movieTitle"></h2><p class="eyebrow" id="movieMeta"></p><div class="stars" id="movieStars"></div><p class="lead" id="movieDesc"></p></div></div>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
