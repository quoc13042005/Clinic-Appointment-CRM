<div style="display:flex; justify-content: space-between; align-items:center;">
    <h1>Appointment Management</h1>
    <a href="/appointments/create" class="btn">+ Create Appointment</a>
</div>

<form method="GET" action="/appointments" style="margin-bottom: 20px;">
    <input type="text" name="q" value="<?= e($keyword ?? '') ?>" placeholder="Tìm mã, bác sĩ" style="padding:8px; width:250px;">
    <button type="submit" class="btn">Filter</button>
</form>

<table>
    <thead><tr><th>ID</th><th>Appt Code</th><th>Patient ID</th><th>Doctor</th><th>Appt Time</th><th>Status</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($appointments ?? [] as $appt): ?>
        <tr><td><?= e((string)$appt['id']) ?></td><td><?= e($appt['appointment_code']) ?></td><td><?= e((string)$appt['patient_id']) ?></td><td><?= e($appt['doctor_name']) ?></td><td><?= e($appt['appointment_time']) ?></td><td><?= e($appt['status']) ?></td><td><a href="/appointments/edit?id=<?= e((string)$appt['id']) ?>" class="btn">Edit</a></td></tr>
        <?php endforeach; ?>
        <?php if(empty($appointments)): ?><tr><td colspan="7" style="text-align:center;">No appointments found.</td></tr><?php endif; ?>
    </tbody>
</table>

<?php if (($totalPages ?? 1) > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="/appointments?page=<?= $i ?>&q=<?= urlencode($keyword ?? '') ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>