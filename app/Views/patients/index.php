<div style="display:flex; justify-content: space-between; align-items:center;">
    <h1>Patient Management</h1>
    <a href="/patients/create" class="btn">+ Add Patient</a>
</div>

<form method="GET" action="/patients" style="margin-bottom: 20px;">
    <input type="text" name="q" value="<?= e($keyword ?? '') ?>" placeholder="Tìm tên, CMND, SĐT" style="padding:8px; width:250px;">
    <select name="sort" style="padding:8px;"><option value="created_at" <?= ($sort ?? '') === 'created_at' ? 'selected' : '' ?>>Created At</option><option value="name" <?= ($sort ?? '') === 'name' ? 'selected' : '' ?>>Name</option></select>
    <select name="direction" style="padding:8px;"><option value="DESC" <?= ($direction ?? '') === 'DESC' ? 'selected' : '' ?>>DESC</option><option value="ASC" <?= ($direction ?? '') === 'ASC' ? 'selected' : '' ?>>ASC</option></select>
    <button type="submit" class="btn">Filter</button>
</form>

<table>
    <thead><tr><th>ID</th><th>Name</th><th>ID Card</th><th>Phone</th><th>DOB</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($patients ?? [] as $patient): ?>
        <tr><td><?= e((string)$patient['id']) ?></td><td><?= e($patient['name']) ?></td><td><?= e($patient['id_card']) ?></td><td><?= e($patient['phone']) ?></td><td><?= e($patient['date_of_birth']) ?></td><td><a href="/patients/edit?id=<?= e((string)$patient['id']) ?>" class="btn">Edit</a></td></tr>
        <?php endforeach; ?>
        <?php if(empty($patients)): ?><tr><td colspan="6" style="text-align:center;">No patients found.</td></tr><?php endif; ?>
    </tbody>
</table>

<?php if (($totalPages ?? 1) > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="/patients?page=<?= $i ?>&q=<?= urlencode($keyword ?? '') ?>&sort=<?= urlencode($sort ?? '') ?>&direction=<?= urlencode($direction ?? '') ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>