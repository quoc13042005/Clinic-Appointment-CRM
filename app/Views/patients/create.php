<h1>Add Patient</h1>
<form action="/patients/store" method="POST" style="max-width: 600px; background: white; padding: 20px; border-radius: 8px;">
    <div style="display:none;"><label>Website</label><input type="text" name="website_hp" value=""></div>
    <div class="form-group"><label>Name</label><input type="text" name="name" value="<?= e(old('name')) ?>"><?php if (isset($errors['name'])): ?><div class="text-danger"><?= e($errors['name']) ?></div><?php endif; ?></div>
    <div class="form-group"><label>ID Card (CMND/CCCD)</label><input type="text" name="id_card" value="<?= e(old('id_card')) ?>"><?php if (isset($errors['id_card'])): ?><div class="text-danger"><?= e($errors['id_card']) ?></div><?php endif; ?></div>
    <div class="form-group"><label>Phone</label><input type="text" name="phone" value="<?= e(old('phone')) ?>"></div>
    <div class="form-group"><label>Date of Birth</label><input type="date" name="date_of_birth" value="<?= e(old('date_of_birth')) ?>"><?php if (isset($errors['date_of_birth'])): ?><div class="text-danger"><?= e($errors['date_of_birth']) ?></div><?php endif; ?></div>
    <div class="form-group"><label>Address</label><textarea name="address"><?= e(old('address')) ?></textarea></div>
    <button type="submit" class="btn">Save Patient</button>
</form>