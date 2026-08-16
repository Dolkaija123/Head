<?php

require_once __DIR__ . '/StaffMember.php';

/**
 * File: ContractStaff.php
 * Author: Phuwadon Khayanyiam
 * Date: 16 Aug 2026
 * Description: Represents a contract staff member. Extends StaffMember
 * (inheritance - R7) and adds a $contract_end_date property. Overrides
 * get_staff_summary() to include the contract end date, demonstrating
 * polymorphism alongside PermanentStaff (R7).
 */

class ContractStaff extends StaffMember
{
    private string $contract_end_date;

    /**
     * Protected constructor. $contract_end_date defaults to an empty
     * string so that the parent class's createFull()/createWithNameOnly()
     * factory methods (which call "new static(...)" with only four
     * arguments) still work correctly for ContractStaff.
     *
     * @param string $name             Full name of the staff member.
     * @param string $job_title        Current job title.
     * @param string $department       Department they work in.
     * @param int    $years_experience Years of industry experience.
     * @param string $contract_end_date Contract end date, e.g. "31/12/2026".
     * @return void
     */
    protected function __construct(
        string $name,
        string $job_title,
        string $department,
        int $years_experience,
        string $contract_end_date = ""
    ) {
        parent::__construct($name, $job_title, $department, $years_experience);
        $this->contract_end_date = $contract_end_date;
    }

    /**
     * Static factory method that creates a ContractStaff with all
     * details, including the contract end date, set at once.
     *
     * @param string $name              Full name of the staff member.
     * @param string $job_title         Current job title.
     * @param string $department        Department they work in.
     * @param int    $years_experience  Years of industry experience.
     * @param string $contract_end_date Contract end date, e.g. "31/12/2026".
     * @return ContractStaff A new fully populated contract staff instance.
     */
    public static function createFullContract(
        string $name,
        string $job_title,
        string $department,
        int $years_experience,
        string $contract_end_date
    ): ContractStaff {
        return new ContractStaff($name, $job_title, $department, $years_experience, $contract_end_date);
    }

    /**
     * Sets the contract end date after the object has been created.
     * Useful when a ContractStaff is built via createWithNameOnly().
     *
     * @param string $contract_end_date Contract end date, e.g. "31/12/2026".
     * @return void
     */
    public function set_contract_end_date(string $contract_end_date): void
    {
        $this->contract_end_date = $contract_end_date;
    }

    /**
     * Returns the contract end date.
     *
     * @return string The contract end date.
     */
    public function get_contract_end_date(): string
    {
        return $this->contract_end_date;
    }

    /**
     * Returns the staff type label used by FileManager.
     *
     * @return string Always "ContractStaff".
     */
    public function get_staff_type(): string
    {
        return "ContractStaff";
    }

    /**
     * Returns a formatted summary identifying this staff member as a
     * contractor, including their contract end date. This overrides the
     * parent implementation so that get_staff_summary() returns
     * different text than PermanentStaff (polymorphism - R7).
     *
     * @return string Human-readable summary for a contract employee.
     */
    public function get_staff_summary(): string
    {
        return "{$this->get_name()} - {$this->get_job_title()}, {$this->get_department()} "
            . "({$this->get_years_experience()} yrs experience) [Contract Staff, "
            . "ends {$this->contract_end_date}]";
    }
}
