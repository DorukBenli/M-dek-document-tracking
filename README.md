#database creation code

CREATE TABLE User (
    username VARCHAR(255) PRIMARY KEY,
    role VARCHAR(255) NOT NULL
);

CREATE TABLE Course (
    course_code VARCHAR(255) PRIMARY KEY,
    course_name VARCHAR(255) NOT NULL,
    exam_count INT,
    program_code VARCHAR(255),
    term VARCHAR(255),
    crn VARCHAR(255),
    section_code VARCHAR(255)
);

CREATE TABLE Requirement (
    type VARCHAR(255) PRIMARY KEY
);

CREATE TABLE Documents (
    doc_id INT PRIMARY KEY AUTO_INCREMENT,
    doc_type VARCHAR(255),
    pdf_data BLOB,
    soft_copy BOOLEAN,
    exam BOOLEAN
);

CREATE TABLE Submit (
    user_username VARCHAR(255),
    doc_id INT,
    FOREIGN KEY (user_username) REFERENCES User(username),
    FOREIGN KEY (doc_id) REFERENCES Documents(doc_id)
);

CREATE TABLE Teaches (
    user_username VARCHAR(255),
    course_code VARCHAR(255),
    FOREIGN KEY (user_username) REFERENCES User(username),
    FOREIGN KEY (course_code) REFERENCES Course(course_code),
    PRIMARY KEY (user_username, course_code)
);

CREATE TABLE Handles (
    user_username VARCHAR(255),
    course_code VARCHAR(255),
    FOREIGN KEY (user_username) REFERENCES User(username),
    FOREIGN KEY (course_code) REFERENCES Course(course_code),
    PRIMARY KEY (user_username, course_code)
);

CREATE TABLE CourseRequirements (
    course_code VARCHAR(255),
    requirement_type VARCHAR(255),
    FOREIGN KEY (course_code) REFERENCES Course(course_code),
    FOREIGN KEY (requirement_type) REFERENCES Requirement(type),
    PRIMARY KEY (course_code, requirement_type)
);