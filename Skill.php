<?php

/**
 * File: Skill.php
 * Author: Phuwadon Khayanyiam
 * Date: 16 Aug 2026
 * Description: Represents a single skill (e.g. PHP, MySQL) that can be
 * associated with a StaffMember. Each Skill stores its name, category,
 * and proficiency level. Skill objects are aggregated inside a
 * StaffMember's $skills array (R2).
 */

class Skill
{
    private string $skill_name;
    private string $skill_category;
    private string $proficiency_level;

    /**
     * Creates a new Skill object.
     *
     * @param string $skill_name        Name of the skill, e.g. "PHP".
     * @param string $skill_category    Category, e.g. "Programming".
     * @param string $proficiency_level Beginner / Intermediate / Advanced.
     * @return void
     */
    public function __construct(string $skill_name, string $skill_category, string $proficiency_level)
    {
        $this->skill_name = $skill_name;
        $this->skill_category = $skill_category;
        $this->proficiency_level = $proficiency_level;
    }

    /**
     * Returns the skill name.
     *
     * @return string The skill name.
     */
    public function get_skill_name(): string
    {
        return $this->skill_name;
    }

    /**
     * Returns the skill category.
     *
     * @return string The skill category.
     */
    public function get_skill_category(): string
    {
        return $this->skill_category;
    }

    /**
     * Returns the proficiency level.
     *
     * @return string The proficiency level.
     */
    public function get_proficiency_level(): string
    {
        return $this->proficiency_level;
    }

    /**
     * Builds a short, readable text representation of the skill.
     * Used when displaying staff details on screen.
     *
     * @return string Formatted skill description.
     */
    public function __toString(): string
    {
        return "{$this->skill_name} ({$this->skill_category}, {$this->proficiency_level})";
    }
}
