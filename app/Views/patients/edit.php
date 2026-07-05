<h1>Edit Patient</h1>
<?php if (isset($errors['general'])): ?><div class="alert alert-error"><?= e($errors['general']) ?></div><?php endif; ?>
<form action="/patients/update" method="POST" style="max-width: 600px; background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
    <input type="hidden" name="id" value="<?= e((string)($patient['id'] ?? old('id'))) ?>">
    <div class="form-group"><label>Name</label><input type="text" name="name" value="<?= e(old('name')) ?>"><?php if (isset($errors['name'])): ?><div class="text-danger"><?= e($errors['name']) ?></div><?php endif; ?></div>
    <div class="form-group"><label>ID Card (CMND/CCCD)</label><input type="text" name="id_card" value="<?= e(old('id_card')) ?>"><?php if (isset($errors['id_card'])): ?><div class="text-danger"><?= e($errors['id_card']) ?></div><?php endif; ?></div>
    <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?= e(old('phone')) ?>"></div>
    <div class="form-group"><label>Date of Birth</label><input type="date" name="date_of_birth" value="<?= e(old('date_of_birth')) ?>"><?php if (isset($errors['date_of_birth'])): ?><div class="text-danger"><?= e($errors['date_of_birth']) ?></div><?php endif; ?></div>
    <div class="form-group"><label>Address</label><textarea name="address"><?= e(old('address')) ?></textarea></div>
    <button type="submit" class="btn">Update Patient</button>
</form>
<form action="/patients/delete" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa patient này?');"><input type="hidden" name="id" value="<?= e((string)($patient['id'] ?? old('id'))) ?>"><button type="submit" class="btn btn-danger">Delete Patient</button></form>