<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseWorkspace;
use Illuminate\Database\Seeder;

class NegativeKeywordExclusionWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $course = $this->course();

        $workspace = CourseWorkspace::updateOrCreate(
            [
                'course_id' => $course->id,
                'title' => 'Negative Keyword Exclusion (Regex/Boolean)',
            ],
            [
                'track' => 'Talent Sourcing & Boolean Architecture',
                'headline' => 'Build a Boolean search string that targets real software engineers while strictly excluding recruiters, consultants, and agency staff.',
                'summary' => 'A search for "Software Engineer" alone returns a flood of recruiter profiles, staffing consultants, and agency employees who match the keyword but aren\'t candidates at all. In this workspace, students build a Boolean string that uses NOT / - exclusion operators to systematically remove this noise, test how aggressive exclusion can be before it starts filtering out real candidates, and package the result into a reusable exclusion list.',
                'progress' => 0,
                'next_milestone' => 'Step 1: Understand the Noise Problem',
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

        $this->command?->info('Seeded Negative Keyword Exclusion workspace for ' . $course->title . '.');
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
                'slug' => 'understand-noise-problem',
                'nav_label' => 'The Problem',
                'title' => 'Understand the Noise Problem in Boolean Search',
                'description' => 'See why plain keyword search returns the wrong people.',
                'status' => 'In Progress',
                'state' => 'active',
                'active' => true,
                'build_goal' => 'A documented count of how much noise a plain title search actually returns.',
                'why_text' => 'A search for "Software Engineer" alone returns recruiters, consultants, and agency staff who merely mention the title, wasting screening time.',
                'lesson' => 'Run a plain search for "Software Engineer" and review the first 20 results. Note how many are actual practicing engineers versus recruiters, staffing consultants, or agency employees.',
                'file_name' => 'noise_audit.md',
                'code_snippet' => "\"Software Engineer\"\n// Review first 20 results manually and tag each as Candidate or Noise",
                'expected_output' => 'A tally showing what fraction of a plain search is noise versus real candidates.',
                'preview_title' => 'Your audit should show a clear count of candidate versus noise profiles.',
                'task' => 'Review 20 results from an unfiltered title search and count how many are noise versus real candidates.',
                'hint' => 'Open each profile\'s headline, not just the title, to judge whether it\'s a real candidate.',
                'mentor_tip' => 'Seeing the actual scale of the noise problem makes every later exclusion step feel necessary, not academic.',
                'preview_points' => ['20 results reviewed', 'Each result tagged candidate or noise', 'Noise percentage calculated'],
                'mistakes' => ['Judging relevance from the title alone', 'Reviewing too few results to be meaningful', 'Skipping this step and assuming the noise level'],
                'tips' => ['Keep a simple two-column tally as you review.', 'Repeat this audit later to measure improvement.'],
            ],
            [
                'number' => 2,
                'slug' => 'learn-not-operator',
                'nav_label' => 'NOT Operator',
                'title' => 'Learn the NOT / - Exclusion Operator',
                'description' => 'Understand how exclusion terms remove unwanted profiles.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A working query demonstrating that a single NOT clause removes matching noise.',
                'why_text' => 'NOT (or a leading -) is the core mechanism that lets a Boolean string actively remove unwanted results, not just include wanted ones.',
                'lesson' => 'Boolean search engines support NOT (or a leading -) to remove any result containing a given term. Test a single exclusion term against your Step 1 search and confirm the flagged noise profiles disappear.',
                'file_name' => 'negative_keyword_string.txt',
                'code_snippet' => '"Software Engineer" NOT "Recruiter"',
                'expected_output' => 'Recruiter-titled profiles from Step 1 no longer appear in the result set.',
                'preview_title' => 'Your query should visibly remove recruiter profiles from the previous result set.',
                'task' => 'Add NOT "Recruiter" to the base query and confirm recruiter profiles drop out of the results.',
                'hint' => 'Compare the same result you flagged as noise in Step 1 and confirm it\'s gone.',
                'mentor_tip' => 'One working exclusion proves the mechanism before you scale it up.',
                'preview_points' => ['NOT clause added correctly', 'Previously flagged noise profile is gone', 'Remaining results still relevant'],
                'mistakes' => ['Using AND NOT instead of just NOT', 'Forgetting to quote the excluded term', 'Testing without a clear before/after comparison'],
                'tips' => ['A leading - works the same as NOT in most engines \u2014 pick one convention and stay consistent.', 'Always compare against your Step 1 baseline.'],
            ],
            [
                'number' => 3,
                'slug' => 'build-base-inclusion-query',
                'nav_label' => 'Base Query',
                'title' => 'Build the Base Inclusion Query',
                'description' => 'Write the core positive-match string before adding exclusions.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A stable inclusion query combining title and required skills with OR grouping.',
                'why_text' => 'Keeping the inclusion half stable and separate makes it easy to layer exclusions on top without breaking the core search.',
                'lesson' => 'Write the inclusion half of the query first: the job title and any required skills, grouped with OR where synonyms exist. Keep this half stable so exclusions can be layered on in later steps.',
                'file_name' => 'negative_keyword_string.txt',
                'code_snippet' => '("Software Engineer" OR "Backend Engineer") AND ("Python" OR "Java")',
                'expected_output' => 'A working inclusion-only query that returns a broad but relevant candidate pool.',
                'preview_title' => 'Your base query should return a wide, relevant pool before any exclusions are applied.',
                'task' => 'Build the base title-and-skills query using quoted terms and OR grouping.',
                'hint' => 'Resist the urge to add exclusions yet \u2014 get the inclusion logic solid first.',
                'mentor_tip' => 'A shaky inclusion query makes it impossible to tell if later problems come from exclusions or from the base search itself.',
                'preview_points' => ['Title terms grouped with OR', 'Skill terms grouped with OR', 'Groups combined correctly with AND'],
                'mistakes' => ['Combining too many unrelated OR groups', 'Missing parentheses around each OR group', 'Adding exclusions before the base query is validated'],
                'tips' => ['Test the base query alone before moving to Step 4.', 'Keep skill terms to the 2-3 most essential ones.'],
            ],
            [
                'number' => 4,
                'slug' => 'identify-common-noise-terms',
                'nav_label' => 'Noise Terms',
                'title' => 'Identify Common Noise Terms',
                'description' => 'Catalog the words that reliably signal a non-candidate profile.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A documented list of at least 6 recurring noise terms found in real profiles.',
                'why_text' => 'Exclusion terms should come from real observed noise, not guesswork, or they\'ll either miss real noise or filter out valid candidates.',
                'lesson' => 'Review a larger sample of noise profiles and list the recurring words in their titles and headlines: Recruiter, Talent Acquisition, Consultant, Staffing, Agency, Headhunter.',
                'file_name' => 'noise_term_list.md',
                'code_snippet' => "Noise terms observed:\n- Recruiter\n- Talent Acquisition\n- Consultant\n- Staffing\n- Agency\n- Headhunter",
                'expected_output' => 'A written list of at least 6 real, observed noise terms.',
                'preview_title' => 'Your list should be grounded in profiles you\'ve actually seen, not assumptions.',
                'task' => 'Write down at least 6 recurring noise terms found across sample profiles.',
                'hint' => 'Scan headlines specifically \u2014 that\'s where most noise-signaling language appears.',
                'mentor_tip' => 'A good exclusion list is a byproduct of careful observation, not a checklist copied from somewhere else.',
                'preview_points' => ['At least 6 terms listed', 'Terms are drawn from real profiles', 'Both single-word and multi-word terms included'],
                'mistakes' => ['Copying a generic exclusion list without verifying it applies', 'Listing terms too narrow to matter', 'Missing multi-word phrases like "Talent Acquisition"'],
                'tips' => ['Keep this list in its own file so it\'s reusable across projects.', 'Revisit and expand the list periodically.'],
            ],
            [
                'number' => 5,
                'slug' => 'add-single-term-exclusions',
                'nav_label' => 'Single Exclusions',
                'title' => 'Add Single-Term Exclusions',
                'description' => 'Attach your noise terms to the base query using NOT.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A query with each Step 4 noise term added as its own individually quoted NOT clause.',
                'why_text' => 'Each exclusion must be quoted separately or it silently fails to match the intended phrase.',
                'lesson' => 'Add each noise term from Step 4 as its own NOT clause. Confirm each exclusion is quoted individually, since an unquoted multi-word exclusion silently fails.',
                'file_name' => 'negative_keyword_string.txt',
                'code_snippet' => '("Software Engineer" OR "Backend Engineer") NOT "Recruiter" NOT "Consultant" NOT "Agency"',
                'expected_output' => 'The result set shrinks as each exclusion term removes matching noise profiles.',
                'preview_title' => 'Your query should show a single Boolean line with clearly separated inclusion and exclusion blocks.',
                'task' => 'Add individually quoted NOT clauses for each noise term identified in Step 4.',
                'hint' => 'Add one exclusion at a time and re-check the result count after each addition.',
                'mentor_tip' => 'Individually quoted exclusions are the difference between a string that works and one that silently doesn\'t.',
                'preview_points' => ['Inclusion terms grouped with OR', 'Every exclusion term individually quoted', 'Result count decreases as exclusions are added'],
                'mistakes' => ['Combining exclusions into one unquoted phrase', 'Excluding too aggressively in one step', 'Forgetting agency-adjacent words like "Staffing" or "Talent Partner"'],
                'tips' => ['Run the string once without exclusions to see what you\'re filtering out.', 'Keep a running exclusion list per role type.'],
            ],
            [
                'number' => 6,
                'slug' => 'handle-multiword-exclusions',
                'nav_label' => 'Phrase Exclusions',
                'title' => 'Handle Multi-Word Exclusion Phrases',
                'description' => 'Correctly exclude phrases, not just single words.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A query where every multi-word exclusion phrase is correctly quoted as a single unit.',
                'why_text' => 'An unquoted phrase splits into separate words, which can accidentally exclude valid candidates who use one of the words differently.',
                'lesson' => 'Terms like "Talent Acquisition" and "IT Staffing" are two-word phrases. Confirm your query wraps each phrase in its own quotation marks.',
                'file_name' => 'negative_keyword_string.txt',
                'code_snippet' => '("Software Engineer" OR "Backend Engineer") NOT "Talent Acquisition" NOT "IT Staffing"',
                'expected_output' => 'Multi-word exclusion phrases behave as single units, not as separate loose word exclusions.',
                'preview_title' => 'Your output should show every multi-word exclusion correctly wrapped in its own quotes.',
                'task' => 'Verify every multi-word exclusion is wrapped in its own quotation marks.',
                'hint' => 'Test one phrase unquoted versus quoted and compare how the result set differs.',
                'mentor_tip' => 'This is a small detail with an outsized effect on precision.',
                'preview_points' => ['Every multi-word phrase individually quoted', 'No stray unquoted exclusion terms', 'Result set does not lose valid candidates'],
                'mistakes' => ['Leaving a two-word phrase unquoted', 'Assuming NOT applies to the whole phrase without quotes', 'Not testing quoted versus unquoted side by side'],
                'tips' => ['When in doubt, quote it.', 'Test each multi-word exclusion in isolation first.'],
            ],
            [
                'number' => 7,
                'slug' => 'test-exclusion-impact',
                'nav_label' => 'Impact Testing',
                'title' => 'Test Exclusion Impact on Result Count',
                'description' => 'Measure how much each exclusion actually removes.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A before/after result-count log for each individual exclusion term.',
                'why_text' => 'Knowing which exclusions do real work versus which are redundant keeps the final query lean and maintainable.',
                'lesson' => 'Run the query with and without each individual exclusion term and record the change in result count. This tells you which exclusions matter and which are redundant.',
                'file_name' => 'exclusion_impact_log.md',
                'code_snippet' => "Exclusion       | Before | After | Impact\n\"Recruiter\"     | 420    | 310   | -110\n\"Consultant\"    | 310    | 295   | -15",
                'expected_output' => 'A log showing the measured impact of each exclusion term on result count.',
                'preview_title' => 'Your log should show a clear before/after count for each exclusion tested.',
                'task' => 'Toggle each exclusion on and off and log the resulting change in result count.',
                'hint' => 'Test exclusions one at a time, not all together, to isolate each one\'s effect.',
                'mentor_tip' => 'Data beats intuition here \u2014 some "obvious" exclusions barely matter, and some easy-to-miss ones matter a lot.',
                'preview_points' => ['Each exclusion tested independently', 'Before/after counts recorded', 'Low-impact exclusions identified'],
                'mistakes' => ['Testing all exclusions at once and losing the individual signal', 'Not recording exact counts', 'Assuming impact instead of measuring it'],
                'tips' => ['Keep the log in a simple table format.', 'Drop exclusions that show near-zero impact to keep the query lean.'],
            ],
            [
                'number' => 8,
                'slug' => 'avoid-over-exclusion',
                'nav_label' => 'False Negatives',
                'title' => 'Avoid Over-Exclusion (False Negatives)',
                'description' => 'Check that real candidates aren\'t being filtered out by mistake.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A reviewed sample of 10 excluded profiles confirming no valid candidates were lost.',
                'why_text' => 'Aggressive exclusions can remove legitimate candidates, such as someone who once worked as a technical recruiter before becoming an engineer.',
                'lesson' => 'Manually review a sample of excluded profiles to confirm you aren\'t losing valid candidates to an overly broad exclusion term.',
                'file_name' => 'false_negative_review.md',
                'code_snippet' => "Reviewed 10 excluded profiles:\n- 9 correctly excluded (genuine recruiters/agencies)\n- 1 false negative: former recruiter now working as an engineer",
                'expected_output' => 'A written confirmation of how many, if any, valid candidates were incorrectly excluded.',
                'preview_title' => 'Your review should clearly separate correct exclusions from false negatives.',
                'task' => 'Sample 10 profiles removed by exclusions and confirm none are valid candidates.',
                'hint' => 'Pay special attention to career-changers whose old titles might trigger an exclusion term.',
                'mentor_tip' => 'The best exclusion lists are the ones that have been stress-tested against real edge cases.',
                'preview_points' => ['10 excluded profiles reviewed', 'False negatives identified if any exist', 'Adjustment made if a valid candidate was excluded'],
                'mistakes' => ['Assuming all exclusions are correct without checking', 'Reviewing too small a sample', 'Leaving a known false negative unaddressed'],
                'tips' => ['Career-changers are the most common source of false negatives.', 'Loosen an exclusion term rather than deleting it if only one case fails.'],
            ],
            [
                'number' => 9,
                'slug' => 'build-reusable-exclusion-list',
                'nav_label' => 'Reusable List',
                'title' => 'Build a Reusable Exclusion List',
                'description' => 'Turn your tested exclusions into a standing list.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A single appendable NOT-clause block built from validated exclusion terms.',
                'why_text' => 'A consolidated, tested exclusion block saves rebuilding exclusion logic from scratch on every new search.',
                'lesson' => 'Consolidate the exclusion terms that proved effective in Steps 5-8 into a single reusable block that can be appended to any future title search.',
                'file_name' => 'reusable_exclusion_block.txt',
                'code_snippet' => 'NOT "Recruiter" NOT "Talent Acquisition" NOT "Consultant" NOT "Staffing" NOT "Agency" NOT "Headhunter"',
                'expected_output' => 'A standalone exclusion block ready to append to any role-specific inclusion query.',
                'preview_title' => 'Your block should be ready to paste onto the end of any future search string.',
                'task' => 'Compile the validated exclusion terms into one appendable NOT-clause block.',
                'hint' => 'Only include exclusions that showed real impact in Step 7 and passed the Step 8 false-negative check.',
                'mentor_tip' => 'This block is the actual deliverable \u2014 everything before this step was validation.',
                'preview_points' => ['Only validated exclusions included', 'Block is domain-independent (works for any role)', 'Formatted as a single appendable string'],
                'mistakes' => ['Including untested exclusions in the final block', 'Making the block role-specific instead of general', 'Skipping the impact and false-negative checks before finalizing'],
                'tips' => ['Store this block separately from any single search so it stays reusable.', 'Review and refresh the block periodically as new noise patterns appear.'],
            ],
            [
                'number' => 10,
                'slug' => 'document-and-package',
                'nav_label' => 'Documentation',
                'title' => 'Document and Package for Team Use',
                'description' => 'Prepare the exclusion list so others can use it without you.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A README and finished exclusion block saved together in a shared team location.',
                'why_text' => 'Documentation makes the exclusion list something the whole sourcing team can rely on, not just its original author.',
                'lesson' => 'Write a short README explaining what each exclusion term protects against and the false-negative risk found in Step 8. Save the final exclusion block and README together.',
                'file_name' => 'README.md',
                'code_snippet' => "# Negative Keyword Exclusion Block\n\n## Purpose\nRemoves recruiter, consultant, staffing, and agency noise from title searches.\n\n## Known Risk\nMay exclude candidates with a past recruiting title \u2014 spot-check results.",
                'expected_output' => 'A committed README and exclusion block ready for another recruiter to use without further explanation.',
                'preview_title' => 'Your submission should let a teammate use the block without asking you how it works.',
                'task' => 'Document each exclusion term\'s purpose and the false-negative check performed, then share the final exclusion block and README.',
                'hint' => 'Explicitly call out the Step 8 false-negative risk so future users know what to spot-check.',
                'mentor_tip' => 'A clear README turns a personal trick into institutional knowledge.',
                'preview_points' => ['Each exclusion term\'s purpose documented', 'False-negative risk clearly noted', 'Block and README saved to a shared location'],
                'mistakes' => ['Omitting the known false-negative risk', 'Leaving exclusion purposes unexplained', 'Saving the file somewhere only you can find it'],
                'tips' => ['Include the Step 7 impact data as supporting evidence in the README.', 'Save the file where the whole sourcing team has access.'],
            ],
        ];
    }

    protected function resources(): array
    {
        return [
            [
                'category' => 'Documentation',
                'label' => 'Boolean Operators Reference (AND / OR / NOT)',
                'icon' => 'fi fi-rr-document',
                'sort_order' => 1,
                'href' => 'https://support.google.com/websearch/answer/2466433',
                'description' => 'Reference for how AND, OR, and NOT operators combine in search queries, including quoting rules for exact phrases.',
            ],
            [
                'category' => 'Guidelines',
                'label' => 'Common Staffing & Agency Keyword Patterns',
                'icon' => 'fi fi-rr-shield',
                'sort_order' => 2,
                'href' => 'https://www.google.com/search?q=staffing+agency+recruiter+keyword+patterns+linkedin',
                'description' => 'Background reading on the common titles and headline phrases used by recruiters, consultants, and staffing agencies.',
            ],
            [
                'category' => 'Examples',
                'label' => 'Boolean Exclusion String Examples',
                'icon' => 'fi fi-rr-list',
                'sort_order' => 3,
                'href' => 'https://www.google.com/search?q=boolean+search+exclusion+string+examples+recruiting',
                'description' => 'Sample exclusion blocks used across different sourcing searches, useful as a starting reference before writing your own.',
            ],
        ];
    }

    protected function goals(): array
    {
        return [
            [
                'title' => 'Master Exclusion Logic',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Understand how NOT / - operators and correct phrase quoting work together to remove unwanted profile types from search results.',
            ],
            [
                'title' => 'Avoid Over-Filtering',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Learn to test exclusions against real results so aggressive filtering doesn\'t quietly remove valid candidates.',
            ],
            [
                'title' => 'Ship a Reusable Exclusion List',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Package validated exclusion terms into a documented, appendable block any recruiter on the team can reuse across searches.',
            ],
        ];
    }
}
