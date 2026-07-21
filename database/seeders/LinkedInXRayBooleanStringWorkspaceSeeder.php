<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseWorkspace;
use Illuminate\Database\Seeder;

class LinkedInXRayBooleanStringWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $course = $this->course();

        $workspace = CourseWorkspace::updateOrCreate(
            [
                'course_id' => $course->id,
                'title' => 'LinkedIn X-Ray Boolean String',
            ],
            [
                'track' => 'Talent Sourcing & Boolean Architecture',
                'headline' => 'Build a Google X-Ray search system that finds real LinkedIn profiles without touching LinkedIn\'s own search bar.',
                'summary' => 'Most free and even paid LinkedIn search tiers cap how many results a recruiter can see per search, and Recruiter licenses are expensive. In this workspace, students build a reusable X-Ray search system that uses Google\'s own index of public LinkedIn profiles to find candidates by title, location, and keyword, with no LinkedIn login and no monthly seat cost.',
                'progress' => 0,
                'next_milestone' => 'Step 1: Understand the X-Ray Search Problem',
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

        $this->command?->info('Seeded LinkedIn X-Ray Boolean String workspace for ' . $course->title . '.');
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
                'slug' => 'understand-xray-search-problem',
                'nav_label' => 'The Problem',
                'title' => 'Understand the X-Ray Search Problem',
                'description' => 'Learn why X-Ray search exists and what it replaces.',
                'status' => 'In Progress',
                'state' => 'active',
                'active' => true,
                'build_goal' => 'A short written brief explaining why X-Ray search exists and who on a recruiting team uses it.',
                'why_text' => 'Recruiter-Lite seats are expensive and free LinkedIn search caps results; X-Ray search sidesteps both limits using Google\'s own public index.',
                'lesson' => 'Research how LinkedIn\'s native search limits free and Recruiter-Lite accounts, and why recruiters use Google to search LinkedIn\'s publicly indexed profile pages instead. Write a short brief explaining the problem this workspace solves and who would use it.',
                'file_name' => 'brief.md',
                'code_snippet' => "# X-Ray Search Brief\n\nProblem: LinkedIn search caps results and Recruiter seats are costly.\nSolution: Use Google's site: operator to search LinkedIn's public /in/ index directly.\nAudience: Sourcers and recruiters without a Recruiter license.",
                'expected_output' => 'A short brief exists explaining the sourcing problem and who benefits from solving it.',
                'preview_title' => 'Your brief should clearly state the problem and who benefits from solving it.',
                'task' => 'Summarize in 3-4 sentences why X-Ray search is needed and who uses it.',
                'hint' => 'Compare the cost of a Recruiter-Lite seat against the cost of a free Google search.',
                'mentor_tip' => 'Understanding the "why" first keeps every later step purposeful.',
                'preview_points' => ['Problem is clearly stated', 'Target audience is named', 'Written in plain language'],
                'mistakes' => ['Jumping straight to code before defining the problem', 'Writing a brief with no clear audience', 'Confusing X-Ray search with LinkedIn Recruiter itself'],
                'tips' => ['Keep the brief under one paragraph.', 'Ground the brief in a real limitation you\'ve personally hit.'],
            ],
            [
                'number' => 2,
                'slug' => 'learn-site-operator',
                'nav_label' => 'Site Operator',
                'title' => 'Learn the site: Operator',
                'description' => 'Restrict Google\'s search to one domain and one URL path.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A working Google query that returns only linkedin.com/in profile URLs.',
                'why_text' => 'The site: operator plus a /in/ path filter automatically excludes job ads and company pages, the two biggest sources of noise.',
                'lesson' => 'The site: operator tells Google to only return results from a specific domain. For LinkedIn profile search, restrict further to the /in/ path, which is the public profile URL pattern.',
                'file_name' => 'xray_search_builder.txt',
                'code_snippet' => 'site:linkedin.com/in',
                'expected_output' => 'Running the query in Google returns only /in/ profile-style URLs.',
                'preview_title' => 'Your query should return real profile links, with no job or company pages mixed in.',
                'task' => 'Run site:linkedin.com/in in Google and confirm only profile-style URLs appear.',
                'hint' => 'Check the actual URL of each result, not just the title text.',
                'mentor_tip' => 'This one operator does most of the filtering work before you add anything else.',
                'preview_points' => ['Only linkedin.com results appear', 'URLs contain /in/', 'No job or company pages present'],
                'mistakes' => ['Using linkedin.com without the /in/ path', 'Forgetting the colon after site', 'Testing in a logged-in browser tab that skews results'],
                'tips' => ['Test in an incognito window for unbiased results.', 'Bookmark this base query as your starting template.'],
            ],
            [
                'number' => 3,
                'slug' => 'add-quoted-title-terms',
                'nav_label' => 'Title Terms',
                'title' => 'Add Quoted Job Title Terms',
                'description' => 'Layer in the job title using exact-match quotes.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A query combining the site: filter with a correctly quoted job title.',
                'why_text' => 'Unquoted multi-word titles get split into separate, unordered terms, which returns far less precise matches.',
                'lesson' => 'Multi-word job titles must be wrapped in quotation marks or Google treats each word as a separate term. Add a quoted title alongside the site: operator and confirm the result set narrows correctly.',
                'file_name' => 'xray_search_builder.txt',
                'code_snippet' => 'site:linkedin.com/in "Java Developer"',
                'expected_output' => 'Results narrow to profiles whose text includes the exact phrase "Java Developer".',
                'preview_title' => 'Your query should return a visibly smaller, more relevant result set than Step 2.',
                'task' => 'Add a properly quoted job title to the base site: query.',
                'hint' => 'Compare result counts with and without the quotes to see the difference quoting makes.',
                'mentor_tip' => 'Quoting is the single most common mistake in Boolean strings; get it right early.',
                'preview_points' => ['Title phrase is wrapped in quotes', 'Result count drops versus the unfiltered query', 'Sampled results mention the exact phrase'],
                'mistakes' => ['Leaving the title unquoted', 'Using curly quotes instead of straight quotes', 'Choosing an overly generic title with no real filtering effect'],
                'tips' => ['Straight double quotes only \u2014 curly quotes break the operator.', 'Test with a title you already know real candidates use.'],
            ],
            [
                'number' => 4,
                'slug' => 'add-location-filtering',
                'nav_label' => 'Location Filter',
                'title' => 'Add Location Filtering',
                'description' => 'Narrow results to a specific city or region.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A query that adds a quoted location string to the title-filtered search.',
                'why_text' => 'LinkedIn profiles store location text inconsistently, so testing both city-only and city-plus-state formats matters.',
                'lesson' => 'Add a quoted location string, e.g. "Austin, Texas", to the query. Test both the city-only and city-plus-state formats, and note which format returns more matches.',
                'file_name' => 'xray_search_builder.txt',
                'code_snippet' => 'site:linkedin.com/in "Java Developer" "Austin, Texas"',
                'expected_output' => 'Results are filtered to profiles mentioning both the title and the location phrase.',
                'preview_title' => 'Your query should return fewer, more geographically relevant profiles.',
                'task' => 'Run the query with city-only and city+state location strings and compare result counts.',
                'hint' => 'Some profiles list only a metro area name, so try that variant too if results seem sparse.',
                'mentor_tip' => 'Location formatting inconsistency is one of the most common silent result-killers.',
                'preview_points' => ['Location phrase is quoted correctly', 'City-only and city+state variants both tested', 'Result counts recorded for comparison'],
                'mistakes' => ['Assuming one location format works for every candidate', 'Forgetting the comma inside the quoted location', 'Using an overly narrow location that excludes valid nearby candidates'],
                'tips' => ['Try the metro-area name as a third variant if results are thin.', 'Keep a small table of format vs. result count for reference.'],
            ],
            [
                'number' => 5,
                'slug' => 'combine-title-variants-or',
                'nav_label' => 'OR Grouping',
                'title' => 'Combine Title Variants with OR Logic',
                'description' => 'Group synonymous job titles into one query.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A query that groups at least three title variants with OR inside parentheses.',
                'why_text' => 'Real candidates use different titles for the same role, so a single-title search misses most of the pool.',
                'lesson' => 'Group synonymous titles (Java Developer, Java Engineer, Backend Java Developer) with OR inside parentheses so the query returns any matching title in a single search.',
                'file_name' => 'xray_search_builder.txt',
                'code_snippet' => 'site:linkedin.com/in ("Java Developer" OR "Java Engineer" OR "Backend Java Developer") "Austin, Texas"',
                'expected_output' => 'The result set grows compared to the single-title query while staying relevant.',
                'preview_title' => 'Your query should return more relevant candidates than any single title alone.',
                'task' => 'Combine at least three title variants using OR inside parentheses.',
                'hint' => 'Keep the OR group wrapped in parentheses so it doesn\'t interfere with the rest of the query.',
                'mentor_tip' => 'This is the step that actually widens your candidate pool, not just filters it.',
                'preview_points' => ['At least 3 title variants included', 'OR group wrapped in parentheses', 'Result count increases versus Step 4'],
                'mistakes' => ['Forgetting the parentheses around the OR group', 'Mixing OR and AND without grouping correctly', 'Adding near-duplicate variants that add no real coverage'],
                'tips' => ['Pull variants from real profiles, not guesses.', 'Cap the group at 4-5 variants to stay readable.'],
            ],
            [
                'number' => 6,
                'slug' => 'exclude-noise-intitle-inurl',
                'nav_label' => 'Exclusions',
                'title' => 'Exclude Noise with intitle: and inurl:',
                'description' => 'Filter out job ads, company pages, and directory listings that slip through.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A query that removes remaining noise pages using intitle: and inurl: exclusions.',
                'why_text' => 'Even with site:linkedin.com/in, some job postings and aggregator pages still appear in results.',
                'lesson' => 'Use -intitle:"profiles" and -inurl:"jobs" to push noise pages out of the result set, and confirm the remaining results are individual candidate profiles.',
                'file_name' => 'xray_search_builder.txt',
                'code_snippet' => 'site:linkedin.com/in ("Java Developer" OR "Java Engineer") "Austin, Texas" -intitle:"profiles" -inurl:"jobs"',
                'expected_output' => 'The result set no longer contains job postings or aggregator/profile-directory pages.',
                'preview_title' => 'Your output should be a single clean search string with no broken quotation marks.',
                'task' => 'Add -intitle and -inurl exclusions and confirm noise pages disappear from results.',
                'hint' => 'Look at what noise remains after Step 5 before deciding which exclusion terms to add.',
                'mentor_tip' => 'Exclusions should be added in response to real noise you\'ve seen, not preemptively guessed.',
                'preview_points' => ['Returns only /in/ profile URLs', 'Excludes job postings and directory pages', 'Title and location remain correctly quoted'],
                'mistakes' => ['Forgetting to exclude /jobs/ and directory pages', 'Using unquoted multi-word exclusion phrases', 'Over-excluding until valid profiles disappear too'],
                'tips' => ['Test the string in an incognito window so cached results don\'t skew what you see.', 'Add one exclusion at a time and recheck results.'],
            ],
            [
                'number' => 7,
                'slug' => 'test-query-length-limits',
                'nav_label' => 'Length Limits',
                'title' => 'Test Google\'s Query Length Limits',
                'description' => 'Find where Google starts silently dropping terms.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A documented safe maximum length for the query string before Google truncates it.',
                'why_text' => 'Google truncates very long queries without warning, which silently breaks exclusions or OR groups.',
                'lesson' => 'Gradually add more OR terms and exclusions to your string, then check the actual search Google ran, visible at the bottom of the results page, to confirm all terms are still being applied.',
                'file_name' => 'xray_search_builder.txt',
                'code_snippet' => "// Keep the full query under ~32 words.\n// Check Google's \"Showing results for...\" footer to confirm no terms were dropped.",
                'expected_output' => 'A documented word-count threshold beyond which Google starts dropping terms.',
                'preview_title' => 'Your output should note the safe maximum length you tested for this query.',
                'task' => 'Add terms until Google drops one, then note the safe maximum length.',
                'hint' => 'Scroll to the bottom of the Google results page to see which terms Google actually used.',
                'mentor_tip' => 'X-Ray strings break silently \u2014 always verify in a live browser before calling a string "done."',
                'preview_points' => ['Query tested at increasing lengths', 'Google\'s applied-terms footer checked', 'Safe maximum length documented'],
                'mistakes' => ['Assuming Google applies every term with no limit', 'Not checking the applied-search footer', 'Adding terms indefinitely without testing'],
                'tips' => ['Keep the string under roughly 32 words as a starting rule of thumb.', 'Re-test the length limit periodically, since it can change.'],
            ],
            [
                'number' => 8,
                'slug' => 'build-reusable-query-template',
                'nav_label' => 'Template',
                'title' => 'Build a Reusable Query Template',
                'description' => 'Turn the working string into a fill-in-the-blank template.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A placeholder version of the query using {TITLE_GROUP} and {LOCATION} variables.',
                'why_text' => 'A reusable template lets any recruiter on the team generate a new search without rebuilding the query structure.',
                'lesson' => 'Replace the specific title and location in your working string with placeholders like {TITLE_GROUP} and {LOCATION}, so new values can be dropped in without touching the rest of the structure.',
                'file_name' => 'xray_template.txt',
                'code_snippet' => 'site:linkedin.com/in ({TITLE_GROUP}) "{LOCATION}" -intitle:"profiles" -inurl:"jobs"',
                'expected_output' => 'A single template string with clearly marked placeholders that produces a working query when filled in.',
                'preview_title' => 'Your template should require only the title group and location to generate a new search.',
                'task' => 'Create a placeholder version of the query with {TITLE_GROUP} and {LOCATION} variables.',
                'hint' => 'Keep the exclusion terms fixed in the template since they rarely change between searches.',
                'mentor_tip' => 'A good template turns a one-time success into a repeatable team asset.',
                'preview_points' => ['Placeholders are clearly marked', 'Exclusion terms remain fixed in the template', 'Filling in placeholders produces a valid query'],
                'mistakes' => ['Leaving real values instead of placeholders', 'Making the template too rigid to adapt to new roles', 'Forgetting to keep the exclusion logic intact'],
                'tips' => ['Test the template by filling in a completely different role.', 'Keep placeholder names consistent and self-explanatory.'],
            ],
            [
                'number' => 9,
                'slug' => 'validate-against-real-candidates',
                'nav_label' => 'Validation',
                'title' => 'Validate Against Real Candidates',
                'description' => 'Confirm the string returns genuinely relevant, reachable profiles.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A recorded precision score from manually reviewing 20 live results.',
                'why_text' => 'A template is only useful if it reliably returns real, relevant candidates, not just plausible-looking noise.',
                'lesson' => 'Run the finished template with real values for a role you\'re sourcing for. Manually review the first 20 results and record how many are genuinely relevant matches versus false positives.',
                'file_name' => 'validation_notes.md',
                'code_snippet' => "Precision = (Relevant Profiles / 20) x 100\nExample: 16 relevant / 20 reviewed = 80% precision",
                'expected_output' => 'A documented precision percentage for the filled-in template on a real search.',
                'preview_title' => 'Your output should show a precision score with notes on any false positives found.',
                'task' => 'Review 20 live results and record the percentage that are true matches.',
                'hint' => 'Open each profile individually rather than judging relevance from the snippet alone.',
                'mentor_tip' => 'A template that looks correct on paper still needs a real-world precision check.',
                'preview_points' => ['20 live results reviewed', 'Precision percentage calculated', 'False positives noted with reasons'],
                'mistakes' => ['Judging relevance from the search snippet only', 'Reviewing fewer than 20 results', 'Not recording why a false positive slipped through'],
                'tips' => ['Keep a running log of false positives to refine future exclusions.', 'Re-run validation whenever the template is edited.'],
            ],
            [
                'number' => 10,
                'slug' => 'document-and-package',
                'nav_label' => 'Documentation',
                'title' => 'Document and Package for Team Use',
                'description' => 'Prepare the template so others can use it without you.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'A README and finished template saved together in a shared team location.',
                'why_text' => 'Documentation turns a personal tool into a team asset that survives beyond the person who built it.',
                'lesson' => 'Write a short README covering how to fill in the template, what the exclusion terms protect against, and the known length limit from Step 7. Save the final template and README together.',
                'file_name' => 'README.md',
                'code_snippet' => "# LinkedIn X-Ray Search Template\n\n## Usage\nFill in {TITLE_GROUP} and {LOCATION}, then paste into Google.\n\n## Notes\n- Exclusions remove job ads and directory pages.\n- Keep the full query under ~32 words.",
                'expected_output' => 'A committed README and template file ready for another recruiter to use without further explanation.',
                'preview_title' => 'Your submission should let a new team member run a search without asking you questions.',
                'task' => 'Document usage instructions, exclusion rationale, and the safe query length limit, then share the final template and README.',
                'hint' => 'Read your own README as if you\'d never seen the template before.',
                'mentor_tip' => 'A clear submission makes your effort easy for the rest of the team to actually use.',
                'preview_points' => ['README explains how to fill in the template', 'Exclusion rationale is documented', 'Length limit from Step 7 is noted'],
                'mistakes' => ['Leaving usage steps vague', 'Omitting the length-limit warning', 'Saving the template somewhere only you can find it'],
                'tips' => ['Include one filled-in example query in the README.', 'Save the file where the whole sourcing team has access.'],
            ],
        ];
    }

    protected function resources(): array
    {
        return [
            [
                'category' => 'Documentation',
                'label' => 'Google Advanced Search Operators',
                'icon' => 'fi fi-rr-document',
                'sort_order' => 1,
                'href' => 'https://support.google.com/websearch/answer/2466433',
                'description' => 'Official reference for site:, intitle:, inurl:, and other Google search operators used to build X-Ray strings.',
            ],
            [
                'category' => 'Guidelines',
                'label' => 'Boolean Search Fundamentals for Recruiters',
                'icon' => 'fi fi-rr-shield',
                'sort_order' => 2,
                'href' => 'https://www.google.com/search?q=boolean+search+fundamentals+for+recruiters',
                'description' => 'Background reading on AND/OR/NOT logic as applied specifically to candidate sourcing searches.',
            ],
            [
                'category' => 'Examples',
                'label' => 'X-Ray Search String Library',
                'icon' => 'fi fi-rr-list',
                'sort_order' => 3,
                'href' => 'https://www.google.com/search?q=linkedin+x-ray+search+string+examples',
                'description' => 'A collection of working X-Ray string examples across different roles, used as a reference while building your own template.',
            ],
        ];
    }

    protected function goals(): array
    {
        return [
            [
                'title' => 'Master X-Ray Search Logic',
                'duration' => '1-2 hours',
                'type' => 'daily',
                'body' => 'Understand how site:, quoted phrases, and OR grouping combine to search LinkedIn\'s public index without a Recruiter license.',
            ],
            [
                'title' => 'Build a Noise-Free Query',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Learn to exclude job ads, aggregator pages, and irrelevant results using intitle: and inurl: exclusions.',
            ],
            [
                'title' => 'Ship a Reusable Team Template',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Package the finished query into a fill-in-the-blank template with documentation so any recruiter on the team can use it independently.',
            ],
        ];
    }
}
