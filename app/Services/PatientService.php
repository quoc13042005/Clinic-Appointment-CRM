<?php
class PatientService {
    public function __construct(private PatientRepository $repo) {}
    public function getPatientList(array $query): array {
        $keyword = trim($query['q'] ?? ''); $page = max(1, (int)($query['page'] ?? 1));
        $sort = in_array($query['sort'] ?? '', ['id', 'name', 'id_card', 'created_at']) ? $query['sort'] : 'created_at';
        $dir = in_array(strtolower($query['direction'] ?? ''), ['asc', 'desc']) ? strtoupper($query['direction']) : 'DESC';
        $perPage = 10; $totalItems = $this->repo->countAll($keyword); $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $page = min($page, $totalPages); $offset = ($page - 1) * $perPage;
        return [ 'patients' => $this->repo->getPaginated($keyword, $perPage, $offset, $sort, $dir), 'keyword' => $keyword, 'page' => $page, 'totalPages' => $totalPages, 'totalItems' => $totalItems, 'sort' => $sort, 'direction' => $dir ];
    }
    private function validatePatientData(array $input): array {
        $errors = []; $name = trim($input['name'] ?? ''); $id_card = trim($input['id_card'] ?? ''); $phone = trim($input['phone'] ?? ''); $date_of_birth = trim($input['date_of_birth'] ?? ''); $address = trim($input['address'] ?? '');
        if ($name === '') $errors['name'] = 'Tên không được để trống.';
        if ($id_card === '') $errors['id_card'] = 'CMND không được để trống.';
        if ($date_of_birth === '') $errors['date_of_birth'] = 'Ngày sinh không được để trống.';
        return compact('errors') + ['values' => compact('name','id_card','phone','date_of_birth','address')];
    }
    public function createPatient(array $input): array {
        $validation = $this->validatePatientData($input); if (!empty($validation['errors'])) return ['success' => false, 'errors' => $validation['errors']];
        try { $this->repo->create($validation['values']); return ['success' => true, 'errors' => []]; } 
        catch (DuplicateRecordException $e) { return ['success' => false, 'errors' => ['id_card' => 'CMND này đã tồn tại.']]; }
    }
    public function findById(int $id): ?array { return $this->repo->findById($id); }
    public function updatePatient(int $id, array $input): array {
        if (!$this->repo->findById($id)) return ['success' => false, 'errors' => ['general' => 'Bệnh nhân không tồn tại.']];
        $validation = $this->validatePatientData($input); if (!empty($validation['errors'])) return ['success' => false, 'errors' => $validation['errors']];
        try { $this->repo->update($id, $validation['values']); return ['success' => true, 'errors' => []]; } 
        catch (DuplicateRecordException $e) { return ['success' => false, 'errors' => ['id_card' => 'CMND này đã tồn tại.']]; }
    }
    public function deletePatient(int $id): array {
        if ($id <= 0) return ['success' => false, 'errors' => ['general' => 'ID không hợp lệ.']];
        $this->repo->delete($id); return ['success' => true, 'errors' => []];
    }
}