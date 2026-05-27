<?php
require_once __DIR__ . '/php/functions.php';
require_login();
$userName = $_SESSION['name'] ?? $_SESSION['user'] ?? 'User';
$userEmail = $_SESSION['user'] ?? 'user@cineflow.app';
$initials = strtoupper(mb_substr($userName, 0, 1));
$tab = $_GET['tab'] ?? 'overview';
$allowed = ['overview','subscription','security','devices','profiles'];
if (!in_array($tab, $allowed, true)) $tab = 'overview';

// Mock account data
$memberSince = 'Jan 2024';
$nextBilling = '15 ' . date('M Y', strtotime('+1 month'));
$devices = [
  ['name' => t('device_chrome'), 'status' => t('active_now'), 'icon' => '💻'],
  ['name' => t('device_iphone'),  'status' => t('last_seen') . ' — 2h', 'icon' => '📱'],
  ['name' => t('device_smarttv'), 'status' => t('last_seen') . ' — 1d', 'icon' => '📺']
];
$profiles = [
  ['name' => $userName, 'color' => 'linear-gradient(135deg,#e50914,#b20710)', 'initial' => $initials],
  ['name' => 'Kids',    'color' => 'linear-gradient(135deg,#f59e0b,#ea580c)', 'initial' => 'K'],
  ['name' => 'Guest',   'color' => 'linear-gradient(135deg,#2563eb,#1e40af)', 'initial' => 'G']
];
render_page_head(t('page_account'));
?>
<?php render_nav('account'); ?>
<main class="page acc-page">
  <div class="acc-layout">
    <!-- Sidebar -->
    <aside class="acc-sidebar">
      <div class="acc-side-user">
        <span class="acc-side-avatar" style="background:linear-gradient(135deg,#e50914,#b20710)"><?php echo e($initials); ?></span>
        <div>
          <div class="acc-side-name"><?php echo e($userName); ?></div>
          <div class="acc-side-mail"><?php echo e($userEmail); ?></div>
        </div>
      </div>
      <nav class="acc-side-nav">
        <a href="?lang=<?php echo e(current_lang()); ?>&tab=overview"     class="<?php echo $tab==='overview'?'active':''; ?>"><span>👤</span><?php echo e(t('acc_overview')); ?></a>
        <a href="?lang=<?php echo e(current_lang()); ?>&tab=subscription" class="<?php echo $tab==='subscription'?'active':''; ?>"><span>💳</span><?php echo e(t('acc_subscription')); ?></a>
        <a href="?lang=<?php echo e(current_lang()); ?>&tab=security"     class="<?php echo $tab==='security'?'active':''; ?>"><span>🛡️</span><?php echo e(t('acc_security')); ?></a>
        <a href="?lang=<?php echo e(current_lang()); ?>&tab=devices"      class="<?php echo $tab==='devices'?'active':''; ?>"><span>📱</span><?php echo e(t('acc_devices')); ?></a>
        <a href="?lang=<?php echo e(current_lang()); ?>&tab=profiles"     class="<?php echo $tab==='profiles'?'active':''; ?>"><span>👥</span><?php echo e(t('acc_profiles')); ?></a>
      </nav>
    </aside>

    <!-- Content -->
    <section class="acc-content">
      <?php if ($tab === 'overview'): ?>
        <header class="acc-head">
          <span class="eyebrow"><?php echo e(t('welcome_back')); ?></span>
          <h1 class="display"><?php echo e($userName); ?></h1>
          <p class="lead"><?php echo e($userEmail); ?> · <?php echo e(t('acc_membership')); ?> <?php echo e($memberSince); ?></p>
        </header>
        <div class="acc-grid">
          <div class="acc-card">
            <span class="eyebrow"><?php echo e(t('acc_plan')); ?></span>
            <h3 class="acc-card-title"><?php echo e(t('acc_plan_premium')); ?></h3>
            <p class="acc-card-meta"><?php echo e(t('acc_next_billing')); ?>: <strong><?php echo e($nextBilling); ?></strong></p>
            <a class="btn small gold" href="?lang=<?php echo e(current_lang()); ?>&tab=subscription"><?php echo e(t('acc_manage_sub')); ?></a>
          </div>
          <div class="acc-card">
            <span class="eyebrow"><?php echo e(t('acc_payment_method')); ?></span>
            <h3 class="acc-card-title"><?php echo e(t('acc_card_masked')); ?></h3>
            <p class="acc-card-meta"><?php echo e(t('acc_next_billing')); ?>: <strong><?php echo e($nextBilling); ?></strong></p>
            <a class="btn small green" href="?lang=<?php echo e(current_lang()); ?>&tab=subscription"><?php echo e(t('acc_manage_payment')); ?></a>
          </div>
          <div class="acc-card">
            <span class="eyebrow"><?php echo e(t('acc_devices')); ?></span>
            <h3 class="acc-card-title"><?php echo count($devices); ?></h3>
            <p class="acc-card-meta"><?php echo e(t('acc_devices_info')); ?></p>
            <a class="btn small" href="?lang=<?php echo e(current_lang()); ?>&tab=devices"><?php echo e(t('acc_devices')); ?></a>
          </div>
        </div>

      <?php elseif ($tab === 'subscription'): ?>
        <header class="acc-head">
          <h1 class="display"><?php echo e(t('acc_subscription')); ?></h1>
          <p class="lead"><?php echo e(t('acc_plan')); ?>: <strong><?php echo e(t('acc_plan_premium')); ?></strong></p>
        </header>
        <div class="acc-grid">
          <div class="acc-card">
            <span class="eyebrow"><?php echo e(t('acc_next_billing')); ?></span>
            <h3 class="acc-card-title"><?php echo e($nextBilling); ?></h3>
            <p class="acc-card-meta"><?php echo e(t('acc_payment_method')); ?>: <strong><?php echo e(t('acc_card_masked')); ?></strong></p>
            <div class="acc-actions">
              <button type="button" class="btn small gold"><?php echo e(t('acc_manage_sub')); ?></button>
              <button type="button" class="btn small"><?php echo e(t('acc_change_plan')); ?></button>
              <button type="button" class="btn small green"><?php echo e(t('acc_manage_payment')); ?></button>
            </div>
          </div>
        </div>

      <?php elseif ($tab === 'security'): ?>
        <header class="acc-head">
          <h1 class="display"><?php echo e(t('acc_security')); ?></h1>
          <p class="lead"><?php echo e(t('acc_security_info')); ?></p>
        </header>
        <div class="acc-grid">
          <div class="acc-card"><span class="eyebrow">2FA</span><h3 class="acc-card-title">✅</h3><p class="acc-card-meta"><?php echo e(t('active_now')); ?></p></div>
          <div class="acc-card"><span class="eyebrow"><?php echo e(t('password')); ?></span><h3 class="acc-card-title">•••••••</h3><button class="btn small"><?php echo e(t('edit')); ?></button></div>
        </div>

      <?php elseif ($tab === 'devices'): ?>
        <header class="acc-head">
          <h1 class="display"><?php echo e(t('acc_devices')); ?></h1>
          <p class="lead"><?php echo e(t('acc_devices_info')); ?></p>
        </header>
        <div class="acc-grid">
          <?php foreach ($devices as $d): ?>
          <div class="acc-card">
            <span class="acc-device-icon"><?php echo e($d['icon']); ?></span>
            <h3 class="acc-card-title"><?php echo e($d['name']); ?></h3>
            <p class="acc-card-meta"><?php echo e($d['status']); ?></p>
          </div>
          <?php endforeach; ?>
        </div>

      <?php else: /* profiles */ ?>
        <header class="acc-head">
          <h1 class="display"><?php echo e(t('acc_profiles')); ?></h1>
          <p class="lead"><?php echo e(t('acc_profiles_info')); ?></p>
        </header>
        <div class="acc-grid">
          <?php foreach ($profiles as $p): ?>
          <div class="acc-card acc-profile-card">
            <span class="acc-profile-avatar" style="background:<?php echo e($p['color']); ?>"><?php echo e($p['initial']); ?></span>
            <h3 class="acc-card-title"><?php echo e($p['name']); ?></h3>
            <button type="button" class="btn small"><?php echo e(t('edit')); ?></button>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
