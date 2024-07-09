CREATE TABLE users (
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

admin - 


Permissions

1. To create other user accounts such as professor and moderator.
2. To view all user accounts 
3. Filter solitations for acceptance and rejections
4. To view all the solicitation requests    
5. Promote professor to coordinator role
6. Validate professors signup and accept request.
7. Reject professors signup and reject request.
8. Delete a user account.


-- view the list of users, 
-- create a user
-- assign the role of coordinators, 
-- create the professors
-- validate the professors
-- view all the professor request
-- accept the professor request
-- have all permission for crud


filter

reject solicitate
approve solicitate



each faculty has a coordinator,


professors and moderator accounts,
delete a user



coordinators 
professors
moderator

-- Table to store user information
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table to define different roles
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_name VARCHAR(50) NOT NULL UNIQUE
);

-- Table to assign roles to users
CREATE TABLE user_roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  role_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Table to manage course requests
CREATE TABLE course_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_name VARCHAR(255) NOT NULL,
  description TEXT,
  status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table to handle notifications
CREATE TABLE notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  message TEXT NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert roles
INSERT INTO roles (role_name) VALUES ('Admin'), ('Coordinator'), ('Professor'), ('Moderator');

-- Sample user creation with roles
-- Passwords should be securely hashed in real implementation
INSERT INTO users (username, password) VALUES ('admin', 'admin_password');
INSERT INTO user_roles (user_id, role_id) VALUES (1, 1); -- Assigning Admin role to admin user

-- Sample queries for Admin permissions
-- View the list of users
SELECT * FROM users;

-- Create a new user
INSERT INTO users (username, password) VALUES ('new_professor', 'password');
INSERT INTO user_roles (user_id, role_id) VALUES ((SELECT id FROM users WHERE username = 'new_professor'), (SELECT id FROM roles WHERE role_name = 'Professor'));

-- Assign the role of Coordinator
UPDATE user_roles SET role_id = (SELECT id FROM roles WHERE role_name = 'Coordinator') WHERE user_id = (SELECT id FROM users WHERE username = 'new_professor');

-- Validate the professors
UPDATE users SET validated = TRUE WHERE username = 'new_professor';

-- View all professor requests
SELECT * FROM course_requests;

-- Accept the professor request
UPDATE course_requests SET status = 'approved' WHERE id = 1;

-- Reject the professor request
UPDATE course_requests SET status = 'rejected', rejection_reason = 'Reason for rejection' WHERE id = 2;

-- Delete a user account
DELETE FROM users WHERE id = 3;

-- Sample queries for Coordinator
-- View course requests
SELECT * FROM course_requests WHERE status = 'pending';

-- Approve or reject requests
UPDATE course_requests SET status = 'approved' WHERE id = 1;
UPDATE course_requests SET status = 'rejected', rejection_reason = 'Reason for rejection' WHERE id = 2;

-- Sample queries for Professors
-- Create a course request
INSERT INTO course_requests (user_id, course_name, description) VALUES ((SELECT id FROM users WHERE username = 'new_professor'), 'Course Name', 'Course Description');

-- View own requests
SELECT * FROM course_requests WHERE user_id = (SELECT id FROM users WHERE username = 'new_professor');

-- Edit and resend rejected requests
UPDATE course_requests SET course_name = 'Updated Course Name', description = 'Updated Description', status = 'pending' WHERE id = 2 AND status = 'rejected';
