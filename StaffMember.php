<?php

/**
 * File: StaffMember.php
 * Author: Phuwadon Khayanyiam
 * Date: 16 Aug 2026
 * Description: Base class for a NovaSoft staff member. Stores the four
 * core fields (R1) and a fixed-size array of up to 10 Skill objects
 * (R9, aggregation - R2). PermanentStaff and ContractStaff extend this
 * class and override get_staff_summary() to demonstrate polymorphism (R7).
 */

class StaffMember
{
    /** Maximum number of skills a staff member may hold. */
    const MAX_SKILLS = 10;

    protected string $name;
    protected string $job_title;
    protected string $department;
    protected int $years_experience;

    /** @var Skill[] Fixed-size array (max 10) of Skill objects. */
    private array $skills;

    /** Tracks how many skill slots are currently filled. */
    private int $skill_count;

    /**
     * Protected constructor. Use the static factory methods createFull()
     * or createWithNameOnly() to build a StaffMember instead (PHP only
     * supports one __construct() per class, so factory methods are used
     * in place of constructor overloading - R8).
     *
     * @param string $name             Full name of the staff member.
     * @param string $job_title        Current job title.
     * @param string $department       Department they work in.
     * @param int    $years_experience Years of industry experience.
     * @return void
     */
    protected function __construct(string $name, string $job_title, string $department, int $years_experience)
    {
        $this->name = $name;
        $this->job_title = $job_title;
        $this->department = $department;
        $this->years_experience = $years_experience;
        $this->skills = [];
        $this->skill_count = 0;
    }

    /**
     * Static factory method that creates a StaffMember with all four
     * details supplied at once.
     *
     * @param string $name             Full name of the staff member.
     * @param string $job_title        Current job title.
     * @param string $department       Department they work in.
     * @param int    $years_experience Years of industry experience.
     * @return static A new fully populated staff member instance.
     */
    public static function createFull(string $name, string $job_title, string $department, int $years_experience): static
    {
        return new static($name, $job_title, $department, $years_experience);
    }

    /**
     * Static factory method that creates a StaffMember using only a
     * name. The remaining fields are set to sensible defaults.
     *
     * @param string $name Full name of the staff member.
     * @return static A new staff member instance with default details.
     */
    public static function createWithNameOnly(string $name): static
    {
        return new static($name, "", "", 0);
    }

    /**
     * Adds a Skill object to this staff member's fixed-size skills
     * array, if there is still room (max MAX_SKILLS).
     *
     * @param Skill $skill The Skill object to add.
     * @return bool True if the skill was added, false if the array is full.
     */
    public function add_skill(Skill $skill): bool
    {
        if ($this->skill_count >= self::MAX_SKILLS) {
            return false;
        }

        // Store the skill in the next free slot and advance the counter.
        $this->skills[$this->skill_count] = $skill;
        $this->skill_count++;

        return true;
    }

    /**
     * Returns all Skill objects currently held by this staff member.
     *
     * @return Skill[] Array of Skill objects (up to $skill_count entries).
     */
    public function get_skills(): array
    {
        return $this->skills;
    }

    /**
     * Returns how many skills have been added so far.
     *
     * @return int The current skill count.
     */
    public function get_skill_count(): int
    {
        return $this->skill_count;
    }

    /**
     * Checks whether this staff member has a skill matching the given
     * name, using a case-insensitive comparison (R5 uses strcasecmp()).
     *
     * @param string $skill_name The skill name to search for.
     * @return bool True if a matching skill is found.
     */
    public function has_skill(string $skill_name): bool
    {
        for ($i = 0; $i < $this->skill_count; $i++) {
            if (strcasecmp($this->skills[$i]->get_skill_name(), $skill_name) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the staff member's full name.
     *
     * @return string The full name.
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * Returns the staff member's job title.
     *
     * @return string The job title.
     */
    public function get_job_title(): string
    {
        return $this->job_title;
    }

    /**
     * Returns the staff member's department.
     *
     * @return string The department.
     */
    public function get_department(): string
    {
        return $this->department;
    }

    /**
     * Returns the staff member's years of experience.
     *
     * @return int Years of experience.
     */
    public function get_years_experience(): int
    {
        return $this->years_experience;
    }

    /**
     * Returns the staff type label used when saving/loading data.
     * Overridden implicitly by subclasses via get_class(), but exposed
     * here so FileManager can call a single consistent method.
     *
     * @return string The staff type, e.g. "PermanentStaff".
     */
    public function get_staff_type(): string
    {
        return "StaffMember";
    }

    /**
     * Returns a formatted text description of this staff member.
     * PermanentStaff and ContractStaff override this method so that
     * calling the same method name on different object types produces
     * different output (polymorphism - R7).
     *
     * @return string Human-readable staff summary.
     */
    public function get_staff_summary(): string
    {
        return "{$this->name} - {$this->job_title}, {$this->department} "
            . "({$this->years_experience} yrs experience)";
    }

    /**
     * Returns a text representation of this staff member, including
     * their summary and a list of their skills.
     *
     * @return string Full text representation for display.
     */
    public function __toString(): string
    {
        $skill_list = [];
        for ($i = 0; $i < $this->skill_count; $i++) {
            $skill_list[] = (string) $this->skills[$i];
        }

        $skills_text = empty($skill_list) ? "No skills recorded" : implode(", ", $skill_list);

        return $this->get_staff_summary() . "\n    Skills: " . $skills_text;
    }
}
