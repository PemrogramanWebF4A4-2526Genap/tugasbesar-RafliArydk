<?php
class ScheduleModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getByProvider($providerId) {
        $stmt = $this->pdo->prepare('SELECT * FROM provider_schedules WHERE provider_id = ? ORDER BY day_of_week, start_time');
        $stmt->execute([(int) $providerId]);
        return $stmt->fetchAll();
    }

    public function create($providerId, $dayOfWeek, $startTime, $endTime, $isAvailable = 1) {
        $stmt = $this->pdo->prepare('
            INSERT INTO provider_schedules (provider_id, day_of_week, start_time, end_time, is_available)
            VALUES (?, ?, ?, ?, ?)
        ');
        return $stmt->execute([(int) $providerId, (int) $dayOfWeek, $startTime, $endTime, (int) $isAvailable]);
    }

    public function delete($id, $providerId) {
        $stmt = $this->pdo->prepare('DELETE FROM provider_schedules WHERE id = ? AND provider_id = ?');
        return $stmt->execute([(int) $id, (int) $providerId]);
    }
}
