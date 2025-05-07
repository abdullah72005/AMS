<?php

class Mentorship {
    private $mentorshipId;
    private $mentorId;
    private $studentsId = []; // Assuming multiple students
    private $description;
    private $date;
    private $dbCnx;

    public function __construct(int $mentorshipId, int $mentorId, array $studentsId, string $description, string $date) {
        $this->mentorshipId = $mentorshipId;
        $this->mentorId = $mentorId;
        $this->studentsId = $studentsId;
        $this->description = $description;
        $this->date = new DateTime($date);
        $this->dbCnx = require('db.php'); // Initialize database connection
    }

    public function getMentor(): string {
        return (string)$this->mentorId;
    }

    public function getStudents(): string {
        return implode(', ', $this->studentsId);
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDate(): string {
        return $this->date->format('Y-m-d');
    }

    public function addStudent(int $newStudentId): void {
        if (!in_array($newStudentId, $this->studentsId)) {
            $this->studentsId[] = $newStudentId;
        }
    }

    public function removeStudent(int $studentId): void {
        $index = array_search($studentId, $this->studentsId);
        if ($index !== false) {
            unset($this->studentsId[$index]);
            $this->studentsId = array_values($this->studentsId); // Reindex array
        }
    }
}
?>
