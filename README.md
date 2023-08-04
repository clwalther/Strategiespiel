# Strategiespiel
This is the GitHub repository for the annually "Hymnus-Strategiespiel". More specificlly: It features the year 2023.
<br>
The generall concept and architecture of this project is to be stackable and easily expandable. Therefore all of the

## Table of Contents
- 2023 - "Die Zauberer Schulen"
    - General
    - Ministerium Schulverwaltung
    - Ministerium Arbeit

## 2023 - "Die Zauberer Schulen"
The main idea board is [here](https://miro.com/app/board/uXjVM9sY-J4=/).
<br>
In the following, we will take a look at the overall code and data structure in the code and the databases.

### General
Time logs:

    TIME:
    ├── id: INT PRIMARY KEY AUTO_INCREMENT NOT NULL;
    ├── time: INT NOT NULL;
    └── type: BOOL NOT NULL;

    CREATE TABLE TIME (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        time INT NOT NULL,
        type BOOL NOT NULL
        );

Teams lookup table  (always 12 entries):

    TEAM:
    ├── gorup_id: INT PRIMARY KEY AUTO_INCREMENT NOT NULL;
    └── teamname: VARCHAR(255) NOT NULL DEFAULT "TEAMNAME";

    CREATE TABLE TEAM (
        group_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        teamname VARCHAR(255) NOT NULL DEFAULT "TEAMNAME"
        );

### Ministerium Schulverwaltung
General school administration (always 12 entries):

    SCHOOL_ADMIN:
    ├── group_id: INT PRIMARY KEY AUTO_INCREMENT NOT NULL;
    ├── Zaubertränke: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    ├── Zauberkunst: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    ├── Verteidigung: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    ├── Geschichte: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    ├── Geschöpfe: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    ├── Kräuterkunde: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    ├── Besenfliegen: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    └── buildings: BIGINT UNSIGNED NOT NULL DEFAULT 0;

    CREATE TABLE SCHOOL_ADMIN (
        group_id INT PRIMARY KEY AUTO_INCREMENT  NOT NULL,
        Zaubertränke BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Zauberkunst BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Verteidigung BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Geschichte BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Geschöpfe BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Kräuterkunde BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Besenfliegen BIGINT UNSIGNED NOT NULL DEFAULT 0,
        buildings BIGINT UNSIGNED NOT NULL DEFAULT 0
        );

Students:

    STUDENTS:
    ├── id: INT PRIMARY KEY AUTO_INCREMENT NOT NULL;
    ├── group_id: INT NOT NULL;
    └── value: BIGINT UNSIGNED NOT NULL DEFAULT 0;

    CREATE TABLE STUDENTS (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        group_id INT NOT NULL,
        value BIGINT UNSIGNED NOT NULL DEFAULT 0
    );


### Ministerium Arbeit
Table arichtecture (always 12 entries):

    LABOUR:
    ├── group_id: INT PRIMARY KEY AUTO_INCREMENT;
    ├── prestige: INT NOT NULL DEFAULT 0;
    ├── Medimagier: FLOAT NOT NULL DEFAULT 0;
    ├── Auror: FLOAT NOT NULL DEFAULT 0;
    ├── Ministeriumsbeamter: FLOAT NOT NULL DEFAULT 0;
    ├── Drachenwärter: FLOAT NOT NULL DEFAULT 0;
    ├── Magiezoologe: FLOAT NOT NULL DEFAULT 0;
    ├── Zauberstabschreinermeister: FLOAT NOT NULL DEFAULT 0;
    └── Quidditchprofi: FLOAT NOT NULL DEFAULT 0;

    CREATE TABLE LABOUR (
        group_id INT PRIMARY KEY AUTO_INCREMENT,
        prestige INT NOT NULL DEFAULT 0,
        Medimagier FLOAT NOT NULL DEFAULT 0,
        Auror FLOAT NOT NULL DEFAULT 0,
        Ministeriumsbeamter FLOAT NOT NULL DEFAULT 0,
        Drachenwärter FLOAT NOT NULL DEFAULT 0,
        Magiezoologe FLOAT NOT NULL DEFAULT 0,
        Zauberstabschreinermeister FLOAT NOT NULL DEFAULT 0,
        Quidditchprofi FLOAT NOT NULL DEFAULT 0
        );


    WORKERS:
    ├── id: INT PRIMARY KEY AUTO_INCREMENT NOT NULL;
    ├── group_id: INT NOT NULL;
    ├── job_name: VARCHAR(255) NOT NULL;
    └── value: BIGINT UNSIGNED NOT NULL DEFAULT 0;

    CREATE TABLE WORKERS (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        group_id INT NOT NULL,
        job_name VARCHAR(255) NOT NULL,
        value BIGINT UNSIGNED NOT NULL DEFAULT 0
    );
