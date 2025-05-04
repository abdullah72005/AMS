-- 1. User
CREATE TABLE IF NOT EXISTS `User` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(500) NOT NULL,
  `role` ENUM('Alumni', 'Student', 'Admin', 'FacultyStaff') NOT NULL
) ENGINE=InnoDB;

-- 2. Alumni
CREATE TABLE IF NOT EXISTS `Alumni` (
  `userId` INT PRIMARY KEY,
  `mentor` BOOLEAN NOT NULL DEFAULT FALSE,
  `verified` BOOLEAN NOT NULL DEFAULT FALSE,
  `graduationDate` DATE,
  `major` VARCHAR(100),
  CONSTRAINT `fk_alumni_user`
    FOREIGN KEY (`userId`) REFERENCES `User`(`user_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. Student
CREATE TABLE IF NOT EXISTS `Student` (
  `userId` INT PRIMARY KEY,
  `major` VARCHAR(100),
  CONSTRAINT `fk_student_user`
    FOREIGN KEY (`userId`) REFERENCES `User`(`user_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. FacultyStaff
CREATE TABLE IF NOT EXISTS `FacultyStaff` (
  `user_id` INT PRIMARY KEY,
  CONSTRAINT `fk_facultystaff_user`
    FOREIGN KEY (`user_id`) REFERENCES `User`(`user_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 5. Admin
CREATE TABLE IF NOT EXISTS `Admin` (
  `user_id` INT PRIMARY KEY,
  CONSTRAINT `fk_admin_user`
    FOREIGN KEY (`user_id`) REFERENCES `User`(`user_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 6. Mentorship
CREATE TABLE IF NOT EXISTS `Mentorship` (
  `mentorship_id` INT AUTO_INCREMENT PRIMARY KEY,
  `mentor_id` INT NOT NULL,
  `description` TEXT NOT NULL,
  `date` DATE,
  CONSTRAINT `fk_mentorship_mentor`
    FOREIGN KEY (`mentor_id`) REFERENCES `Alumni`(`userId`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 7. Event
CREATE TABLE IF NOT EXISTS `Event` (
  `eventId` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `date` DATE NOT NULL,
  `creatorId` INT,
  CONSTRAINT `fk_event_creator`
    FOREIGN KEY (`creatorId`) REFERENCES `FacultyStaff`(`user_id`)
      ON DELETE SET NULL
) ENGINE=InnoDB;

-- 8. EventParticipant
CREATE TABLE IF NOT EXISTS `EventParticipant` (
  `event_id` INT NOT NULL,
  `participant_id` INT NOT NULL,
  `RSVP_status` BOOLEAN DEFAULT FALSE,
  PRIMARY KEY (`event_id`, `participant_id`),
  CONSTRAINT `fk_ep_event`
    FOREIGN KEY (`event_id`) REFERENCES `Event`(`eventId`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT `fk_ep_participant`
    FOREIGN KEY (`participant_id`) REFERENCES `Alumni`(`userId`)
      ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. Donation
CREATE TABLE IF NOT EXISTS `Donation` (
  `donation_id` INT AUTO_INCREMENT PRIMARY KEY,
  `amount` DECIMAL(10,2) NOT NULL,
  `cause` VARCHAR(255),
  `date` DATE,
  `donor_id` INT NOT NULL,
  CONSTRAINT `chk_donation_amount`
    CHECK (`amount` > 0),
  CONSTRAINT `fk_donation_donor`
    FOREIGN KEY (`donor_id`) REFERENCES `Alumni`(`userId`)
      ON DELETE SET NULL
) ENGINE=InnoDB;

-- 10. Newsletter
CREATE TABLE IF NOT EXISTS `Newsletter` (
  `newsletter_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `body` TEXT NOT NULL,
  `creatorId` INT NOT NULL,
  `publishedState` BOOLEAN DEFAULT FALSE,
  CONSTRAINT `fk_newsletter_creator`
    FOREIGN KEY (`creatorId`) REFERENCES `FacultyStaff`(`user_id`)
      ON DELETE SET NULL
) ENGINE=InnoDB;

-- 11. Notification
CREATE TABLE IF NOT EXISTS `Notification` (
  `user_id` INT PRIMARY KEY,
  `notification` TEXT NOT NULL,
  `read` BOOLEAN DEFAULT FALSE,
  CONSTRAINT `fk_notification_user`
    FOREIGN KEY (`user_id`) REFERENCES `User`(`user_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 12. Student_Mentor
CREATE TABLE IF NOT EXISTS `Student_Mentor` (
  `student_id` INT NOT NULL,
  `mentor_id` INT NOT NULL,
  PRIMARY KEY (`student_id`, `mentor_id`),
  CONSTRAINT `fk_sm_student`
    FOREIGN KEY (`student_id`) REFERENCES `Student`(`userId`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT `fk_sm_mentor`
    FOREIGN KEY (`mentor_id`) REFERENCES `Alumni`(`userId`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE=InnoDB;