<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_ROOT', dirname(__DIR__));
define('DATA_DIR', APP_ROOT . DIRECTORY_SEPARATOR . 'data');
define('LANG_DIR', DATA_DIR . DIRECTORY_SEPARATOR . 'lang');
define('APP_BRAND', 'MOVIE TRACKER');

function data_path(string $file): string {
    return DATA_DIR . DIRECTORY_SEPARATOR . $file;
}

function load_json(string $file, array $fallback = []): array {
    $path = str_contains($file, DIRECTORY_SEPARATOR) ? $file : data_path($file);
    if (!file_exists($path)) {
        return $fallback;
    }
    $content = trim((string) file_get_contents($path));
    if ($content === '') {
        return $fallback;
    }
    $data = json_decode($content, true);
    return is_array($data) ? $data : $fallback;
}

function save_json(string $file, array $data): bool {
    $path = str_contains($file, DIRECTORY_SEPARATOR) ? $file : data_path($file);
    if (!is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

function e($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function clean_text($value, int $max = 255): string {
    $value = trim(strip_tags((string) $value));
    return function_exists('mb_substr') ? mb_substr($value, 0, $max) : substr($value, 0, $max);
}

function supported_langs(): array {
    return ['ro', 'en', 'ru'];
}

function current_lang(): string {
    $allowed = supported_langs();
    $lang = null;
    if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed, true)) {
        $lang = $_GET['lang'];
    } elseif (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $allowed, true)) {
        $lang = $_SESSION['lang'];
    } elseif (isset($_COOKIE['mt_lang']) && in_array($_COOKIE['mt_lang'], $allowed, true)) {
        $lang = $_COOKIE['mt_lang'];
    }
    $lang = $lang ?: 'ro';
    $_SESSION['lang'] = $lang;
    if (!isset($_COOKIE['mt_lang']) || $_COOKIE['mt_lang'] !== $lang) {
        if (!headers_sent()) {
            setcookie('mt_lang', $lang, [
                'expires' => time() + 60 * 60 * 24 * 365,
                'path' => '/',
                'samesite' => 'Lax'
            ]);
        }
        $_COOKIE['mt_lang'] = $lang;
    }
    return $lang;
}

function dict(): array {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    foreach (supported_langs() as $code) {
        $file = LANG_DIR . DIRECTORY_SEPARATOR . $code . '.json';
        $cache[$code] = load_json($file, []);
    }
    return $cache;
}

function t(string $key): string {
    $all = dict();
    $lang = current_lang();
    return $all[$lang][$key] ?? $all['en'][$key] ?? $all['ro'][$key] ?? $key;
}

function url_lang(string $url): string {
    $hash = '';
    if (str_contains($url, '#')) {
        [$url, $hash] = explode('#', $url, 2);
        $hash = '#' . $hash;
    }
    return $url . (str_contains($url, '?') ? '&' : '?') . 'lang=' . current_lang() . $hash;
}

function is_logged_in(): bool {
    return !empty($_SESSION['user']);
}

function saved_accounts(): array {
    $accounts = $_SESSION['saved_accounts'] ?? [];
    if (!empty($_SESSION['user']) && empty($accounts[$_SESSION['user']])) {
        $accounts[$_SESSION['user']] = [
            'email' => $_SESSION['user'],
            'name' => $_SESSION['name'] ?? $_SESSION['user'],
            'last_active' => date('c')
        ];
        $_SESSION['saved_accounts'] = $accounts;
    }
    return $accounts;
}

function save_account_session(array $user): void {
    $email = $user['email'] ?? '';
    if ($email === '') return;
    $_SESSION['saved_accounts'][$email] = [
        'email' => $email,
        'name' => $user['name'] ?? $email,
        'last_active' => date('c')
    ];
}

function activate_account(string $email): bool {
    $accounts = saved_accounts();
    if (empty($accounts[$email])) {
        return false;
    }
    $_SESSION['user'] = $accounts[$email]['email'];
    $_SESSION['name'] = $accounts[$email]['name'];
    $_SESSION['saved_accounts'][$email]['last_active'] = date('c');
    return true;
}

function remove_saved_account(string $email): void {
    unset($_SESSION['saved_accounts'][$email]);
    if (($_SESSION['user'] ?? '') === $email) {
        $next = reset($_SESSION['saved_accounts']);
        if (is_array($next)) {
            $_SESSION['user'] = $next['email'];
            $_SESSION['name'] = $next['name'];
        } else {
            unset($_SESSION['user'], $_SESSION['name'], $_SESSION['saved_accounts']);
        }
    }
}
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash(): string {
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return '<div class="alert ' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
}

function boot_head(): void {
    echo '<script>(function(){var t=localStorage.getItem("mt-theme");if(t!=="light"&&t!=="dark")t="dark";document.documentElement.setAttribute("data-theme",t);document.documentElement.style.colorScheme=t;})();</script>';
}

function i18n_script(): void {
    $lang = current_lang();
    $all = dict();
    $keys = ['genre','year','rating','details','duration','age_rating','close','description','edit','delete','watched','unwatched','g_Action','g_Comedy','g_Drama','g_Horror','g_Sci-Fi','g_Adventure','g_Thriller','play','add_to_list','remove_from_list','notif_empty'];
    $obj = [];
    foreach ($keys as $k) {
        $obj[$k] = $all[$lang][$k] ?? $all['en'][$k] ?? $k;
    }
    echo '<script>window.MT_I18N=' . json_encode($obj, JSON_UNESCAPED_UNICODE) . ';</script>';
}

function nav_routes(): array {
    return [
        'home'        => 'index.php',
        'tv_shows'    => 'series.php',
        'movies'      => 'movies.php',
        'games'       => 'games.php',
        'latest'      => 'latest.php',
        'my_list'     => 'mylist.php',
        'browse_lang' => 'browse-lang.php',
        'account'     => 'account.php',
        'contact'     => 'contact.php',
        'dashboard'   => 'dashboard.php'
    ];
}

function nav_url(string $key): string {
    $routes = nav_routes();
    return url_lang($routes[$key] ?? 'index.php');
}

function render_nav(string $active = ''): void {
    $routes = nav_routes();
    $navLinks = [
        'home'        => t('home'),
        'tv_shows'    => t('tv_shows'),
        'movies'      => t('movies'),
        'games'       => t('games'),
        'latest'      => t('latest'),
        'my_list'     => t('my_list'),
        'browse_lang' => t('browse_lang')
    ];
    echo '<nav class="nfx-nav">';
    // Logo
    echo '<a class="nfx-logo" href="' . e(url_lang('index.php')) . '">' . e(APP_BRAND) . '</a>';
    // Hamburger (mobile)
    echo '<button class="hamb" data-menu aria-label="Menu">&#9776;</button>';
    // Left nav links
    echo '<div class="nfx-nav-links" data-links>';
    foreach ($navLinks as $key => $label) {
        $cls = ($active === $key) ? ' class="active"' : '';
        echo '<a' . $cls . ' href="' . e(url_lang($routes[$key])) . '">' . e($label) . '</a>';
    }
    echo '</div>';
    // Right icons
    echo '<div class="nfx-nav-right">';
    // Search icon -> jumps to filters on index
    echo '<a class="nfx-icon" href="' . e(url_lang('index.php#movies')) . '" aria-label="' . e(t('search')) . '"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg></a>';
    // Kids button
    echo '<a class="nfx-kids" href="' . e(url_lang('index.php#movies')) . '">' . e(t('kids')) . '</a>';
    // Notifications bell with dropdown
    $notifications = [
        ['icon' => '🎬', 'text' => t('notif_new_episode'),     'time' => str_replace('{n}', '12', t('notif_minutes_ago'))],
        ['icon' => '💳', 'text' => t('notif_renews'),          'time' => str_replace('{n}', '3',  t('notif_hours_ago'))],
        ['icon' => '✓',  'text' => t('notif_profile_updated'), 'time' => str_replace('{n}', '2',  t('notif_days_ago'))],
    ];
    $notifCount = count($notifications);
    echo '<div class="notif-wrapper">';
    echo '<button class="nfx-icon nfx-bell" type="button" data-notif-toggle aria-label="' . e(t('notifications')) . '"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>';
    if ($notifCount > 0) echo '<span class="nfx-badge">' . e($notifCount) . '</span>';
    echo '</button>';
    echo '<div class="notif-menu" data-notif-menu>';
    echo '<div class="notif-head"><span class="notif-title">' . e(t('notifications_title')) . '</span></div>';
    if ($notifCount === 0) {
        echo '<div class="notif-empty">' . e(t('notif_empty')) . '</div>';
    } else {
        echo '<div class="notif-list">';
        foreach ($notifications as $n) {
            echo '<div class="notif-item"><span class="notif-icon">' . e($n['icon']) . '</span><div class="notif-body"><div class="notif-text">' . e($n['text']) . '</div><div class="notif-time">' . e($n['time']) . '</div></div></div>';
        }
        echo '</div>';
        echo '<button class="notif-mark" type="button" data-notif-mark>' . e(t('notif_mark_all')) . '</button>';
    }
    echo '</div>';
    echo '</div>';
    // Language selector
    echo '<select class="nfx-lang-sel" data-lang aria-label="' . e(t('language')) . '">';
    foreach (['ro' => 'RO', 'en' => 'EN', 'ru' => 'RU'] as $code => $label) {
        echo '<option value="' . e($code) . '"' . (current_lang() === $code ? ' selected' : '') . '>' . e($label) . '</option>';
    }
    echo '</select>';
    // Theme toggle
    echo '<button class="nfx-icon" data-theme-toggle type="button" aria-label="' . e(t('theme')) . '"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg></button>';
    // Profile avatar with dropdown
    if (is_logged_in()) {
        $rawUserName = $_SESSION['name'] ?? $_SESSION['user'] ?? 'User';
        $currentEmail = $_SESSION['user'] ?? '';
        $userName = e($rawUserName);
        $initials = strtoupper(function_exists('mb_substr') ? mb_substr($rawUserName, 0, 1) : substr($rawUserName, 0, 1));
        $accounts = saved_accounts();
        echo '<div class="profile-wrapper">';
        echo '<button class="nfx-profile" data-profile-toggle title="' . $userName . '"><span class="nfx-profile-img">' . e($initials) . '</span><svg class="nfx-caret" width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg></button>';
        echo '<div class="profile-menu" data-profile-menu>';
        echo '<div class="profile-menu-user"><span class="profile-menu-avatar">' . e($initials) . '</span><span class="profile-menu-name">' . $userName . '</span></div>';
        if (!empty($accounts)) {
            echo '<div class="profile-menu-divider"></div>';
            echo '<div class="profile-menu-section-title">' . e(t('saved_accounts')) . '</div>';
            foreach ($accounts as $account) {
                $accountEmail = $account['email'] ?? '';
                $accountNameRaw = $account['name'] ?? $accountEmail;
                $accountInitial = strtoupper(function_exists('mb_substr') ? mb_substr($accountNameRaw, 0, 1) : substr($accountNameRaw, 0, 1));
                $activeClass = ($accountEmail === $currentEmail) ? ' active' : '';
                echo '<a class="profile-account' . $activeClass . '" href="' . e(url_lang('switch-account.php?action=switch&email=' . urlencode($accountEmail))) . '"><span class="profile-account-avatar">' . e($accountInitial) . '</span><span class="profile-account-info"><strong>' . e($accountNameRaw) . '</strong><small>' . e($accountEmail) . '</small></span><span class="profile-account-check">✓</span></a>';
            }
        }
        echo '<div class="profile-menu-divider"></div>';
        echo '<a class="profile-menu-item" href="' . e(url_lang('login.php?add=1')) . '">＋ ' . e(t('add_account')) . '</a>';
        echo '<a class="profile-menu-item" href="' . e(url_lang('account.php')) . '"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>' . e(t('manage_profiles')) . '</a>';
        echo '<a class="profile-menu-item" href="' . e(url_lang('account.php')) . '"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>' . e(t('account')) . '</a>';
        echo '<a class="profile-menu-item" href="' . e(url_lang('contact.php')) . '"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>' . e(t('help')) . '</a>';
        echo '<div class="profile-menu-divider"></div>';
        echo '<a class="profile-menu-item profile-menu-signout" href="' . e(url_lang('logout.php')) . '">' . e(t('sign_out')) . '</a>';
        echo '</div></div>';
    } else {
        echo '<a class="nfx-login" href="' . e(url_lang('login.php')) . '">' . e(t('login')) . '</a>';
    }
    echo '</div>'; // nfx-nav-right
    echo '</nav>';
}

function render_footer(): void {
    echo '';
}

function movie_gradient(string $seed): string {
    $gradients = ['linear-gradient(135deg,#161a30,#e50914)','linear-gradient(135deg,#09203f,#537895)','linear-gradient(135deg,#200122,#6f0000)','linear-gradient(135deg,#141e30,#e8c547)','linear-gradient(135deg,#000428,#004e92)','linear-gradient(135deg,#42275a,#734b6d)','linear-gradient(135deg,#1f4037,#99f2c8)','linear-gradient(135deg,#2b5876,#4e4376)'];
    return $gradients[abs(crc32($seed)) % count($gradients)];
}

function stars($rating): string {
    $rating = max(1, min(5, (int) round((float) $rating)));
    return str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
}

function tg(string $genre): string {
    return t('g_' . $genre) ?: $genre;
}

function movie_desc(array $movie): string {
    $lang = current_lang();
    if ($lang !== 'en' && !empty($movie['description_' . $lang])) {
        return $movie['description_' . $lang];
    }
    return $movie['description'] ?? '';
}

function categories(array $movies): array {
    $out = [];
    foreach ($movies as $movie) {
        $out[$movie['genre']][] = $movie;
    }
    return $out;
}

// Reusable card renderer (used by all pages)
function render_card(array $movie): void {
    $desc = movie_desc($movie);
    $detail = [
        'title' => $movie['title'],
        'genre' => $movie['genre'],
        'year' => $movie['year'],
        'duration' => $movie['duration'],
        'age' => $movie['age'],
        'description' => $desc,
        'poster' => $movie['poster'],
        'poster_url' => $movie['poster_url'] ?? '',
        'stars' => stars($movie['rating'])
    ];
    $posterUrl = $movie['poster_url'] ?? '';
    $posterImg = !empty($posterUrl) ? "url('" . addslashes($posterUrl) . "')" : 'none';
    echo '<article class="card" data-card data-genre="' . e($movie['genre']) . '" data-text="' . e(strtolower($movie['title'] . ' ' . $movie['genre'] . ' ' . ($movie['section'] ?? ''))) . '" style="--poster:' . e($movie['poster']) . ';--poster-img:' . e($posterImg) . '">';
    if (!empty($posterUrl)) {
        echo '<img class="card-poster" src="' . e($posterUrl) . '" alt="' . e($movie['title']) . '" loading="lazy">';
    }
    echo '<span class="badge">' . e($movie['section'] ?? '') . '</span>';
    echo '<h3>' . e($movie['title']) . '</h3>';
    echo '<div class="meta"><span class="chip">' . e(tg($movie['genre'])) . '</span><span class="chip">' . e($movie['year']) . '</span><span class="chip">' . e($movie['age']) . '</span></div>';
    echo '<div class="stars">' . e(stars($movie['rating'])) . '</div>';
    echo '<p>' . e($desc) . '</p>';
    echo '<button class="btn small gold" data-detail="' . e(json_encode($detail, JSON_UNESCAPED_UNICODE)) . '">' . e(t('details')) . '</button>';
    echo '</article>';
}

// Render empty state when a section has no titles
function render_empty(): void {
    echo '<div class="empty-state"><span class="empty-icon">🎬</span><p>' . e(t('empty_list')) . '</p></div>';
}

// Common page header (used by listing pages)
function render_page_head(string $title): void {
    echo '<!DOCTYPE html><html lang="' . e(current_lang()) . '" data-theme="dark"><head>';
    echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . e($title) . ' | ' . e(APP_BRAND) . '</title>';
    boot_head();
    i18n_script();
    echo '<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="css/style.css">';
    echo '</head><body>';
}
?>
