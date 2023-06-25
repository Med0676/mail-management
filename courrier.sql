-- Create the users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(255) NOT NULL
);

-- Insert users data
INSERT INTO users (username, password, role)
VALUES
  ('med', 'med123', 'secretary'),
  ('test', 'test123', 'user');

-- Create the couriers table
CREATE TABLE couriers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  subject VARCHAR(255) NOT NULL,
  pdf_file VARCHAR(255) NOT NULL,
  date DATE NOT NULL,
  type ENUM('arrival', 'departure') NOT NULL
);

-- Insert courier data
INSERT INTO couriers (subject, pdf_file, date, type)
VALUES
  ('Subject 1', 'sample.pdf', '2023-06-01', 'arrival'),
  ('Subject 2', 'sample.pdf', '2023-06-02', 'departure'),
  ('Subject 3', 'sample.pdf', '2023-06-03', 'arrival'),
  ('Subject 4', 'sample.pdf', '2023-06-04', 'departure');
