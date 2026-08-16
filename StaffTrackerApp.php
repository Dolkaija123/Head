<?php

require_once __DIR__ . '/src/StaffMember.php';
require_once __DIR__ . '/src/PermanentStaff.php';
require_once __DIR__ . '/src/ContractStaff.php';
require_once __DIR__ . '/src/Skill.php';
require_once __DIR__ . '/src/FileManager.php';

/**
 * File: StaffTrackerApp.php
 * Author: Phuwadon Khayanyiam
 * Date: 16 Aug 2026
 * Description: Main entry point for the NovaSoft Staff Skills Tracker.
 * Displays a numbered text menu (R10) and dispatches to the correct
 * action using a switch statement inside a while loop. Run with:
 * php StaffTrackerApp.php
 */

class StaffTrackerApp
{
    private const DATA_FILE = "staff_data.txt";

    /** @var StaffMember[] The staff members currently held in memory. */
    private array $staff_list;

    private bool $running;
    private FileManager $file_manager;

    /**
     * Sets up the application, ready to run.
     *
     * @return void
     */
    public function __construct()
    {
        $this->staff_list = [];
        $this->running = true;
        $this->file_manager = new FileManager();
    }

    /**
     * Starts the application: loads any existing data, then repeatedly
     * displays the menu and processes the user's selection until they
     * choose to exit (option 7). This is the iteration construct that
     * drives the whole program (R10).
     *
     * @return void
     */
    public function run(): void
    {
        echo "=========================================\n";
        echo " NovaSoft Solutions - Staff Skills Tracker\n";
        echo "=========================================\n";

        $this->load_data();

        while ($this->running) {
            $this->display_menu();
            $choice = trim(fgets(STDIN));

            switch ($choice) {
                case "1":
                    $this->add_staff();
                    break;
                case "2":
                    $this->add_skill();
                    break;
                case "3":
                    $this->search_by_skill();
                    break;
                case "4":
                    $this->display_all_staff();
                    break;
                case "5":
                    $this->save_data();
                    break;
                case "6":
                    $this->load_data();
                    break;
                case "7":
                    $this->running = false;
                    echo "Goodbye!\n";
                    break;
                default:
                    echo "Invalid option. Please enter a number from 1 to 7.\n";
            }
        }
    }

    /**
     * Displays the numbered text menu (R10).
     *
     * @return void
     */
    private function display_menu(): void
    {
        echo "\n----------- MENU -----------\n";
        echo "1. Add Staff\n";
        echo "2. Add Skill\n";
        echo "3. Search by Skill\n";
        echo "4. Display All Staff\n";
        echo "5. Save Data\n";
        echo "6. Load Data\n";
        echo "7. Exit\n";
        echo "-----------------------------\n";
        echo "Enter your choice: ";
    }

    /**
     * Adds a new staff member (PermanentStaff or ContractStaff) by
     * collecting details from the user via fgets(STDIN), as required
     * by R3. Uses a sequence of prompts to gather each field in order.
     *
     * @return void
     */
    private function add_staff(): void
    {
        echo "\n--- Add New Staff Member ---\n";

        echo "Full name: ";
        $name = trim(fgets(STDIN));

        echo "Job title: ";
        $job_title = trim(fgets(STDIN));

        echo "Department: ";
        $department = trim(fgets(STDIN));

        echo "Years of experience: ";
        $years_experience = (int) trim(fgets(STDIN));

        echo "Staff type (1 = Permanent, 2 = Contract): ";
        $type_choice = trim(fgets(STDIN));

        if ($type_choice === "2") {
            echo "Contract end date (e.g. 31/12/2026): ";
            $contract_end_date = trim(fgets(STDIN));

            $new_staff = ContractStaff::createFullContract(
                $name,
                $job_title,
                $department,
                $years_experience,
                $contract_end_date
            );
        } else {
            $new_staff = PermanentStaff::createFull($name, $job_title, $department, $years_experience);
        }

        $this->staff_list[] = $new_staff;

        echo "Staff member added successfully.\n";
    }

    /**
     * Adds a new skill to an existing staff member. Searches for the
     * staff member by name using a loop (R4), then collects the skill
     * details from the user.
     *
     * @return void
     */
    private function add_skill(): void
    {
        echo "\n--- Add Skill to Staff Member ---\n";
        echo "Enter the staff member's name: ";
        $search_name = trim(fgets(STDIN));

        $found_staff = null;

        // Loop through the array to search for the staff member's name.
        for ($i = 0; $i < count($this->staff_list); $i++) {
            if (strcasecmp($this->staff_list[$i]->get_name(), $search_name) === 0) {
                $found_staff = $this->staff_list[$i];
                break;
            }
        }

        if ($found_staff === null) {
            echo "No staff member found with that name.\n";
            return;
        }

        echo "Skill name: ";
        $skill_name = trim(fgets(STDIN));

        echo "Skill category: ";
        $skill_category = trim(fgets(STDIN));

        echo "Proficiency level (Beginner/Intermediate/Advanced): ";
        $proficiency_level = trim(fgets(STDIN));

        $new_skill = new Skill($skill_name, $skill_category, $proficiency_level);
        $added = $found_staff->add_skill($new_skill);

        if ($added) {
            echo "Skill added to {$found_staff->get_name()}.\n";
        } else {
            echo "Could not add skill - {$found_staff->get_name()} already has the maximum of "
                . StaffMember::MAX_SKILLS . " skills.\n";
        }
    }

    /**
     * Searches for all staff members who have a specific skill and
     * displays the results on screen. Uses strcasecmp() for a
     * case-insensitive comparison of skill names (R5).
     *
     * @return void
     */
    private function search_by_skill(): void
    {
        echo "\n--- Search Staff By Skill ---\n";
        echo "Enter the skill name to search for: ";
        $skill_name = trim(fgets(STDIN));

        $matches_found = 0;

        foreach ($this->staff_list as $staff_member) {
            if ($staff_member->has_skill($skill_name)) {
                echo "- " . $staff_member->get_staff_summary() . "\n";
                $matches_found++;
            }
        }

        if ($matches_found === 0) {
            echo "No staff members found with the skill \"{$skill_name}\".\n";
        }
    }

    /**
     * Displays all staff members currently held in memory, including
     * their summary (which differs by staff type - polymorphism, R7)
     * and their full list of skills.
     *
     * @return void
     */
    private function display_all_staff(): void
    {
        echo "\n--- All Staff Members ---\n";

        if (empty($this->staff_list)) {
            echo "No staff members recorded yet.\n";
            return;
        }

        foreach ($this->staff_list as $staff_member) {
            echo $staff_member . "\n\n";
        }
    }

    /**
     * Saves all staff and skill data to staff_data.txt via
     * FileManager::save_to_file() (R6).
     *
     * @return void
     */
    private function save_data(): void
    {
        $success = $this->file_manager->save_to_file($this->staff_list, self::DATA_FILE);

        if ($success) {
            echo "Data saved to " . self::DATA_FILE . ".\n";
        } else {
            echo "Failed to save data.\n";
        }
    }

    /**
     * Loads staff and skill data from staff_data.txt via
     * FileManager::load_from_file() (R6). Called automatically on
     * startup, and can also be triggered from the menu.
     *
     * @return void
     */
    private function load_data(): void
    {
        $loaded_staff = $this->file_manager->load_from_file(self::DATA_FILE);

        if (!empty($loaded_staff)) {
            $this->staff_list = $loaded_staff;
            echo "Loaded " . count($loaded_staff) . " staff member(s) from " . self::DATA_FILE . ".\n";
        } else {
            echo "No existing data found - starting with an empty staff list.\n";
        }
    }
}

// Entry point - only run the app when this file is executed directly.
if (php_sapi_name() === "cli") {
    $app = new StaffTrackerApp();
    $app->run();
}
