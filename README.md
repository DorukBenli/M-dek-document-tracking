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
