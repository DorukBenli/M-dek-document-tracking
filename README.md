#database creation code

CREATE TABLE User (
    username VARCHAR(255) NOT NULL,
    role VARCHAR(255),
    PRIMARY KEY (username)
);

CREATE TABLE Course (
    course_code VARCHAR(255),
    course_name VARCHAR(255),
    exam_count INT,
    program_code VARCHAR(255),
    term VARCHAR(255),
    crn INT,
    section_code VARCHAR(255),
    PRIMARY KEY (term, crn)
);

CREATE TABLE Handles (
    crn INT,
    username VARCHAR(255),
    PRIMARY KEY (crn, username),
    FOREIGN KEY (crn) REFERENCES Course(crn),
    FOREIGN KEY (username) REFERENCES User(username)
);


CREATE TABLE Teaches (
    crn INT,
    username VARCHAR(255),
    PRIMARY KEY (crn, username),
    FOREIGN KEY (crn) REFERENCES Course(crn),
    FOREIGN KEY (username) REFERENCES User(username)
);


CREATE TABLE Requirement (
    type VARCHAR(255) PRIMARY KEY
);


CREATE TABLE Documents (
    type VARCHAR(255) PRIMARY KEY,
    exam BOOLEAN,
    soft BOOLEAN
);


CREATE TABLE RequiredDocuments (
    requirement_type VARCHAR(255),
    document_type VARCHAR(255),
    PRIMARY KEY (requirement_type, document_type),
    FOREIGN KEY (requirement_type) REFERENCES Requirement(type),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);


CREATE TABLE CourseRequirements (
    requirement_type VARCHAR(255),
    term VARCHAR(255),
    crn INT,
    PRIMARY KEY (requirement_type, term, crn),
    FOREIGN KEY (requirement_type) REFERENCES Requirement(type),
    FOREIGN KEY (term, crn) REFERENCES Course(term, crn)
);

CREATE TABLE Submit (
    term VARCHAR(255),
    crn INT,
    document_type VARCHAR(255),
    submitted BOOLEAN,
    pdf_data BLOB,
    PRIMARY KEY (term, crn, document_type),
    FOREIGN KEY (term, crn) REFERENCES Course(term, crn),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);

CREATE TABLE Soft_Submit (
    term VARCHAR(255),
    crn INT,
    document_type VARCHAR(255),
    submitted_prof BOOLEAN,
    submitted_arg BOOLEAN,
    PRIMARY KEY (term, crn, document_type),
    FOREIGN KEY (term, crn) REFERENCES Course(term, crn),
    FOREIGN KEY (document_type) REFERENCES Documents(type)
);
