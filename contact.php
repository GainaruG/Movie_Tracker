<?php
require_once __DIR__ . '/php/functions.php';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_text($_POST['name'] ?? '', 80);
    $email = trim($_POST['email'] ?? '');
    $subject = clean_text($_POST['subject'] ?? '', 120);
    $message = clean_text($_POST['message'] ?? '', 500);
    if ($name === '' || $email === '' || $subject === '' || $message === '') { $error = t('required'); }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $error = t('invalid_email'); }
    else {
        $messages = load_json('mesaje.json');
        $messages[] = ['id' => uniqid('msg_', true), 'name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message, 'created_at' => date('c')];
        save_json('mesaje.json', $messages);
        $success = t('success_contact');
        $_POST = [];
    }
}
render_page_head(t('contact'));
?>
<?php render_nav('contact'); ?>
<main class="page">
  <section class="section grid" style="grid-template-columns:repeat(auto-fit,minmax(280px,1fr));align-items:start;gap:24px">
    <div class="panel">
      <span class="eyebrow"><?php echo e(t('feedback')); ?></span>
      <h1 class="display"><?php echo e(t('contact')); ?></h1>
      <p class="lead"><?php echo e(t('contact_intro')); ?></p>
      <p class="lead"><?php echo e(t('file_used')); ?>: <strong>data/mesaje.json</strong></p>
    </div>
    <form class="form-card" method="POST">
      <?php if ($success): ?><div class="alert success"><?php echo e($success); ?></div><?php endif; ?>
      <?php if ($error): ?><div class="alert error"><?php echo e($error); ?></div><?php endif; ?>
      <div class="field"><label><?php echo e(t('name')); ?></label><input name="name" required value="<?php echo e($_POST['name'] ?? ''); ?>"></div>
      <div class="field"><label><?php echo e(t('email')); ?></label><input type="email" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>"></div>
      <div class="field"><label><?php echo e(t('subject')); ?></label><input name="subject" required value="<?php echo e($_POST['subject'] ?? ''); ?>"></div>
      <div class="field"><label><?php echo e(t('message')); ?></label><textarea name="message" maxlength="500" rows="6" oninput="updateCount(this)" required><?php echo e($_POST['message'] ?? ''); ?></textarea><span class="eyebrow"><span id="count">0</span>/500</span></div>
      <button class="btn primary" type="submit"><?php echo e(t('send')); ?></button>
    </form>
  </section>
</main>
<?php render_footer(); ?>
<script src="js/script.js"></script>
</body></html>
