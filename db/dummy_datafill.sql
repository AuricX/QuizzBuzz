-- dummy_data.sql
-- Inserts sample data into the quiz-taking app schema

-- 1. Teachers
INSERT INTO `teacher` (`teacher_id`, `name`, `email`, `password_hash`) VALUES
  (1, 'Alice Smith',   'alice.smith@example.com',   'hash_alice'),
  (2, 'Bob Johnson',   'bob.johnson@example.com',   'hash_bob'),
  (3, 'Carol Davis',   'carol.davis@example.com',   'hash_carol'),
  (4, 'David Wilson',  'david.wilson@example.com',  'hash_david'),
  (5, 'Emma Thompson', 'emma.thompson@example.com', 'hash_emma');

-- 2. Students
INSERT INTO `student` (`student_id`, `name`, `email`, `password_hash`) VALUES
  (1, 'Charlie Brown', 'charlie.brown@example.com', 'hash_charlie'),
  (2, 'Dana White',    'dana.white@example.com',    'hash_dana'),
  (3, 'Evan Lee',      'evan.lee@example.com',      'hash_evan'),
  (4, 'Frank Miller',  'frank.miller@example.com',  'hash_frank'),
  (5, 'Grace Chen',    'grace.chen@example.com',    'hash_grace'),
  (6, 'Henry Taylor',  'henry.taylor@example.com',  'hash_henry'),
  (7, 'Ivy Martinez',  'ivy.martinez@example.com',  'hash_ivy');

-- 3. Categories
INSERT INTO `category` (`category_id`, `name`) VALUES
  (1, 'Web Development'),
  (2, 'Database Design'),
  (3, 'AI & Data Science'),
  (4, 'Mobile Development'),
  (5, 'Cloud Computing'),
  (6, 'Cybersecurity');

-- 4. Courses
INSERT INTO `course` (`course_id`, `title`,                     `description`,                       `level`,     `teacher_id`, `category_id`) VALUES
  (1, 'PHP for Beginners',        'Learn PHP from scratch',           'Very Easy', 1,            1),
  (2, 'Advanced MySQL',           'Deep dive into MySQL optimization','Hard',      2,            2),
  (3, 'Intro to Machine Learning','Basics of ML algorithms',         'Normal',    1,            3),
  (4, 'React Native Basics',      'Build mobile apps with React Native', 'Normal', 3,            4),
  (5, 'AWS Fundamentals',         'Introduction to Amazon Web Services', 'Hard',    4,            5),
  (6, 'Network Security',         'Essential cybersecurity practices',   'Hard',    5,            6),
  (7, 'Advanced PHP',             'Master PHP frameworks and patterns',  'Hard',    1,            1);

-- 5. Quizzes
INSERT INTO `quiz` (`quiz_id`, `course_id`, `title`) VALUES
  (1, 1, 'PHP Syntax Quiz'),
  (2, 1, 'Working with Forms'),
  (3, 2, 'Indexing & Joins'),
  (4, 3, 'Supervised Learning'),
  (5, 4, 'React Native Components'),
  (6, 4, 'State Management'),
  (7, 5, 'EC2 & S3 Basics'),
  (8, 6, 'Network Protocols'),
  (9, 7, 'PHP Design Patterns');

-- 6. Questions
INSERT INTO `question` (`question_id`, `quiz_id`, `body`) VALUES
  (1, 1, 'Which keyword outputs text in PHP?'),
  (2, 1, 'What is the file extension for PHP scripts?'),
  (3, 2, 'Name the HTTP method used when submitting a form.'),
  (4, 3, 'Which MySQL clause combines rows from two tables?'),
  (5, 4, 'Is Linear Regression supervised or unsupervised?'),
  (6, 5, 'What is the main component type in React Native?'),
  (7, 5, 'Which hook is used for state management?'),
  (8, 7, 'What is the main difference between EC2 and S3?'),
  (9, 8, 'Which protocol is used for secure web browsing?'),
  (10, 9, 'What is the Singleton pattern used for?');

-- 7. Choices
INSERT INTO `choice` (`choice_id`, `question_id`, `text`,                           `is_correct`) VALUES
  ( 1, 1, 'echo',        TRUE),
  ( 2, 1, 'print',       FALSE),
  ( 3, 1, 'printf',      FALSE),
  ( 4, 1, 'write',       FALSE),

  ( 5, 2, '.php',        TRUE),
  ( 6, 2, '.html',       FALSE),
  ( 7, 2, '.js',         FALSE),
  ( 8, 2, '.css',        FALSE),

  ( 9, 3, 'GET',         FALSE),
  (10, 3, 'POST',        TRUE),
  (11, 3, 'PUT',         FALSE),
  (12, 3, 'DELETE',      FALSE),

  (13, 4, 'WHERE',       FALSE),
  (14, 4, 'JOIN',        TRUE),
  (15, 4, 'GROUP BY',    FALSE),
  (16, 4, 'ORDER BY',    FALSE),

  (17, 5, 'Supervised',  TRUE),
  (18, 5, 'Unsupervised',FALSE),
  (19, 5, 'Reinforcement',FALSE),
  (20, 5, 'None of the above', FALSE),

  (21, 6, 'View',           TRUE),
  (22, 6, 'Div',            FALSE),
  (23, 6, 'Container',      FALSE),
  (24, 6, 'Box',            FALSE),

  (25, 7, 'useState',       TRUE),
  (26, 7, 'useEffect',      FALSE),
  (27, 7, 'useContext',     FALSE),
  (28, 7, 'useReducer',     FALSE),

  (29, 8, 'EC2 is compute, S3 is storage', TRUE),
  (30, 8, 'EC2 is storage, S3 is compute', FALSE),
  (31, 8, 'Both are compute services',      FALSE),
  (32, 8, 'Both are storage services',      FALSE),

  (33, 9, 'HTTPS',          TRUE),
  (34, 9, 'HTTP',           FALSE),
  (35, 9, 'FTP',            FALSE),
  (36, 9, 'SMTP',           FALSE),

  (37, 10, 'Single instance creation', TRUE),
  (38, 10, 'Multiple instances',       FALSE),
  (39, 10, 'Instance destruction',     FALSE),
  (40, 10, 'Instance cloning',         FALSE);

-- 8. Enrollments
INSERT INTO `enrollment` (`student_id`, `course_id`, `final_grade`) VALUES
  (1, 1, 88.50),
  (1, 2, 72.00),
  (2, 1, 95.00),
  (3, 3, 81.25),
  (4, 4, 92.00),
  (5, 4, 88.75),
  (6, 5, 85.50),
  (7, 6, 90.25),
  (4, 7, 87.00),
  (5, 5, 91.50);

-- 9. Payments
INSERT INTO `payment` (`payment_id`, `student_id`, `course_id`, `amount`) VALUES
  (1, 1, 1, 49.99),
  (2, 1, 2, 79.99),
  (3, 2, 1, 49.99),
  (4, 3, 3, 59.99),
  (5, 4, 4, 69.99),
  (6, 5, 4, 69.99),
  (7, 6, 5, 89.99),
  (8, 7, 6, 79.99),
  (9, 4, 7, 59.99),
  (10, 5, 5, 89.99);

-- 10. Quiz Attempts
INSERT INTO `quiz_attempt` (`attempt_id`, `student_id`, `quiz_id`, `score`) VALUES
  (1, 1, 1, 85.00),
  (2, 1, 2, 90.00),
  (3, 2, 1, 95.00),
  (4, 3, 4, 80.00),
  (5, 4, 5, 92.00),
  (6, 5, 5, 88.00),
  (7, 6, 7, 85.00),
  (8, 7, 8, 90.00),
  (9, 4, 9, 87.00),
  (10, 5, 7, 91.00);