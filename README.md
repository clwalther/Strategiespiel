# Strategiespiel
This is the GitHub repository for the annually "Hymnus-Strategiespiel". More specificlly: It features the year 2023.
<br>
The generall concept and architecture of this project is to be stackable and easily expandable. Therefore all of the

## Table of Contents
- 2023 - "Die Zauberer Schulen"
    - Ministerium Schulverwaltung
    - Ministerium Arbeit

## 2023 - "Die Zauberer Schulen"
The main idea board is [here](https://miro.com/app/board/uXjVM9sY-J4=/).
<br>
In the following, we will take a look at the overall code and data structure in the code and the databases.

### Ministerium Schulverwaltung
Table arichtecture:

    MINISTRY_SCHOOL_ADMIN;
    ├── group_id: INT PRIMARY KEY AUTO_INCREMENT  NOT NULL;     # identifes the group
    ├── teacher_slots: INT NOT NULL;            # holds the number of teacher slots
    ├── teachers: INT NOT NULL;                 # represents the schools teachers as an integer-string (influences the quality of a student in a given subject)
    ├── graduates_slots: INT NOT NULL;          # defines how many graduates / year
    ├── graduates: INT NOT NULL;                # represents the schools graduates as an integer-string
    └── buildings: INT NOT NULL;                # represents the schools buildings as an integer-string (influences the quality of a student in a given subject)

    CREATE TABLE MINISTRY_SCHOOL_ADMIN (group_id INT PRIMARY KEY AUTO_INCREMENT  NOT NULL, teacher_slots INT NOT NULL, teachers INT NOT NULL, graduates_slots INT NOT NULL, graduates INT NOT NULL, buildings INT NOT NULL);

    INSERT INTO MINISTRY_SCHOOL_ADMIN (teacher_slots, graduates_slots, graduates, teachers, buildings) VALUES (3, 3, 0, 2, 5);

`group_id`: Number from 1 to 12. 4 teams compose one order and therefore group_ids from ((n-1) * 4) + 1 -> n * 4 make a one team for n beeing [1, 2, 3].

`teacher_slots`: Defines how many teachers can be employed

`graduates_slots`: Defines how many students can be in the school and therefore influcences the yield of students per year.

`graduates`: Holds each not yet retrived graduate and its value in each subject. max value is 5

`teachers`: Level of teacher influeces the gausian (around which value its is concentrated) curve of the graduates performance. And in which subject.

`buildings`: Ceils the maximum level a student can accomplish in a certain subject. Further dictating what teachers can be employed and therefore which subjects can be thaugth.

