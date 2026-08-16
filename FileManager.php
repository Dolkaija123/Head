<?php

require_once __DIR__ . '/StaffMember.php';
require_once __DIR__ . '/PermanentStaff.php';
require_once __DIR__ . '/ContractStaff.php';
require_once __DIR__ . '/Skill.php';

/**
 * File: FileManager.php
 * Author: Phuwadon Khayanyiam
 * Date: 16 Aug 2026
 * Description: Handles saving the staff list to staff_data.txt and
 * loading it back into memory, using fopen()/fwrite()/fgets()/fclose()
 * as required (R6). Each staff record is written on its own line,
 * followed by one line per associated Skill object.
 */

class FileManager
{
    /** Field separator used within each line of the data file. */
    const FIELD_SEPARATOR = "|";

    /**
     * Saves the given list of staff members (and their skills) to
     * staff_data.txt using fopen(), fwrite(), and fclose().
     *
     * @param StaffMember[] $staff_list      The staff members to save.
     * @param string        $file_path       Path of the text file to write to.
     * @return bool True on success, false if the file could not be opened.
     */
    public function save_to_file(array $staff_list, string $file_path = "staff_data.txt"): bool
    {
        $file_handle = fopen($file_path, "w");

        if ($file_handle === false) {
            return false;
        }

        foreach ($staff_list as $staff_member) {
            $contract_end_date = "";
            if ($staff_member instanceof ContractStaff) {
                $contract_end_date = $staff_member->get_contract_end_date();
            }

            // STAFF|type|name|job_title|department|years_experience|contract_end_date
            $staff_line = implode(self::FIELD_SEPARATOR, [
                "STAFF",
                $staff_member->get_staff_type(),
                $staff_member->get_name(),
                $staff_member->get_job_title(),
                $staff_member->get_department(),
                (string) $staff_member->get_years_experience(),
                $contract_end_date,
            ]);

            fwrite($file_handle, $staff_line . "\n");

            // SKILL|skill_name|skill_category|proficiency_level
            foreach ($staff_member->get_skills() as $skill) {
                $skill_line = implode(self::FIELD_SEPARATOR, [
                    "SKILL",
                    $skill->get_skill_name(),
                    $skill->get_skill_category(),
                    $skill->get_proficiency_level(),
                ]);

                fwrite($file_handle, $skill_line . "\n");
            }
        }

        fclose($file_handle);

        return true;
    }

    /**
     * Loads the staff list back from staff_data.txt, reconstructing
     * StaffMember (PermanentStaff/ContractStaff) and Skill objects
     * using fopen(), fgets(), and fclose(). If the file does not exist,
     * an empty array is returned so the application can start fresh.
     *
     * @param string $file_path Path of the text file to read from.
     * @return StaffMember[] The reconstructed list of staff members.
     */
    public function load_from_file(string $file_path = "staff_data.txt"): array
    {
        $staff_list = [];

        if (!file_exists($file_path)) {
            return $staff_list;
        }

        $file_handle = fopen($file_path, "r");

        if ($file_handle === false) {
            return $staff_list;
        }

        $current_staff = null;

        while (($line = fgets($file_handle)) !== false) {
            $line = rtrim($line, "\r\n");

            if ($line === "") {
                continue;
            }

            $parts = explode(self::FIELD_SEPARATOR, $line);
            $record_type = $parts[0];

            if ($record_type === "STAFF") {
                [, $staff_type, $name, $job_title, $department, $years_experience, $contract_end_date] = $parts;

                if ($staff_type === "ContractStaff") {
                    $current_staff = ContractStaff::createFullContract(
                        $name,
                        $job_title,
                        $department,
                        (int) $years_experience,
                        $contract_end_date
                    );
                } else {
                    // Default to PermanentStaff for unknown/legacy types.
                    $current_staff = PermanentStaff::createFull(
                        $name,
                        $job_title,
                        $department,
                        (int) $years_experience
                    );
                }

                $staff_list[] = $current_staff;
            } elseif ($record_type === "SKILL" && $current_staff !== null) {
                [, $skill_name, $skill_category, $proficiency_level] = $parts;
                $current_staff->add_skill(new Skill($skill_name, $skill_category, $proficiency_level));
            }
        }

        fclose($file_handle);

        return $staff_list;
    }
}
