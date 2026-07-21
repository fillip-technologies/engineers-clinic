<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseWorkspace;
use Illuminate\Database\Seeder;

class AlternativeTitleSorterWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $course = $this->course();

        $workspace = CourseWorkspace::updateOrCreate(
            [
                'course_id' => $course->id,
                'title' => 'Alternative Title Sorter',
            ],
            [
                'track' => 'Talent Sourcing & Boolean Architecture',
                'headline' => 'Build a title dictionary that groups every real-world variant of a role into one standardized search query.',
                'summary' => 'Companies label the same job differently \u2014 SDR, BDR, Sales Rep, and Inside Sales are all the same role family, but a search for only one of these titles misses most of the actual candidate pool. In this workspace, students design a canonical role dictionary in JSON, write a function that turns a dictionary entry into a ready-to-use Boolean OR string, and validate that it returns more relevant candidates than a single-title search would.',
                'progress' => 0,
                'next_milestone' => 'Step 1: Understand Why Title Variance Breaks Sourcing',
                'status' => true,
            ]
        );

        foreach ($this->steps() as $step) {
            $workspace->steps()->updateOrCreate(
                ['slug' => $step['slug']],
                [
                    'step_no' => $step['number'],
                    'nav_label' => $step['nav_label'],
                    'title' => $step['title'],
                    'description' => $step['description'],
                    'status' => $step['status'],
                    'state' => $step['state'],
                    'active' => $step['active'],
                    'build_goal' => $step['build_goal'],
                    'why_text' => $step['why_text'],
                    'lesson' => $step['lesson'],
                    'file_name' => $step['file_name'],
                    'code_snippet' => $step['code_snippet'],
                    'expected_output' => $step['expected_output'],
                    'preview_title' => $step['preview_title'],
                    'task' => $step['task'],
                    'hint' => $step['hint'],
                    'mentor_tip' => $step['mentor_tip'],
                    'preview_points' => $step['preview_points'],
                    'mistakes' => $step['mistakes'],
                    'tips' => $step['tips'],
                    'sort_order' => $step['number'],
                ]
            );
        }

        foreach ($this->resources() as $resource) {
            $workspace->resources()->updateOrCreate(
                [
                    'category' => $resource['category'],
                    'label' => $resource['label'],
                ],
                [
                    'description' => $resource['description'],
                    'icon' => $resource['icon'],
                    'href' => $resource['href'],
                    'sort_order' => $resource['sort_order'],
                ]
            );
        }

        foreach ($this->goals() as $goal) {
            $workspace->goals()->updateOrCreate(
                ['title' => $goal['title']],
                [
                    'body' => $goal['body'],
                    'duration' => $goal['duration'],
                    'type' => $goal['type'],
                ]
            );
        }

        $this->command?->info('Seeded Alternative Title Sorter workspace for ' . $course->title . '.');
    }

    protected function course(): Course
    {
        return Course::query()
            ->where('title', 'HR Recruitment Basics & ATS Navigation')
            ->orWhereIn('slug', ['hr-recruitment-basics', 'hr-recruitment-ats-navigation'])
            ->orWhere('title', 'like', '%HR Recruitment%')
            ->first()
            ?? Course::firstOrCreate(
                ['slug' => 'hr-recruitment-basics-ats-navigation'],
                [
                    'title' => 'HR Recruitment Basics & ATS Navigation',
                    'description' => 'Practical HR recruitment and ATS engineering projects with guided workspaces.',
                    'level' => 'Beginner',
                    'category' => 'AI Remote Internships',
                    'duration_months' => 3,
                    'fee' => 0,
                ]
            );
    }

    protected function steps(): array
    {
        return [
            [
                'number' => 1,
                'slug' => 'understand-title-variance-problem',
                'nav_label' => 'The Problem',
                'title' => 'Understand Why Title Variance Breaks Sourcing',
                'description' => 'See how much of the candidate pool a single-title search misses.',
                'status' => 'In Progress',
                'state' => 'active',
                'active' => true,
                'build_goal' => 'A documented comparison showing how little two single-title searches for the same role overlap.',
                'why_text' => 'Sourcing on only one title variant silently excludes most of the real candidate pool for that role.',
                'lesson' => 'Search for just one title, e.g. "SDR", and separately search for "BDR". Compare the two result sets and note how many unique, relevant profiles appear in one search but not the other.',
                'file_name' => 'title_variance_audit.md',
                'code_snippet' => "Search A: \"SDR\" \u2192 240 results\nSearch B: \"BDR\" \u2192 190 results\nOverlap: ~15 profiles appear in both",
                'expected_output' => 'A written comparison showing the minimal overlap between two single-title searches.',
                'preview_title' => 'Your comparison should clearly show two search result sets with little overlap.',
                'task' => 'Run two single-title searches for the same role family and record how little the results overlap.',
                'hint' => 'Skim both result lists and manually count how many profiles appear in both.',
                'mentor_tip' => 'Seeing the real overlap number makes the case for a title dictionary far more convincing than any explanation.',
                'preview_points' => ['Two single-title searches run', 'Result counts recorded for each', 'Overlap between the two sets calculated'],
                'mistakes' => ['Choosing two titles that aren\'t actually synonyms', 'Not recording exact result counts', 'Assuming overlap without checking'],
                'tips' => ['Pick a role family you already know has multiple common titles.', 'Save this comparison to reference later as your "before" baseline.'],
            ],
            [
                'number' => 2,
                'slug' => 'audit-real-world-title-variants',
                'nav_label' => 'Variant Audit',
                'title' => 'Audit Real-World Title Variants',
                'description' => 'Collect the actual titles people use for one role family.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A list of at least 5 real, observed title variants for one target role family.',
                'why_text' => 'A dictionary is only as good as the real-world titles it\'s built from, not titles you assume people use.',
                'lesson' => 'Pick one role family, e.g. Sales Development, and manually collect at least 5 real title variants seen on actual profiles or job postings, rather than guessing what variants might exist.',
                'file_name' => 'title_variant_list.md',
                'code_snippet' => "Role family: Sales Development\nObserved variants:\n- SDR\n- BDR\n- Sales Development Rep\n- Sales Rep\n- Inside Sales",
                'expected_output' => 'A written list of at least 5 real title variants sourced from actual profiles or postings.',
                'preview_title' => 'Your list should show real variants, each traceable back to an actual profile or posting.',
                'task' => 'Collect at least 5 real-world title variants for one target role family.',
                'hint' => 'Search a few job boards or LinkedIn postings for the role and note every distinct title you see.',
                'mentor_tip' => 'A dictionary built from guessed variants will quietly miss the exact candidates it was meant to catch.',
                'preview_points' => ['At least 5 variants collected', 'Each variant traceable to a real source', 'Variants cover the same underlying role'],
                'mistakes' => ['Inventing plausible-sounding variants instead of observing real ones', 'Mixing variants from unrelated role families', 'Stopping at 2-3 variants instead of a fuller list'],
                'tips' => ['Job postings are often a faster source of variants than profiles.', 'Note the source next to each variant for future reference.'],
            ],
            [
                'number' => 3,
                'slug' => 'design-dictionary-structure',
                'nav_label' => 'Structure Design',
                'title' => 'Design the Canonical Role Dictionary Structure',
                'description' => 'Decide how canonical roles and their variants will be stored.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A documented key-value structure sketch for canonical roles and their variant arrays.',
                'why_text' => 'Deciding naming conventions before writing code keeps the dictionary consistent as more roles get added later.',
                'lesson' => 'Sketch a key-value structure where each canonical role name maps to an array of its known title variants. Decide on naming conventions for canonical keys before writing any code.',
                'file_name' => 'dictionary_structure_sketch.md',
                'code_snippet' => "canonical_role_key: [\"variant 1\", \"variant 2\", \"variant 3\"]\nExample: \"Sales Development\": [\"SDR\", \"BDR\", \"Sales Rep\"]",
                'expected_output' => 'A written sketch of the key-value shape to be implemented as JSON in Step 4.',
                'preview_title' => 'Your sketch should show the exact shape the JSON dictionary will follow.',
                'task' => 'Draft the key-value shape for canonical roles and their variant arrays.',
                'hint' => 'Decide now whether canonical keys will be Title Case, snake_case, or another convention, since this affects every role added later.',
                'mentor_tip' => 'Ten minutes of structure planning saves an hour of refactoring once the dictionary grows.',
                'preview_points' => ['Key-value shape clearly sketched', 'Canonical key naming convention decided', 'Structure supports multiple roles, not just one'],
                'mistakes' => ['Skipping the sketch and jumping straight to code', 'Choosing an inconsistent naming convention', 'Designing a structure that only works for one role'],
                'tips' => ['Write the sketch in plain text before touching JSON syntax.', 'Test the structure mentally against a second, different role family.'],
            ],
            [
                'number' => 4,
                'slug' => 'build-json-title-dictionary',
                'nav_label' => 'JSON Dictionary',
                'title' => 'Build the JSON Title Dictionary',
                'description' => 'Turn the sketch into working JSON.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A valid, parseable JSON file containing the canonical role and its variant array.',
                'why_text' => 'A working JSON file is the actual data source every later step depends on.',
                'lesson' => 'Write the actual JSON file with your canonical role as the key and the Step 2 variants as the array value. Validate the JSON parses correctly before moving on.',
                'file_name' => 'title_dictionary.json',
                'code_snippet' => "{\n  \"Sales Development\": [\"SDR\", \"BDR\", \"Sales Development Rep\", \"Sales Rep\", \"Inside Sales\"]\n}",
                'expected_output' => 'A JSON file that parses without errors and matches the Step 3 structure sketch.',
                'preview_title' => 'Your JSON file should parse cleanly and match the planned structure exactly.',
                'task' => 'Create the JSON file with the canonical role key and its title-variant array.',
                'hint' => 'Run the file through a JSON validator before moving to the next step.',
                'mentor_tip' => 'A malformed JSON file breaks silently downstream \u2014 validate it now, not later.',
                'preview_points' => ['File parses as valid JSON', 'Canonical key matches Step 3 convention', 'All Step 2 variants included in the array'],
                'mistakes' => ['Trailing commas breaking JSON validity', 'Mismatched quotes around keys or values', 'Forgetting a variant collected in Step 2'],
                'tips' => ['Use a JSON linter or online validator before trusting the file.', 'Keep the file name descriptive, like title_dictionary.json.'],
            ],
            [
                'number' => 5,
                'slug' => 'write-or-string-generator',
                'nav_label' => 'Generator Function',
                'title' => 'Write the OR-String Generator Function',
                'description' => 'Turn a dictionary entry into a ready-to-search Boolean string.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A function that accepts a canonical role name and returns a properly quoted, OR-joined Boolean string.',
                'why_text' => 'Generating the string from the dictionary means it never has to be typed out by hand and stays in sync with the source data.',
                'lesson' => 'Write a function that accepts a canonical role name, looks it up in the dictionary, and returns a properly quoted, OR-joined Boolean string built from its variants.',
                'file_name' => 'title_string_generator.py',
                'code_snippet' => "import json\n\ndef generate_or_string(canonical_role, dictionary_path='title_dictionary.json'):\n    with open(dictionary_path) as f:\n        dictionary = json.load(f)\n    variants = dictionary.get(canonical_role, [])\n    quoted = [f'\"{v}\"' for v in variants]\n    return '(' + ' OR '.join(quoted) + ')'",
                'expected_output' => 'Calling the function with a canonical role name returns a correctly formatted Boolean OR string.',
                'preview_title' => 'Your function should output a ready-to-paste Boolean string from a single role name input.',
                'task' => 'Create a function that converts a dictionary entry into a quoted OR-string.',
                'hint' => 'Handle the case where the canonical role isn\'t found in the dictionary gracefully.',
                'mentor_tip' => 'This function is what turns the dictionary from a reference document into an actual tool.',
                'preview_points' => ['Function accepts a canonical role name', 'Output is correctly quoted and OR-joined', 'Missing roles handled without crashing'],
                'mistakes' => ['Forgetting to quote each variant individually', 'Not wrapping the OR group in parentheses', 'Crashing on an unknown canonical role instead of handling it gracefully'],
                'tips' => ['Test the function against the exact role name used in Step 4.', 'Return an empty string or clear message for unknown roles.'],
            ],
            [
                'number' => 6,
                'slug' => 'test-generated-strings-live',
                'nav_label' => 'Live Testing',
                'title' => 'Test Generated Strings Against Live Search',
                'description' => 'Confirm the generated string actually returns more candidates.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A documented comparison showing the generated string outperforms the Step 1 single-title searches.',
                'why_text' => 'A generator that works in theory still needs to prove it returns real, additional relevant candidates.',
                'lesson' => 'Run the generated OR-string in a live search and compare the result count and relevance against the single-title searches from Step 1. Confirm the combined string captures candidates the single searches missed.',
                'file_name' => 'live_test_results.md',
                'code_snippet' => "Generated string result count: 410\nStep 1 \"SDR\" only: 240\nStep 1 \"BDR\" only: 190\nNet new candidates captured: ~170",
                'expected_output' => 'A comparison confirming the generated string returns more unique relevant candidates than either single-title search.',
                'preview_title' => 'Your comparison should show the generated string clearly outperforming any single-title search.',
                'task' => 'Run the generated string live and confirm it returns more relevant candidates than any single-title search.',
                'hint' => 'Reuse the exact Step 1 searches as your comparison baseline.',
                'mentor_tip' => 'This is the step that proves the whole project was worth building.',
                'preview_points' => ['Generated string run live', 'Result count compared against Step 1 baseline', 'Net new relevant candidates identified'],
                'mistakes' => ['Comparing against a different baseline than Step 1', 'Not checking relevance, only result count', 'Skipping this validation and assuming the generator works'],
                'tips' => ['Spot-check a handful of the "new" results for genuine relevance.', 'Keep this comparison as evidence for Step 10\'s documentation.'],
            ],
            [
                'number' => 7,
                'slug' => 'handle-overlapping-titles',
                'nav_label' => 'Ambiguity Rule',
                'title' => 'Handle Overlapping Titles Across Role Families',
                'description' => 'Resolve titles that could belong to more than one canonical role.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A documented rule for resolving titles that could map to more than one canonical role.',
                'why_text' => 'Without a resolution rule, ambiguous titles like "Sales Rep" can produce conflicting or duplicated search strings across role families.',
                'lesson' => 'Some titles could plausibly belong to more than one role family depending on context. Decide and document a rule for which canonical key claims an ambiguous title.',
                'file_name' => 'ambiguity_rule.md',
                'code_snippet' => "Rule: \"Sales Rep\" defaults to \"Sales Development\" unless paired with\n\"Account Executive\" context clues, which route it to \"Account Management\".",
                'expected_output' => 'A written rule that resolves at least one identified ambiguous title case.',
                'preview_title' => 'Your rule should clearly resolve a real ambiguous-title case you\'ve identified.',
                'task' => 'Write a rule for resolving titles that could map to more than one canonical role.',
                'hint' => 'Look for a title from your Step 2 list that could plausibly belong elsewhere too.',
                'mentor_tip' => 'Ambiguity is inevitable once you have more than one role family \u2014 the rule just needs to be consistent, not perfect.',
                'preview_points' => ['At least one ambiguous title identified', 'A clear resolution rule documented', 'Rule is consistent and repeatable'],
                'mistakes' => ['Ignoring ambiguity until it causes a real conflict', 'Writing a rule too vague to apply consistently', 'Assigning the same title to two canonical roles at once'],
                'tips' => ['Document the rule right next to the dictionary file for future reference.', 'Revisit this rule whenever a new role family is added.'],
            ],
            [
                'number' => 8,
                'slug' => 'add-variants-without-breaking',
                'nav_label' => 'Extensibility',
                'title' => 'Add New Variants Without Breaking the Structure',
                'description' => 'Confirm the dictionary is safe to extend.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A confirmed test showing a newly added variant is picked up without any generator code changes.',
                'why_text' => 'A dictionary that requires code changes every time a variant is added isn\'t actually maintainable long-term.',
                'lesson' => 'Add one new title variant to an existing canonical role and re-run the generator function. Confirm the new variant is picked up automatically without any changes to the generator code.',
                'file_name' => 'title_dictionary.json',
                'code_snippet' => "// Before: \"Sales Development\": [\"SDR\", \"BDR\", \"Sales Rep\"]\n// After:  \"Sales Development\": [\"SDR\", \"BDR\", \"Sales Rep\", \"Business Development Associate\"]",
                'expected_output' => 'The generator function output includes the new variant with zero code changes required.',
                'preview_title' => 'Your test should confirm the generator adapts automatically to a dictionary-only change.',
                'task' => 'Add a new variant to the dictionary and confirm the generator picks it up without code changes.',
                'hint' => 'If the generator needs a code change to pick up the new variant, the Step 5 function needs to be revisited.',
                'mentor_tip' => 'This test is what separates a real tool from a one-off script.',
                'preview_points' => ['New variant added to JSON only', 'Generator function untouched', 'New variant appears in generated output'],
                'mistakes' => ['Editing the generator function instead of just the dictionary', 'Not re-testing after the addition', 'Adding the variant to the wrong canonical role'],
                'tips' => ['Treat any need to touch generator code as a signal the design needs fixing.', 'Re-run Step 6\'s live test after significant dictionary changes.'],
            ],
            [
                'number' => 9,
                'slug' => 'validate-coverage-missed-candidates',
                'nav_label' => 'Coverage Check',
                'title' => 'Validate Coverage Against Missed Candidates',
                'description' => 'Check for real candidates the dictionary still misses.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A confirmed cross-check of 10-15 known-good candidates against the dictionary\'s variant list.',
                'why_text' => 'The only real test of dictionary completeness is whether it actually covers real candidates you already know exist.',
                'lesson' => 'Manually review 10-15 known-good candidate profiles in the target role family and confirm each one\'s actual title appears somewhere in your dictionary\'s variant list. Add any missing variants you find.',
                'file_name' => 'coverage_validation.md',
                'code_snippet' => "Checked 12 known candidates:\n- 11 titles matched an existing variant\n- 1 new variant found: \"Business Development Associate\" (added to dictionary)",
                'expected_output' => 'A written confirmation of dictionary coverage against real known candidates, with any gaps closed.',
                'preview_title' => 'Your check should confirm real candidates are covered, with any gaps explicitly closed.',
                'task' => 'Confirm each sampled candidate\'s real title exists in the dictionary, adding any that are missing.',
                'hint' => 'Pull your known-good candidates from people you\'ve actually placed or sourced before, not random profiles.',
                'mentor_tip' => 'This step catches the blind spots that Step 2\'s initial audit couldn\'t have known about.',
                'preview_points' => ['10-15 known candidates checked', 'Each title cross-referenced against the dictionary', 'Missing variants added where found'],
                'mistakes' => ['Using unverified candidates instead of known-good ones', 'Skipping the addition of newly discovered variants', 'Treating this as optional rather than a real coverage check'],
                'tips' => ['Reuse candidates from past successful searches if available.', 'Repeat this check whenever the dictionary is reused for a new search cycle.'],
            ],
            [
                'number' => 10,
                'slug' => 'document-and-package',
                'nav_label' => 'Documentation',
                'title' => 'Document and Package for Team Use',
                'description' => 'Prepare the dictionary and generator so others can use it without you.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A README, dictionary file, and generator function saved together in a shared team location.',
                'why_text' => 'Documentation is what lets the rest of the sourcing team extend and use the dictionary without needing the original builder.',
                'lesson' => 'Write a short README covering how to add new roles and variants, how the generator function works, and the ambiguity rule from Step 7. Save the dictionary file, generator function, and README together.',
                'file_name' => 'README.md',
                'code_snippet' => "# Alternative Title Sorter\n\n## Usage\ngenerate_or_string(\"Sales Development\")\n\n## Adding a Role\nAdd a new key to title_dictionary.json with an array of variants.\n\n## Ambiguity Rule\nSee ambiguity_rule.md for how overlapping titles are resolved.",
                'expected_output' => 'A committed README, dictionary, and generator ready for another recruiter to extend without further explanation.',
                'preview_title' => 'Your submission should let a teammate add a new role without asking you how the system works.',
                'task' => 'Document how to extend the dictionary and how the generator function works, then share the final dictionary, generator function, and README.',
                'hint' => 'Include a worked example of adding a brand-new role family, not just a new variant.',
                'mentor_tip' => 'A dictionary nobody else can extend eventually becomes dead weight \u2014 good docs keep it alive.',
                'preview_points' => ['README explains how to add new roles', 'Generator usage documented with an example', 'Ambiguity rule referenced or included'],
                'mistakes' => ['Leaving the extension process unexplained', 'Omitting the ambiguity rule from the docs', 'Saving files somewhere only you can find them'],
                'tips' => ['Include one full worked example in the README.', 'Save the file where the whole sourcing team has access.'],
            ],
        ];
    }

    protected function resources(): array
    {
        return [
            [
                'category' => 'Documentation',
                'label' => 'JSON Structure Basics',
                'icon' => 'fi fi-rr-document',
                'sort_order' => 1,
                'href' => 'https://www.json.org/json-en.html',
                'description' => 'Reference for valid JSON key-value and array structures, used to build the title dictionary correctly.',
            ],
            [
                'category' => 'Guidelines',
                'label' => 'Job Title Taxonomy Standards',
                'icon' => 'fi fi-rr-shield',
                'sort_order' => 2,
                'href' => 'https://www.google.com/search?q=job+title+taxonomy+standard+list',
                'description' => 'Background reading on how standardized job title taxonomies group role variants across companies and industries.',
            ],
            [
                'category' => 'Examples',
                'label' => 'Sourcing Title Dictionary Examples',
                'icon' => 'fi fi-rr-list',
                'sort_order' => 3,
                'href' => 'https://www.google.com/search?q=recruiting+job+title+synonym+dictionary+examples',
                'description' => 'Sample title-variant dictionaries from other sourcing workflows, useful as a reference before building your own.',
            ],
        ];
    }

    protected function goals(): array
    {
        return [
            [
                'title' => 'Master Title-Variant Mapping',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Understand how real-world job titles diverge for the same role, and how a canonical dictionary structure captures that variance.',
            ],
            [
                'title' => 'Build a Maintainable Dictionary',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Design a JSON structure that can absorb new title variants over time without requiring changes to the search-generation logic.',
            ],
            [
                'title' => 'Ship a Reusable Generator',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Package the dictionary and OR-string generator into a documented tool any recruiter on the team can use for any role family.',
            ],
        ];
    }
}
