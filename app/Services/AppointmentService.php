<?php
class AppointmentService {
    public function __construct(private AppointmentRepository $repo) {}
    public function getAppointmentList(array $query): array {
        $keyword = trim($query['q'] ?? ''); $page = max(1, (int)($query['page'] ?? 1));
        $sort = in_array($query['sort'] ?? '', ['id', 'appointment_code', 'appointment_time', 'created_at']) ? $query['sort'] : 'created_at';
        $dir = in_array(strtolower($query['direction'] ?? ''), ['asc', 'desc']) ? strtoupper($query['direction']) : 'DESC';
        $perPage = 10; $totalItems = $this->repo->countAll($keyword); $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $page = min($page, $totalPages); $offset = ($page - 1) * $perPage;
        return [ 'appointments' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $dir), 'keyword' => $keyword, 'page' => $page, 'totalPages' => $totalPages, 'totalItems' => $totalItems, 'sort' => $sort, 'direction' => $dir ];
    }
    private function validateAppointmentData(array $input): array {
        $errors = []; $appointment_code = trim($input['appointment_code'] ?? ''); $patient_id = trim($input['patient_id'] ?? '0'); $doctor_name = trim($input['doctor_name'] ?? ''); $appointment_time = trim($input['appointment_time'] ?? ''); $status = trim($input['status'] ?? 'scheduled');
        if ($appointment_code === '') $errors['appointment_code'] = 'Mã lịch hẹn không để trống.';
        if (!is_numeric($patient_id) || $patient_id <= 0) $errors['patient_id'] = 'ID bệnh nhân không hợp lệ.';
        if ($doctor_name === '') $errors['doctor_name'] = 'Tên bác sĩ không để trống.';
        if ($appointment_time === '') $errors['appointment_time'] = 'Thời gian hẹn không để trống.';
        if (!in_array($status, ['scheduled','completed','cancelled'], true)) $errors['status'] = 'Trạng thái không hợp lệ.';
        return compact('errors') + ['values' => compact('appointment_code','patient_id','doctor_name','appointment_time','status')];
    }
    public function createAppointment(array $input): array {
        $validation = $this->validateAppointmentData($input); if (!empty($validation['errors'])) return ['success' => false, 'errors' => $validation['errors']];
        try { $this->repo->create($validation['values']); return ['success' => true, 'errors' => []]; } 
        catch (DuplicateRecordException $e) { return ['success' => false, 'errors' => ['appointment_code' => 'Mã lịch hẹn này đã tồn tại.']]; }
        catch (Exception $e) { return ['success' => false, 'errors' => ['patient_id' => $e->getMessage()]]; }
    }
    public function findById(int $id): ?array { return $this->repo->findById($id); }
    public function updateAppointment(int $id, array $input): array {
        if (!$this->repo->findById($id)) return ['success' => false, 'errors' => ['general' => 'Lịch hẹn không tồn tại.']];
        $validation = $this->validateAppointmentData($input); if (!empty($validation['errors'])) return ['success' => false, 'errors' => $validation['errors']];
        try { $this->repo->update($id, $validation['values']); return ['success' => true, 'errors' => []]; } 
        catch (DuplicateRecordException $e) { return ['success' => false, 'errors' => ['appointment_code' => 'Mã lịch hẹn này đã tồn tại.']]; }
        catch (Exception $e) { return ['success' => false, 'errors' => ['patient_id' => $e->getMessage()]]; }
    }
    public function deleteAppointment(int $id): array {
        if ($id <= 0) return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        $this->repo->delete($id); return ['success' => true, 'errors' => []];
    }
}