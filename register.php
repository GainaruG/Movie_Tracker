<?php
require_once __DIR__ . '/php/auth.php';
if (is_logged_in()) { header('Location: dashboard.php?lang=' . current_lang()); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_text($_POST['name'] ?? '', 80);
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    if ($name === '' || $email === '' || $password === '' || $confirm === '') { $error = t('required'); }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = t('invalid_email'); }
    elseif (strlen($password) < 6) { $error = t('short_password'); }
    elseif ($password !== $confirm) { $error = t('passwords'); }
    elseif (!register_user($name, $email, $password)) { $error = t('duplicate'); }
    else { $_SESSION['user'] = $email; $_SESSION['name'] = $name; set_flash('success', t('success_register')); header('Location: dashboard.php?lang=' . current_lang()); exit; }
}
render_page_head(t('register'));
?>
<?php render_nav('register'); ?>
<main class="auth">
  <section class="auth-art">
    <span class="eyebrow"><?php echo e(t('new_account')); ?></span>
    <h1 class="display"><?php echo e(t('register')); ?></h1>
    <p class="lead"><?php echo e(t('register_intro')); ?></p>
  </section>
  <section class="auth-box">
    <form class="form-card" method="POST">
      <h2 class="title"><?php echo e(t('join')); ?></h2>
      <?php if ($error): ?><div class="alert error"><?php echo e($error); ?></div><?php endif; ?>
      <div class="field"><label><?php echo e(t('name')); ?></label><input name="name" required value="<?php echo e($_POST['name'] ?? ''); ?>"></div>
      <div class="field"><label><?php echo e(t('email')); ?></label><input type="email" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>"></div>
      <div class="field"><label><?php echo e(t('password')); ?></label><input type="password" name="password" required minlength="6"></div>
      <div class="field"><label><?php echo e(t('confirm')); ?></label><input type="password" name="confirm" required minlength="6"></div>
      <button class="btn primary" style="width:100%" type="submit"><?php echo e(t('register')); ?></button>
    </form>
  </section>
</main>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
