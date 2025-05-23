-- Create the quizzes table
CREATE TABLE IF NOT EXISTS `quizzes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `image` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample quizzes
INSERT INTO `quizzes` (`title`, `content`, `image`) VALUES
('Introduction to PHP', 'Test your knowledge of PHP basics with this comprehensive quiz.', '/assets/img/js-quiz.png'),
('JavaScript Fundamentals', 'Challenge yourself with core JavaScript concepts and problems.', '/assets/img/js-quiz.png'); 