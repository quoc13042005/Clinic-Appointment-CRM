<?php
class AppointmentController {
    public function __construct(private AppointmentService $service) {}
    public function index(): void { require_login(); $data = $this->service->getAppointmentList($_GET); render('appointments/index', ['title' => 'Appointment Management'] + $data); }
    public function create(): void { require_login(); render('appointments/create', ['title' => 'Create Appointment', 'errors' => []]); }
    public function store(): void {
        require_login();
        $result = $this->service->createAppointment($_POST);
        if (!$result['success']) { $_SESSION['old'] = $_POST; render('appointments/create', ['title' => 'Create', 'errors' => $result['errors']]); return; }
        clear_old(); flash('success', 'Thành công.'); redirect('/appointments'); 
    }
    public function edit(): void {
        require_login(); $id = (int)($_GET['id'] ?? 0); $appointment = $this->service->findById($id);
        if (!$appointment) { flash('error', 'Không tồn tại.'); redirect('/appointments'); }
        $_SESSION['old'] = $appointment; render('appointments/edit', ['title' => 'Edit', 'appointment' => $appointment, 'errors' => []]);
    }
    public function update(): void {
        require_login(); $id = (int)($_POST['id'] ?? 0); $result = $this->service->updateAppointment($id, $_POST);
        if (!$result['success']) { $_SESSION['old'] = $_POST; render('appointments/edit', ['title' => 'Edit', 'appointment' => ['id' => $id], 'errors' => $result['errors']]); return; }
        clear_old(); flash('success', 'Cập nhật thành công.'); redirect('/appointments');
    }
    public function delete(): void {
        require_login(); $id = (int)($_POST['id'] ?? 0); $result = $this->service->deleteAppointment($id);
        if($result['success']) flash('success', 'Xóa thành công.'); else flash('error', 'Lỗi.');
        redirect('/appointments');
    }
}