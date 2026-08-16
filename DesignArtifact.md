# DESIGN ARTIFACT - NovaSoft Staff Skills Tracker (PHP)

**Option Selected:** Option B - Annotated Pseudocode

## CLASS StaffMember

**PROPERTIES:**
- `protected string $name`
- `protected string $job_title`
- `protected string $department`
- `protected int $years_experience`
- `private array $skills` — fixed-size array, max 10 `Skill` objects
- `private int $skill_count` — number of skill slots currently filled

**FACTORY METHODS (static):**
- `createFull(name, job_title, department, years_experience) : static`
  — creates a StaffMember with all four properties set.
- `createWithNameOnly(name) : static`
  — creates a StaffMember with only the name set; other fields default
  to empty string / 0.

**METHODS:**
- `add_skill(Skill $skill) : bool` — adds a Skill to `$skills` if there
  is room (max 10), increments `$skill_count`.
- `has_skill(skill_name) : bool` — loops through `$skills` using
  `strcasecmp()` for case-insensitive matching.
- `get_staff_summary() : string` — returns a formatted summary;
  overridden in subclasses (polymorphism).
- `__toString() : string` — returns full text representation including
  skills.

**RELATIONSHIPS:**
- `PermanentStaff extends StaffMember`
- `ContractStaff extends StaffMember`
- `StaffMember HAS-A array of Skill objects ($skills)` — aggregation

## CLASS Skill

**PROPERTIES:**
- `private string $skill_name`
- `private string $skill_category`
- `private string $proficiency_level`

**METHODS:**
- `__construct(skill_name, skill_category, proficiency_level)`
- `get_skill_name() : string`
- `get_skill_category() : string`
- `get_proficiency_level() : string`

## CLASS PermanentStaff

**INHERITS FROM:** StaffMember

**METHOD:**
- `get_staff_summary() : string` — overrides parent; returns a summary
  identifying the staff member as permanent.

## CLASS ContractStaff

**INHERITS FROM:** StaffMember

**PROPERTY:**
- `private string $contract_end_date`

**METHODS:**
- `createFullContract(name, job_title, department, years_experience,
  contract_end_date) : ContractStaff` — static factory including the
  contract end date.
- `get_staff_summary() : string` — overrides parent; returns a summary
  identifying the staff member as contract and includes the contract
  end date.

## CLASS FileManager

**METHODS:**
- `save_to_file(staff_list, file_path) : bool` — opens `staff_data.txt`
  for writing with `fopen()`, writes one STAFF line and one SKILL line
  per skill using `fwrite()`, then `fclose()`.
- `load_from_file(file_path) : array` — opens `staff_data.txt` for
  reading with `fopen()`, reads each line with `fgets()`, reconstructs
  `StaffMember`/`ContractStaff` and `Skill` objects, then `fclose()`.
  Returns the rebuilt staff list.

## CLASS StaffTrackerApp

**PROPERTIES:**
- `private array $staff_list`
- `private bool $running`
- `private FileManager $file_manager`

**METHODS:**
- `run() : void` — loads existing data, then loops (`while ($running)`)
  displaying the menu and processing the user's choice via a
  `switch` statement until option 7 is chosen.
- `display_menu() : void` — prints options 1-7.
- `add_staff() : void` — collects details via `fgets(STDIN)` in
  sequence; creates a `PermanentStaff` or `ContractStaff`.
- `add_skill() : void` — loops through `$staff_list` to find a staff
  member by name, then adds a new `Skill`.
- `search_by_skill() : void` — loops through `$staff_list`, using
  `strcasecmp()` (via `has_skill()`) to find matches.
- `display_all_staff() : void` — prints every staff member's summary
  and skills.
- `save_data() : void` — calls `FileManager::save_to_file()`.
- `load_data() : void` — calls `FileManager::load_from_file()`.
