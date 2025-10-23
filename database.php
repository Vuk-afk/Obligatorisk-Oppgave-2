CREATE TABLE klasse (
    klassekode CHAR(5) NOT NULL,
    klassenavn VARCHAR(50) NOT NULL,
    studiumkode VARCHAR(50) NOT NULL,
    PRIMARY KEY (klassekode)
);

CREATE TABLE student (
    brukernavn CHAR(7) NOT NULL,
    fornavn VARCHAR(50) NOT NULL,
    etternavn VARCHAR(50) NOT NULL,
    klassekode CHAR(5) NOT NULL,
    PRIMARY KEY (brukernavn),
    FOREIGN KEY (klassekode) REFERENCES klasse (klassekode)
);

INSERT INTO klasse VALUES ('IT1', 'IT og ledelse 1. år', 'ITLED');
INSERT INTO klasse VALUES ('IT2', 'IT og ledelse 2. år', 'ITLED');
INSERT INTO klasse VALUES ('IT3', 'IT og ledelse 3. år', 'ITLED');

INSERT INTO student VALUES ('gb', 'Geir', 'Bjarvin', 'IT1');
INSERT INTO student VALUES ('mrj', 'Marius', 'R. Johannessen', 'IT1');
INSERT INTO student VALUES ('tb', 'Tove', 'Bøe', 'IT2');