<?php

class Mentorship {
    private $mentorshipId;
    private $mentorId;
    private $studentsIds = [];
    private $description = '';
    private $date;
    private $dbCnx;

    public function __construct(int $mentorId) {
        $this->dbCnx = require('db.php');
        $this->mentorId = $mentorId;

        // Check for existing mentorship
        $stmt = $this->dbCnx->prepare("SELECT * FROM Mentorship WHERE mentor_id = ?");
        $stmt->execute([$this->mentorId]);

        if ($data = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            // Load existing mentorship
            $this->mentorshipId = $data['mentorship_id'];
            $this->description = $data['description'];
            $this->date = DateTime::createFromFormat('d-m-y', $data['date']);
            $this->studentsIds = $this->getStudentsIds();
        } 
        else 
        {
            $this->date = new DateTime();
            $stmt = $this->dbCnx->prepare("INSERT INTO Mentorship (mentor_id, description, date) VALUES (?, ?, ?)");
            $stmt->execute([
                $this->mentorId,
                $this->description,
                $this->date->format('d-m-y')
            ]);
            $this->mentorshipId = $this->dbCnx->lastInsertId();
            $stmt = $this->dbCnx->prepare("SELECT user_id FROM Alumni WHERE user_id = ? AND verified = 1 AND mentor = 1");
            $stmt->execute([$this->mentorId]);
            if (!$stmt->fetch()) {
                throw new Exception("Alumni is not verified or not registered as mentor");
            }
        }
    }

    public function addStudent($studentId)
    {
        // Verify student exists
        $stmt = $this->dbCnx->prepare("SELECT user_id FROM Student WHERE user_id = ?");
        $stmt->execute([$studentId]);
        if (!$stmt->fetch()) 
        {
            throw new Exception("Student not found");
        }

        // Add to Student_Mentor table
        $stmt = $this->dbCnx->prepare("INSERT INTO Student_Mentor (student_id, mentor_id) VALUES (?, ?)");
        $stmt->execute([$studentId, $this->mentorId]);
        $this->studentsIds = $this->updateStudentsIds();
    }

    public function removeStudent(int $studentId): void {
        $stmt = $this->dbCnx->prepare("DELETE FROM Student_Mentor WHERE student_id = ? AND mentor_id = ?");
        $stmt->execute([$studentId, $this->mentorId]);
        $this->studentsIds = $this->updateStudentsIds();
    }

    private function updateStudentsIds()
    {
        $stmt = $this->dbCnx->prepare("SELECT student_id FROM Student_Mentor WHERE mentor_id = ?");
        $stmt->execute([$this->mentorId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function getMentorshipId()
    {
        return $this->mentorshipId;
    }

    public function getMentorId()
    {
        return $this->mentorId;
    }

    public function getStudentsIds() 
    {
        return $this->studentsIds = $this->updateStudentsIds();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDate(): string {
        return $this->date->format('d-m-y');
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
        $stmt = $this->dbCnx->prepare("UPDATE Mentorship SET description = ? WHERE mentorship_id = ?");
        $stmt->execute([$description, $this->mentorshipId]);
    }

    public function setDate()
    {
        $this->date = new DateTime();
        if (!$this->date) 
        {
            throw new Exception("Invalid date format. Use d-m-y.");
        }
        
        $stmt = $this->dbCnx->prepare("UPDATE Mentorship SET date = ? WHERE mentorship_id = ?");
        $stmt->execute([
            $this->date->format('d-m-y'),
            $this->mentorshipId
        ]);
    }
}