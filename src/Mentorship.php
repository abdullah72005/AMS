<?php

class Mentorship {
    private $mentorshipId;
    private $mentorId;
    private $studentsId = [];
    private $description;
    private $date;
    private $dbCnx;

    public function __construct(int $mentorshipId, int $mentorId, array $studentsId, string $description, string $date) {
        $this->dbCnx = require('db.php');
        $this->mentorshipId = $mentorshipId;

        // Check if mentorship exists
        $stmt = $this->dbCnx->prepare("SELECT * FROM Mentorship WHERE mentorship_id = ?");
        $stmt->execute([$this->mentorshipId]);
        
        if ($stmt->fetch()) {
            // Load existing mentorship data
            $this->refreshFromDB();
        } else {
            // Create new mentorship
            $this->mentorId = $mentorId;
            $this->description = $description;
            $this->date = DateTime::createFromFormat('d-m-Y', $date);
            if (!$this->date) {
                throw new Exception("Invalid date format. Use d-m-Y.");
            }

            // Verify mentor exists
            $stmt = $this->dbCnx->prepare("SELECT user_id FROM Alumni WHERE user_id = ?");
            $stmt->execute([$this->mentorId]);
            if (!$stmt->fetch()) {
                throw new Exception("Mentor not found.");
            }

            // Insert into Mentorship table
            $stmt = $this->dbCnx->prepare("INSERT INTO Mentorship (mentorship_id, mentor_id, description, date) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $this->mentorshipId,
                $this->mentorId,
                $this->description,
                $this->date->format('Y-m-d')
            ]);

            // Add students
            foreach ($studentsId as $studentId) {
                $this->addStudent($studentId);
            }
        }
    }

    private function refreshFromDB(): void {
        $stmt = $this->dbCnx->prepare("SELECT mentor_id, description, date FROM Mentorship WHERE mentorship_id = ?");
        $stmt->execute([$this->mentorshipId]);
        $data = $stmt->fetch();
        
        $this->mentorId = $data['mentor_id'];
        $this->description = $data['description'];
        $this->date = DateTime::createFromFormat('Y-m-d', $data['date']);
        $this->studentsId = $this->fetchStudentsFromDB();
    }

    private function fetchStudentsFromDB(): array {
        $stmt = $this->dbCnx->prepare("SELECT student_id FROM Student_Mentor WHERE mentor_id = ?");
        $stmt->execute([$this->mentorId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getMentor(): string {
        $stmt = $this->dbCnx->prepare("SELECT mentor_id FROM Mentorship WHERE mentorship_id = ?");
        $stmt->execute([$this->mentorshipId]);
        return (string)$stmt->fetchColumn();
    }

    public function getStudents(): string {
        return implode(', ', $this->fetchStudentsFromDB());
    }

    public function getDescription(): string {
        $stmt = $this->dbCnx->prepare("SELECT description FROM Mentorship WHERE mentorship_id = ?");
        $stmt->execute([$this->mentorshipId]);
        return $stmt->fetchColumn();
    }

    public function getDate(): string {
        $stmt = $this->dbCnx->prepare("SELECT date FROM Mentorship WHERE mentorship_id = ?");
        $stmt->execute([$this->mentorshipId]);
        $dateStr = $stmt->fetchColumn();
        return date('d-m-Y', strtotime($dateStr));
    }

    public function addStudent(int $newStudentId): void {
        // Verify student exists
        $stmt = $this->dbCnx->prepare("SELECT user_id FROM Student WHERE user_id = ?");
        $stmt->execute([$newStudentId]);
        if (!$stmt->fetch()) {
            throw new Exception("Student not found.");
        }

        // Add relationship
        $stmt = $this->dbCnx->prepare("INSERT IGNORE INTO Student_Mentor (student_id, mentor_id) VALUES (?, ?)");
        $stmt->execute([$newStudentId, $this->mentorId]);
        $this->studentsId = $this->fetchStudentsFromDB();
    }

    public function removeStudent(int $studentId): void {
        $stmt = $this->dbCnx->prepare("DELETE FROM Student_Mentor WHERE student_id = ? AND mentor_id = ?");
        $stmt->execute([$studentId, $this->mentorId]);
        $this->studentsId = $this->fetchStudentsFromDB();
    }
}
?>