<?php

require_once __DIR__ . '/StaffMember.php';

/**
 * File: PermanentStaff.php
 * Author: Phuwadon Khayanyiam
 * Date: 16 Aug 2026
 * Description: Represents a permanent staff member. Extends StaffMember
 * (inheritance - R7) and overrides get_staff_summary() to produce a
 * summary specific to permanent employment (polymorphism - R7).
 */

class PermanentStaff extends StaffMember
{
    /**
     * Returns the staff type label used by FileManager.
     *
     * @return string Always "PermanentStaff".
     */
    public function get_staff_type(): string
    {
        return "PermanentStaff";
    }

    /**
     * Returns a formatted summary identifying this staff member as
     * permanent. This overrides the parent implementation, so calling
     * get_staff_summary() on a PermanentStaff produces different text
     * than on a ContractStaff, even though the method name is the same.
     *
     * @return string Human-readable summary for a permanent employee.
     */
    public function get_staff_summary(): string
    {
        return "{$this->get_name()} - {$this->get_job_title()}, {$this->get_department()} "
            . "({$this->get_years_experience()} yrs experience) [Permanent Staff]";
    }
}
