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

Event lookup table

    EVENT:
    ├── id: INT PRIMARY KEY AUTO_INCREMENT NOT NULL;
    ├── name: VARCHAR(255) NOT NULL;
    ├── duration: INT NOT NULL;
    └── time: INT NOT NULL;

    CREATE TABLE EVENT (
        id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        name VARCHAR(255) NOT NULL,
        duration INT NOT NULL,
        time INT NOT NULL
    );

Event: Fire of Hogwarts

    FIRE_OF_HOGWARTS:
    ├── group_id: INT PRIMARY KEY AUTO_INCREMENT NOT NULL;
    ├── Holzbalken: BIGINT UNSIGNED NOT NULL DEFAULT 0
    ├── Steinziegel: BIGINT UNSIGNED NOT NULL DEFAULT 0
    ├── Sand: BIGINT NOT NULL DEFAULT 0
    ├── Stoff: BIGINT NOT NULL DEFAULT 0
    ├── Metall: BIGINT NOT NULL DEFAULT 0
    ├── Möbel: BIGINT NOT NULL DEFAULT 0
    ├── Glas: BIGINT NOT NULL DEFAULT 0
    ├── Baumstämme: BIGINT NOT NULL DEFAULT 0
    ├── Fels: BIGINT NOT NULL DEFAULT 0
    ├── Wolle: BIGINT NOT NULL DEFAULT 0
    ├── Erz: BIGINT NOT NULL DEFAULT 0
    └── Hymnen: BIGINT NOT NULL DEFAULT 0

    CREATE TABLE FIRE_OF_HOGWARTS (
        group_id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
        Holzbalken BIGINT NOT NULL DEFAULT 0,
        Steinziegel BIGINT NOT NULL DEFAULT 0,
        Sand BIGINT  NOT NULL DEFAULT 0,
        Stoff BIGINT  NOT NULL DEFAULT 0,
        Metall BIGINT  NOT NULL DEFAULT 0,
        Möbel BIGINT  NOT NULL DEFAULT 0,
        Glas BIGINT  NOT NULL DEFAULT 0,
        Baumstämme BIGINT  NOT NULL DEFAULT 0,
        Fels BIGINT  NOT NULL DEFAULT 0,
        Wolle BIGINT  NOT NULL DEFAULT 0,
        Erz BIGINT  NOT NULL DEFAULT 0,
        Hymnen BIGINT  NOT NULL DEFAULT 0
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
    ├── buildings: BIGINT UNSIGNED NOT NULL DEFAULT 0;
    ├── Zaubertränke_displacement: FLOAT UNSIGNED NOT NULL DEFAULT 0;
    ├── Zauberkunst_displacement: FLOAT UNSIGNED NOT NULL DEFAULT 0;
    ├── Verteidigung_displacement: FLOAT UNSIGNED NOT NULL DEFAULT 0;
    ├── Geschichte_displacement: FLOAT UNSIGNED NOT NULL DEFAULT 0;
    ├── Geschöpfe_displacement: FLOAT UNSIGNED NOT NULL DEFAULT 0;
    ├── Kräuterkunde_displacement: FLOAT UNSIGNED NOT NULL DEFAULT 0;
    └── Besenfliegen_displacement: FLOAT UNSIGNED NOT NULL DEFAULT 0;

    CREATE TABLE SCHOOL_ADMIN (
        group_id INT PRIMARY KEY AUTO_INCREMENT  NOT NULL,
        Zaubertränke BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Zauberkunst BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Verteidigung BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Geschichte BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Geschöpfe BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Kräuterkunde BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Besenfliegen BIGINT UNSIGNED NOT NULL DEFAULT 0,
        buildings BIGINT UNSIGNED NOT NULL DEFAULT 0,
        Zaubertränke_displacement FLOAT UNSIGNED NOT NULL DEFAULT 0,
        Zauberkunst_displacement FLOAT UNSIGNED NOT NULL DEFAULT 0,
        Verteidigung_displacement FLOAT UNSIGNED NOT NULL DEFAULT 0,
        Geschichte_displacement FLOAT UNSIGNED NOT NULL DEFAULT 0,
        Geschöpfe_displacement FLOAT UNSIGNED NOT NULL DEFAULT 0,
        Kräuterkunde_displacement FLOAT UNSIGNED NOT NULL DEFAULT 0,
        Besenfliegen_displacement FLOAT UNSIGNED NOT NULL DEFAULT 0
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
