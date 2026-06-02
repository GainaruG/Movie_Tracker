<?php
require_once __DIR__ . '/php/auth.php';
$addingAccount = isset($_GET['add']);
if (is_logged_in() && !$addingAccount) { header('Location: dashboard.php?lang=' . current_lang()); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email === '' || $password === '') { $error = t('required'); }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = t('invalid_email'); }
    elseif (login_user($email, $password)) { header('Location: dashboard.php?lang=' . current_lang()); exit; }
    else { $error = t('bad_login'); }
}
render_page_head(t('login'));
?>
<?php render_nav('login'); ?>
<main class="auth">
  <section class="auth-art">
    <span class="eyebrow"><?php echo e(t('secure_access')); ?></span>
    <h1 class="display"><?php echo e($addingAccount ? t('add_account') : t('login')); ?></h1>
    <p class="lead"><?php echo e($addingAccount ? t('add_account_intro') : t('login_intro')); ?></p>
  </section>
  <section class="auth-box">
    <form class="form-card" method="POST">
      <h2 class="title"><?php echo e($addingAccount ? t('add_account') : t('login')); ?></h2>
      <?php if ($error): ?><div class="alert error"><?php echo e($error); ?></div><?php endif; ?>
      <div class="field"><label><?php echo e(t('email')); ?></label><input type="email" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>"></div>
      <div class="field"><label><?php echo e(t('password')); ?></label><input type="password" name="password" required minlength="6"></div>
      <button class="btn primary" style="width:100%" type="submit"><?php echo e(t('login')); ?></button>
      <p class="lead" style="font-size:15px;margin-top:16px"><a class="chip" href="<?php echo e(url_lang('register.php')); ?>"><?php echo e(t('register')); ?></a></p>
    </form>
  </section>
</main>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
