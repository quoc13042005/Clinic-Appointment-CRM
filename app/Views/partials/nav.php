<div class="header">
    <h2>Clinic Appointment CRM</h2>
    <div class="nav">
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="/dashboard">Dashboard</a>
            <a href="/patients">Patients</a>
            <a href="/appointments">Appointments</a>
            <form action="/logout" method="POST" style="display:inline;">
                <button type="submit" style="background:none; border:none; color:white; cursor:pointer; margin-left:15px; font-size:16px;">Logout (<?= e($_SESSION['user_name'] ?? '') ?>)</button>
            </form>
        <?php else: ?>
            <a href="/login">Login</a>
        <?php endif; ?>
    </div>
</div>