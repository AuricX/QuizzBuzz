-- Drop tables if they exist (CAUTION: this deletes all data)
DROP TABLE IF EXISTS quizzes;
DROP TABLE IF EXISTS courses;

-- Create the courses table
CREATE TABLE IF NOT EXISTS `courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `instructor_name` VARCHAR(255) NOT NULL,
    `image` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the quizzes table
CREATE TABLE IF NOT EXISTS `quizzes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `image` VARCHAR(255),
    `course_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample courses
INSERT INTO `courses` (`title`, `description`, `instructor_name`, `image`) VALUES
('Web Development Fundamentals', 'Learn the basics of web development including HTML, CSS, and JavaScript.', 'John Smith', '/assets/img/web-dev.png'),
('Advanced PHP Programming', 'Master PHP programming with advanced concepts and best practices.', 'Sarah Johnson', '/assets/img/php-advanced.png'),
('Database Design', 'Learn database design principles and SQL implementation.', 'Michael Brown', '/assets/img/database.png');

-- Insert sample quizzes
INSERT INTO `quizzes` (`title`, `content`, `image`, `course_id`) VALUES
('Introduction to PHP', 'Test your knowledge of PHP basics with this comprehensive quiz.', '/assets/img/js-quiz.png', 2),
('JavaScript Fundamentals', 'Challenge yourself with core JavaScript concepts and problems.', '/assets/img/js-quiz.png', 1); 