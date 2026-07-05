<?php
class PatientController {
    public function __construct(private PatientService $service) {}
    public function index(): void { require_login(); $data = $this->service->getPatientList($_GET); render('patients/index', ['title' => 'Patient Management'] + $data); }
    public function create(): void { require_login(); render('patients/create', ['title' => 'Create Patient', 'errors' => []]); }
    public function store(): void {
        require_login();
        if (!empty($_POST['website_hp'])) { flash('error', 'Spam detected.'); redirect('/patients/create'); }
        if (isset($_SESSION['last_submit']) && (time() - $_SESSION['last_submit']) < 5) { flash('error', 'Vui lòng đợi 5s.'); $_SESSION['old'] = $_POST; redirect('/patients/create'); }
        $_SESSION['last_submit'] = time();
        $result = $this->service->createPatient($_POST);
        if (!$result['success']) { $_SESSION['old'] = $_POST; render('patients/create', ['title' => 'Create', 'errors' => $result['errors']]); return; }
        clear_old(); flash('success', 'Thành công.'); redirect('/patients'); 
    }
    public function edit(): void {
        require_login(); $id = (int)($_GET['id'] ?? 0); $patient = $this->service->findById($id);
        if (!$patient) { flash('error', 'Không tồn tại.'); redirect('/patients'); }
        $_SESSION['old'] = $patient; render('patients/edit', ['title' => 'Edit', 'patient' => $patient, 'errors' => []]);
    }
    public function update(): void {
        require_login(); $id = (int)($_POST['id'] ?? 0); $result = $this->service->updatePatient($id, $_POST);
        if (!$result['success']) { $_SESSION['old'] = $_POST; render('patients/edit', ['title' => 'Edit', 'patient' => ['id' => $id], 'errors' => $result['errors']]); return; }
        clear_old(); flash('success', 'Cập nhật thành công.'); redirect('/patients');
    }
    public function delete(): void {
        require_login(); $id = (int)($_POST['id'] ?? 0); $result = $this->service->deletePatient($id);
        if($result['success']) flash('success', 'Xóa thành công.'); else flash('error', 'Lỗi.');
        redirect('/patients');
    }
}