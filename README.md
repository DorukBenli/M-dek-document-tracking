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


-- Make sure the foreign keys reference existing primary keys correctly
-- Create Handles table
CREATE TABLE Handles (
    course_code VARCHAR(255),
    username VARCHAR(255),
    PRIMARY KEY (course_code, username),
    FOREIGN KEY (course_code) REFERENCES Course(course_code),
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
    course_code VARCHAR(255),
    PRIMARY KEY (requirement_type, term, course_code),
    FOREIGN KEY (requirement_type) REFERENCES Requirement(type),
    FOREIGN KEY (course_code) REFERENCES Course(course_code)
);

-- Create Submit table
CREATE TABLE Submit (
    term VARCHAR(255),
    course_code VARCHAR(255),
    document_type VARCHAR(255),
    submitted BOOLEAN,
    pdf_data BLOB,
    PRIMARY KEY (term, course_code, document_type),
    FOREIGN KEY (course_code) REFERENCES Course(course_code),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);

CREATE TABLE Soft_Submit (
    term VARCHAR(255),
    course_code VARCHAR(255),
    document_type VARCHAR(255),
    submitted_prof BOOLEAN,
    submitted_arg BOOLEAN,
    PRIMARY KEY (term, course_code, document_type),
    FOREIGN KEY (course_code) REFERENCES Course(course_code),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);

### add the following code for dummy data as of now



INSERT INTO Requirement (type) VALUES ('YÖK');

INSERT INTO Documents (type, exam, soft) VALUES ('Midterm', true, false), ('Final', true, false);

INSERT INTO RequiredDocuments (requirement_type, document_type) VALUES ('YÖK', 'Midterm'), ('YÖK', 'Final');
INSERT INTO Course (course_code, course_name, exam_count, program_code, term, crn, section_code) VALUES ('CS101', 'Introduction to Programming', 3, 'COMPSCI', 'Spring 2024', 12345, '01');

INSERT INTO CourseRequirements (requirement_type, term, crn) VALUES ('YÖK', 'Spring 2024', 12345);