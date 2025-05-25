-- quiz_app_schema.sql

-- 0. Drop existing tables (in order of dependencies)
DROP TABLE IF EXISTS `student_answer`;
DROP TABLE IF EXISTS `quiz_attempt`;
DROP TABLE IF EXISTS `payment`;
DROP TABLE IF EXISTS `enrollment`;
DROP TABLE IF EXISTS `choice`;
DROP TABLE IF EXISTS `question`;
DROP TABLE IF EXISTS `quiz`;
DROP TABLE IF EXISTS `course`;
DROP TABLE IF EXISTS `category`;
DROP TABLE IF EXISTS `student`;
DROP TABLE IF EXISTS `teacher`;

-- 1. Teachers
CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_id`    INT AUTO_INCREMENT PRIMARY KEY,
  `name`          VARCHAR(100)        NOT NULL,
  `email`         VARCHAR(150)        NOT NULL UNIQUE,
  `password_hash` VARCHAR(255)        NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Students
CREATE TABLE IF NOT EXISTS `student` (
  `student_id`    INT AUTO_INCREMENT PRIMARY KEY,
  `name`          VARCHAR(100)        NOT NULL,
  `email`         VARCHAR(150)        NOT NULL UNIQUE,
  `password_hash` VARCHAR(255)        NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Categories
CREATE TABLE IF NOT EXISTS `category` (
  `category_id`   INT AUTO_INCREMENT PRIMARY KEY,
  `name`          VARCHAR(100)        NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Courses
CREATE TABLE IF NOT EXISTS `course` (
  `course_id`     INT AUTO_INCREMENT PRIMARY KEY,
  `title`         VARCHAR(200)        NOT NULL,
  `description`   TEXT,
  `level`         ENUM(
                     'Very Easy',
                     'Easy',
                     'Normal',
                     'Hard',
                     'Very Hard'
                   ) NOT NULL DEFAULT 'Normal',
  `teacher_id`    INT                 NOT NULL,
  `category_id`   INT                 NOT NULL,
  FOREIGN KEY (`teacher_id`)  REFERENCES `teacher`(`teacher_id`),
  FOREIGN KEY (`category_id`) REFERENCES `category`(`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Quizzes
CREATE TABLE IF NOT EXISTS `quiz` (
  `quiz_id`       INT AUTO_INCREMENT PRIMARY KEY,
  `course_id`     INT                 NOT NULL,
  `title`         VARCHAR(200)        NOT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `course`(`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Questions
CREATE TABLE IF NOT EXISTS `question` (
  `question_id`   INT AUTO_INCREMENT PRIMARY KEY,
  `quiz_id`       INT                 NOT NULL,
  `body`          TEXT                NOT NULL,
  FOREIGN KEY (`quiz_id`) REFERENCES `quiz`(`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Choices
CREATE TABLE IF NOT EXISTS `choice` (
  `choice_id`     INT AUTO_INCREMENT PRIMARY KEY,
  `question_id`   INT                 NOT NULL,
  `text`          VARCHAR(255)        NOT NULL,
  `is_correct`    BOOLEAN             NOT NULL DEFAULT FALSE,
  FOREIGN KEY (`question_id`) REFERENCES `question`(`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Enrollments (student ⇄ course, holds final grade)
CREATE TABLE IF NOT EXISTS `enrollment` (
  `student_id`    INT                 NOT NULL,
  `course_id`     INT                 NOT NULL,
  `final_grade`   DECIMAL(5,2),
  PRIMARY KEY (`student_id`,`course_id`),
  FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`),
  FOREIGN KEY (`course_id`)  REFERENCES `course`(`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Payments (student pays for course)
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id`    INT AUTO_INCREMENT PRIMARY KEY,
  `student_id`    INT                 NOT NULL,
  `course_id`     INT                 NOT NULL,
  `amount`        DECIMAL(10,2)       NOT NULL,
  FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`),
  FOREIGN KEY (`course_id`)  REFERENCES `course`(`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Quiz Attempts (student’s score on a quiz)
CREATE TABLE IF NOT EXISTS `quiz_attempt` (
  `attempt_id`    INT AUTO_INCREMENT PRIMARY KEY,
  `student_id`    INT                 NOT NULL,
  `quiz_id`       INT                 NOT NULL,
  `score`         DECIMAL(5,2),
  FOREIGN KEY (`student_id`) REFERENCES `student`(`student_id`),
  FOREIGN KEY (`quiz_id`)    REFERENCES `quiz`(`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Student Answers (if you choose to track individual responses)
/* Uncomment if needed:
CREATE TABLE IF NOT EXISTS `student_answer` (
  `answer_id`    INT AUTO_INCREMENT PRIMARY KEY,
  `attempt_id`   INT                 NOT NULL,
  `question_id`  INT                 NOT NULL,
  `choice_id`    INT,
  `answer_text`  TEXT,
  FOREIGN KEY (`attempt_id`)   REFERENCES `quiz_attempt`(`attempt_id`),
  FOREIGN KEY (`question_id`)  REFERENCES `question`(`question_id`),
  FOREIGN KEY (`choice_id`)    REFERENCES `choice`(`choice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
*/