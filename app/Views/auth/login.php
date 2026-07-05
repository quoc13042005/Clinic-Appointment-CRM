<div style="max-width: 400px; margin: 50px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Clinic Login</h2>
    <?php if (isset($errors['general'])): ?><div class="alert alert-error"><?= e($errors['general']) ?></div><?php endif; ?>
    <form action="/login" method="POST">
        <!-- T09: Honeypot -->
        <input type="text" name="website_hp" style="display:none" tabindex="-1" autocomplete="off">
        
        <div class="form-group"><label>Email</label><input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>