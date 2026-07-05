<?php
class AppointmentRepository {
    public function __construct(private PDO $db) {}
    public function countAll(string $keyword = ''): int {
        $sql = "SELECT COUNT(*) AS total FROM appointments"; $params = [];
        if ($keyword !== '') { $sql .= " WHERE appointment_code LIKE :keyword1 OR doctor_name LIKE :keyword2"; $params['keyword1'] = '%' . $keyword . '%'; $params['keyword2'] = '%' . $keyword . '%'; }
        $stmt = $this->db->prepare($sql); $stmt->execute($params); return (int) ($stmt->fetch()['total'] ?? 0);
    }
    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $dir): array {
        $sql = "SELECT id, appointment_code, patient_id, doctor_name, appointment_time, status, created_at FROM appointments"; $params = [];
        if ($keyword !== '') { $sql .= " WHERE appointment_code LIKE :keyword1 OR doctor_name LIKE :keyword2"; $params['keyword1'] = '%' . $keyword . '%'; $params['keyword2'] = '%' . $keyword . '%'; }
        $sql .= " ORDER BY {$sort} {$dir} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute(); return $stmt->fetchAll();
    }
    public function create(array $data): bool {
        $sql = "INSERT INTO appointments (appointment_code, patient_id, doctor_name, appointment_time, status) VALUES (:appointment_code, :patient_id, :doctor_name, :appointment_time, :status)";
        $stmt = $this->db->prepare($sql);
        try { return $stmt->execute($data); } 
        catch (PDOException $e) { 
            if (isset($e->errorInfo[1])) {
                $code = (int)$e->errorInfo[1];
                if ($code === 1062) throw new DuplicateRecordException('Duplicate appointment_code.');
                if ($code === 1452) throw new Exception('ID bệnh nhân không tồn tại.');
            }
            throw $e; 
        }
    }
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE id = :id LIMIT 1"); $stmt->execute(['id' => $id]); return $stmt->fetch() ?: null;
    }
    public function update(int $id, array $data): bool {
        $data['id'] = $id; $sql = "UPDATE appointments SET appointment_code=:appointment_code, patient_id=:patient_id, doctor_name=:doctor_name, appointment_time=:appointment_time, status=:status, updated_at=NOW() WHERE id=:id";
        try { return $this->db->prepare($sql)->execute($data); } 
        catch (PDOException $e) { 
            if (isset($e->errorInfo[1])) {
                $code = (int)$e->errorInfo[1];
                if ($code === 1062) throw new DuplicateRecordException('Duplicate appointment_code.');
                if ($code === 1452) throw new Exception('ID bệnh nhân không tồn tại.');
            }
            throw $e; 
        }
    }
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM appointments WHERE id = :id"); return $stmt->execute(['id' => $id]);
    }
}