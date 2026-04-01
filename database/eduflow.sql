CREATE TABLE Users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'student',
    time DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Class (
    id_class INT AUTO_INCREMENT PRIMARY KEY,
    subtitle TEXT,
    img VARCHAR(255),
    material VARCHAR(100) NOT NULL,
    course VARCHAR(50) NOT NULL,
    id_teacher INT NOT NULL,
    time DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_teacher) REFERENCES Users(id_user)
);

CREATE TABLE Registrations (
    id_registrations INT AUTO_INCREMENT PRIMARY KEY,
    id_class INT NOT NULL,
    id_student INT NOT NULL,
    time DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_class) REFERENCES Class(id_class),
    FOREIGN KEY (id_student) REFERENCES Users(id_user)
);

CREATE TABLE Topic (
    id_topic INT AUTO_INCREMENT PRIMARY KEY,
    id_class INT NOT NULL,
    number INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle TEXT,
    time DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_class) REFERENCES Class(id_class)
);

CREATE TABLE Task (
    id_task INT AUTO_INCREMENT PRIMARY KEY,
    id_topic INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    subtitle TEXT,
    archive VARCHAR(255),
    timeMax DATETIME,
    time DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_topic) REFERENCES Topic(id_topic)
);

CREATE TABLE Answer (
    id_answer INT AUTO_INCREMENT PRIMARY KEY,
    id_task INT NOT NULL,
    id_student INT NOT NULL,
    archive VARCHAR(255) NOT NULL,
    note INT,
    time DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_task) REFERENCES Task(id_task),
    FOREIGN KEY (id_student) REFERENCES Users(id_user)
);

CREATE TABLE Content(
    id_content INT AUTO_INCREMENT PRIMARY KEY,
    id_topic INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    subtitle TEXT,
    archive VARCHAR(255),
    time DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_topic) REFERENCES Topic(id_topic)
);