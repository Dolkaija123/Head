NovaSoft Solutions - Staff Skills Tracker v1.0 (PHP Edition)
==============================================================

WHAT THIS PROJECT IS
---------------------
A command-line PHP application for NovaSoft Solutions that lets an
HR administrator manage staff members and their skills. Built to meet
client requirements R1-R10 from the Staff Skills Tracker project brief
(ICTPRG430 - Apply introductory object-oriented language skills).

PHP VERSION REQUIRED
---------------------
PHP 8.x (developed and tested with PHP 8.3).

HOW TO RUN
-----------
    php StaffTrackerApp.php

Then follow the on-screen numbered menu:
    1. Add Staff
    2. Add Skill
    3. Search by Skill
    4. Display All Staff
    5. Save Data
    6. Load Data
    7. Exit

Data is saved to and loaded from staff_data.txt in the same folder.

PROJECT STRUCTURE
-------------------
    StaffTrackerApp.php   - Main entry point / menu loop
    /src
        StaffMember.php   - Base class (R1, R8, R9)
        PermanentStaff.php- Extends StaffMember (R7)
        ContractStaff.php - Extends StaffMember, adds contract end date (R7)
        Skill.php         - Skill value object (R2)
        FileManager.php   - Saves/loads staff_data.txt (R6)
    /docs
        DesignArtifact.md - Annotated pseudocode design document
    README.txt            - This file

KEY OOP FEATURES DEMONSTRATED
-------------------------------
- Inheritance: PermanentStaff and ContractStaff extend StaffMember.
- Polymorphism: get_staff_summary() is overridden in both subclasses
  and returns different text for each staff type.
- Aggregation: StaffMember holds a fixed-size array (max 10) of Skill
  objects, tracked with a $skill_count counter.
- Static factory methods: StaffMember::createFull() and
  createWithNameOnly() replace constructor overloading, which PHP
  does not support.
- File I/O: FileManager uses fopen()/fwrite()/fgets()/fclose() to
  persist data to a plain text file.
