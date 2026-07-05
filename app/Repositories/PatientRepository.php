<?php
class PatientRepository {
    public function __construct(private PDO $db) {}
    public function countAll(string $keyword = ''): int {
        $sql = "SELECT COUNT(*) AS total FROM patients"; $params = [];
        if ($keyword !== '') { $sql .= " WHERE name LIKE :keyword1 OR id_card LIKE :keyword2 OR phone LIKE :keyword3"; $params['keyword1'] = '%' . $keyword . '%'; $params['keyword2'] = '%' . $keyword . '%'; $params['keyword3'] = '%' . $keyword . '%'; }
        $stmt = $this->db->prepare($sql); $stmt->execute($params); return (int) ($stmt->fetch()['total'] ?? 0);
    }
    public function getPaginated(string $keyword, int $limit, int $offset, string $sort, string $dir): array {
        $sql = "SELECT id, name, id_card, phone, date_of_birth, created_at FROM patients"; $params = [];
        if ($keyword !== '') { $sql .= " WHERE name LIKE :keyword1 OR id_card LIKE :keyword2 OR phone LIKE :keyword3"; $params['keyword1'] = '%' . $keyword . '%'; $params['keyword2'] = '%' . $keyword . '%'; $params['keyword3'] = '%' . $keyword . '%'; }
        $sql .= " ORDER BY {$sort} {$dir} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute(); return $stmt->fetchAll();
    }
    public function create(array $data): bool {
        $sql = "INSERT INTO patients (name, id_card, phone, date_of_birth, address) VALUES (:name, :id_card, :phone, :date_of_birth, :address)";
        $stmt = $this->db->prepare($sql);
        try { return $stmt->execute($data); } 
        catch (PDOException $e) { if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) throw new DuplicateRecordException('Duplicate id_card.'); throw $e; }
    }
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM patients WHERE id = :id LIMIT 1"); $stmt->execute(['id' => $id]); return $stmt->fetch() ?: null;
    }
    public function update(int $id, array $data): bool {
        $data['id'] = $id; $sql = "UPDATE patients SET name=:name, id_card=:id_card, phone=:phone, date_of_birth=:date_of_birth, address=:address, updated_at=NOW() WHERE id=:id";
        try { return $this->db->prepare($sql)->execute($data); } 
        catch (PDOException $e) { if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) throw new DuplicateRecordException('Duplicate id_card.'); throw $e; }
    }
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM patients WHERE id = :id"); return $stmt->execute(['id' => $id]);
    }
}