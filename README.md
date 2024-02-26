-- Create User table
CREATE TABLE User (
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(255),
    PRIMARY KEY (username)
);

-- Create Course table
CREATE TABLE Course (
    crn INT NOT NULL,
    course_code VARCHAR(255),
    course_name VARCHAR(255),
    exam_count INT,
    program_code VARCHAR(255),
    term VARCHAR(255),
    section_code VARCHAR(255),
    PRIMARY KEY (crn, term)
);

-- Make sure the foreign keys reference existing primary keys correctly
-- Create Handles table
CREATE TABLE Handles (
    crn INT,
    username VARCHAR(255),
    PRIMARY KEY (crn, username),
    FOREIGN KEY (crn) REFERENCES Course(crn),
    FOREIGN KEY (username) REFERENCES User(username)
);

-- Create Teaches table
CREATE TABLE Teaches (
    crn INT,
    username VARCHAR(255),
    PRIMARY KEY (crn, username),
    FOREIGN KEY (crn) REFERENCES Course(crn),
    FOREIGN KEY (username) REFERENCES User(username)
);

-- Create Requirement table
CREATE TABLE Requirement (
    type VARCHAR(255) NOT NULL,
    PRIMARY KEY (type)
);

-- Create Documents table
CREATE TABLE Documents (
    type VARCHAR(255) NOT NULL,
    exam BOOLEAN,
    soft BOOLEAN,
    PRIMARY KEY (type)
);

-- Create RequiredDocuments table
CREATE TABLE RequiredDocuments (
    requirement_type VARCHAR(255),
    document_type VARCHAR(255),
    PRIMARY KEY (requirement_type, document_type),
    FOREIGN KEY (requirement_type) REFERENCES Requirement(type),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);

-- Create CourseRequirements table
CREATE TABLE CourseRequirements (
    requirement_type VARCHAR(255),
    term VARCHAR(255),
    crn INT,
    PRIMARY KEY (requirement_type, term, crn),
    FOREIGN KEY (requirement_type) REFERENCES Requirement(type),
    FOREIGN KEY (crn) REFERENCES Course(crn)
);

-- Create Submit table
CREATE TABLE Submit (
    term VARCHAR(255),
    crn INT,
    document_type VARCHAR(255),
    submitted BOOLEAN,
    pdf_data BLOB,
    PRIMARY KEY (term, crn, document_type),
    FOREIGN KEY (crn) REFERENCES Course(crn),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);

CREATE TABLE Soft_Submit (
    term VARCHAR(255),
    crn INT,
    document_type VARCHAR(255),
    submitted_prof BOOLEAN,
    submitted_arg BOOLEAN,
    PRIMARY KEY (term, crn, document_type),
    FOREIGN KEY (crn) REFERENCES Course(crn),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);

### add the following code for dummy data as of now
INSERT INTO Course (course_code, course_name, exam_count, program_code, term, crn, section_code) VALUES
('CS101', 'Introduction to Computer Science', 2, 'CSE', 'Spring 2024', 10101, 'A01'),
('CS102', 'Data Structures', 2, 'CSE', 'Fall 2024', 10202, 'A02'),
('CS103', 'Algorithms', 2, 'CSE', 'Summer 2024', 10303, 'A03');

INSERT INTO user (username, role, password) VALUES ('doruk', 'lecturer','123');


INSERT INTO Requirement (type) VALUES ('YÖK');

INSERT INTO Documents (type, exam, soft) VALUES ('Midterm', true, false), ('Final', true, false);

INSERT INTO RequiredDocuments (requirement_type, document_type) VALUES ('YÖK', 'Midterm'), ('YÖK', 'Final');
INSERT INTO Course (course_code, course_name, exam_count, program_code, term, crn, section_code) VALUES ('CS101', 'Introduction to Programming', 3, 'COMPSCI', 'Spring 2024', 12345, '01');

INSERT INTO CourseRequirements (requirement_type, term, crn) VALUES ('YÖK', 'Spring 2024', 12345);


