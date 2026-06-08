<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseWorkspace;
use Illuminate\Database\Seeder;

class EnterprisePasswordStrengthAnalyzerWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $course = $this->course();

        $workspace = CourseWorkspace::updateOrCreate(
            [
                'course_id' => $course->id,
                'title' => 'Enterprise Password Strength Analyzer',
            ],
            [
                'track' => 'Security & Backend Operations',
                'headline' => 'Build an entropy-based password analyzer that scores security mathematically, moving beyond basic character-count validation.',
                'summary' => 'Most applications validate passwords using flawed, rigid regex rules. In this workspace, students build an enterprise-grade Python or Node.js utility that calculates cryptographic entropy, evaluates character pool size, checks breached-password dictionaries, and exposes the logic through a reusable API or CLI.',
                'progress' => 0,
                'next_milestone' => 'Step 1: Environment Setup & Architecture',
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

        $this->command?->info('Seeded Enterprise Password Strength Analyzer workspace for ' . $course->title . '.');
    }

    protected function course(): Course
    {
        return Course::query()
            ->where('title', 'Engineering & AI Courses')
            ->orWhereIn('slug', ['ethical-hacking', 'cyber-security'])
            ->orWhere('title', 'like', '%Cyber%Security%')
            ->orWhere('title', 'like', '%Ethical%Hacking%')
            ->first()
            ?? Course::firstOrCreate(
                ['slug' => 'engineering-ai-courses'],
                [
                    'title' => 'Engineering & AI Courses',
                    'description' => 'Practical engineering and AI projects with guided workspaces.',
                    'level' => 'Intermediate',
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
                'slug' => 'environment-setup-architecture',
                'nav_label' => 'Environment Setup',
                'title' => 'Project Initialization & Environment Setup',
                'description' => 'Initialize your workspace structure and package manager.',
                'status' => 'In Progress',
                'state' => 'active',
                'active' => true,
                'build_goal' => 'Create a clean Python or Node.js project with version control, ignored dependencies, and separated main, utility, and test files.',
                'why_text' => 'A clean architecture makes the analyzer easier to test, reuse, and expose later through a CLI or API.',
                'lesson' => 'Create your project directory and set up version control. For Python, establish a virtual environment. For Node.js, run npm init. Create the primary entry file and a separate utility file for the analyzer logic.',
                'file_name' => 'terminal',
                'code_snippet' => "mkdir enterprise-password-analyzer\ncd enterprise-password-analyzer\ngit init\nmkdir src tests\n# Python: python -m venv .venv\n# Node.js: npm init -y",
                'expected_output' => 'A local project folder exists with Git initialized and clear folders for source files and tests.',
                'preview_title' => 'Your workspace should show a clean project structure ready for analyzer code.',
                'task' => 'Initialize a local git repository, create a .gitignore file, and add main, utils, and tests directories.',
                'hint' => 'Keep analyzer logic outside the entry file so tests can import it directly.',
                'mentor_tip' => 'The first step is boring in the best way: it removes friction from every step after it.',
                'preview_points' => ['Git repository initialized', '.gitignore created', 'Source and test folders are separated'],
                'mistakes' => ['Skipping .gitignore', 'Putting all logic in one file', 'Creating tests only after the project is finished'],
                'tips' => ['Commit the starting structure before writing logic.', 'Use names like analyzer.py/index.js and password_utils.py/passwordUtils.js.'],
            ],
            [
                'number' => 2,
                'slug' => 'define-character-pools',
                'nav_label' => 'Character Pools',
                'title' => 'Define Character Pools',
                'description' => 'Define the constants for different character sets.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Declare the lowercase, uppercase, digit, and symbol pool sizes used by entropy calculations.',
                'why_text' => 'Entropy depends on the possible character pool, not only password length.',
                'lesson' => 'Define strict constants or arrays for Lowercase (26), Uppercase (26), Digits (10), and Symbols, usually 32 standard special characters.',
                'file_name' => 'src/password_utils.py',
                'code_snippet' => "LOWERCASE_POOL = 26\nUPPERCASE_POOL = 26\nDIGIT_POOL = 10\nSYMBOL_POOL = 32",
                'expected_output' => 'Your utility file exposes reusable constants for every supported character set.',
                'preview_title' => 'Your constants should be easy to import into detection and scoring functions.',
                'task' => 'Write variables holding the lengths of each character type.',
                'hint' => 'Use constants instead of magic numbers inside the entropy function.',
                'mentor_tip' => 'Clear constants make the math readable when someone reviews the project.',
                'preview_points' => ['Lowercase pool is 26', 'Uppercase pool is 26', 'Digit pool is 10', 'Symbol pool is documented'],
                'mistakes' => ['Hardcoding pool values inside many functions', 'Forgetting symbols', 'Mixing character examples with pool-size numbers'],
                'tips' => ['Keep constants near the top of the utility file.', 'Add a short note describing the symbol set you support.'],
            ],
            [
                'number' => 3,
                'slug' => 'pool-size-detection',
                'nav_label' => 'Pool Detection',
                'title' => 'Build Pool Size Detection Logic',
                'description' => 'Write the logic to analyze a string and determine its total character pool.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Create a function that checks which character sets a password uses and returns the combined pool size.',
                'why_text' => 'A password using lowercase and digits has a smaller possible search space than one using every character category.',
                'lesson' => 'Create a function that accepts a password and checks which character sets it uses. If a password uses lowercase and digits, the pool size is 26 + 10 = 36.',
                'file_name' => 'src/password_utils.py',
                'code_snippet' => "def get_pool_size(password):\n    pool_size = 0\n    if any(char.islower() for char in password):\n        pool_size += LOWERCASE_POOL\n    if any(char.isupper() for char in password):\n        pool_size += UPPERCASE_POOL\n    if any(char.isdigit() for char in password):\n        pool_size += DIGIT_POOL\n    if any(not char.isalnum() for char in password):\n        pool_size += SYMBOL_POOL\n    return pool_size",
                'expected_output' => 'The function returns an integer pool size such as 36 for lowercase plus digits.',
                'preview_title' => 'Your analyzer can now identify the character search space behind a password.',
                'task' => 'Create get_pool_size(password) that returns an integer representing the character pool.',
                'hint' => 'Use boolean checks or regex tests, then add each pool only once.',
                'mentor_tip' => 'This is the bridge between string inspection and security math.',
                'preview_points' => ['Lowercase detection works', 'Digit detection works', 'Multiple pools add correctly'],
                'mistakes' => ['Adding 26 for every lowercase letter', 'Counting repeated characters as new pools', 'Returning text instead of a number'],
                'tips' => ['Test abc123 before testing complex strings.', 'Write one assertion for each character category.'],
            ],
            [
                'number' => 4,
                'slug' => 'entropy-formula',
                'nav_label' => 'Entropy Formula',
                'title' => 'Implement the Mathematical Entropy Formula',
                'description' => 'Apply the entropy equation using native math libraries.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Calculate entropy bits using password length and detected pool size.',
                'why_text' => 'Entropy converts the search space into a measurable security score.',
                'lesson' => 'Use the formula E = L x log2(R), where E is entropy in bits, L is password length, and R is the pool size calculated in Step 3.',
                'file_name' => 'src/password_utils.py',
                'code_snippet' => "import math\n\ndef calculate_entropy(length, pool_size):\n    if length <= 0 or pool_size <= 0:\n        return 0.0\n\n    return round(length * math.log2(pool_size), 2)",
                'expected_output' => 'The function returns rounded entropy bits for valid password length and pool size inputs.',
                'preview_title' => 'Your analyzer now produces a mathematical entropy score.',
                'task' => 'Create calculate_entropy(length, pool_size) returning the entropy bits.',
                'hint' => 'Guard against empty strings so log2 never receives zero.',
                'mentor_tip' => 'Once the formula works, the rest of the app is mostly interpretation and packaging.',
                'preview_points' => ['Uses log2', 'Handles empty passwords', 'Returns a rounded float'],
                'mistakes' => ['Using log10 instead of log2', 'Ignoring empty passwords', 'Passing the password string instead of its length'],
                'tips' => ['Manually verify one simple input.', 'Keep the math function pure so it is easy to test.'],
            ],
            [
                'number' => 5,
                'slug' => 'security-scoring-thresholds',
                'nav_label' => 'Scoring',
                'title' => 'Create Security Scoring Thresholds',
                'description' => 'Translate raw entropy bits into human-readable security levels.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Map entropy scores to clear labels such as Very Weak, Weak, Reasonable, Strong, and Very Strong.',
                'why_text' => 'Users and reviewers need a readable security label, not only raw entropy bits.',
                'lesson' => 'Define enterprise thresholds: below 28 bits is Very Weak, 28-35 is Weak, 36-59 is Reasonable, 60-127 is Strong, and above 127 is Very Strong.',
                'file_name' => 'src/password_utils.py',
                'code_snippet' => "def get_strength_label(entropy_score):\n    if entropy_score < 28:\n        return 'Very Weak'\n    if entropy_score < 36:\n        return 'Weak'\n    if entropy_score < 60:\n        return 'Reasonable'\n    if entropy_score < 128:\n        return 'Strong'\n    return 'Very Strong'",
                'expected_output' => 'The analyzer converts entropy bits into consistent strength labels.',
                'preview_title' => 'Your output now reads like a security report instead of a raw calculator.',
                'task' => 'Write a function get_strength_label(entropy_score).',
                'hint' => 'Order threshold checks from lowest to highest.',
                'mentor_tip' => 'Good labels make the security result understandable without weakening the math.',
                'preview_points' => ['Very Weak threshold works', 'Strong threshold works', 'Boundary values are tested'],
                'mistakes' => ['Overlapping ranges', 'Skipping boundary tests', 'Using vague labels without thresholds'],
                'tips' => ['Write tests for 27, 28, 35, 36, 59, 60, 127, and 128.', 'Document the thresholds in README.md later.'],
            ],
            [
                'number' => 6,
                'slug' => 'dictionary-checks',
                'nav_label' => 'Dictionary Check',
                'title' => 'Integrate Common Dictionary Checks',
                'description' => 'Prevent high-entropy but compromised passwords.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Load a common-password dictionary and fail compromised passwords before entropy scoring.',
                'why_text' => 'Passwords like Password123! may look complex but are unsafe if they appear in breach lists.',
                'lesson' => 'Download a sample top 10,000 worst passwords text file. Load it into a Hash Set for O(1) lookup speed and return Compromised/Very Weak when a password is found.',
                'file_name' => 'src/password_utils.py',
                'code_snippet' => "def load_compromised_passwords(path):\n    with open(path, 'r', encoding='utf-8') as file:\n        return {line.strip() for line in file if line.strip()}\n\ndef is_compromised(password, compromised_passwords):\n    return password in compromised_passwords",
                'expected_output' => 'Known dictionary passwords are flagged before entropy scoring continues.',
                'preview_title' => 'Your analyzer can now block predictable and breached passwords.',
                'task' => 'Import the text file into a Hash Set and write a true/false lookup function.',
                'hint' => 'Normalize case only if your policy explicitly treats passwords case-insensitively.',
                'mentor_tip' => 'Enterprise password checks need both math and breach awareness.',
                'preview_points' => ['Dictionary file loads', 'Lookup returns boolean', 'Compromised passwords fail early'],
                'mistakes' => ['Scanning the whole file on every request', 'Ignoring whitespace in dictionary rows', 'Letting compromised passwords pass because entropy is high'],
                'tips' => ['Use a small sample file while testing.', 'Keep very large dictionary files out of Git if needed.'],
            ],
            [
                'number' => 7,
                'slug' => 'main-analyzer-engine',
                'nav_label' => 'Analyzer Engine',
                'title' => 'Assemble the Main Analyzer Engine',
                'description' => 'Combine all utility functions into a single entry point.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Return one structured analysis result containing length, pool size, entropy, label, and compromise status.',
                'why_text' => 'A single master function makes the utility easy to use from tests, CLI commands, or APIs.',
                'lesson' => 'Create analyze_password(pwd). The flow should be dictionary check, pool detection, entropy calculation, threshold label, and structured JSON response.',
                'file_name' => 'src/analyzer.py',
                'code_snippet' => "def analyze_password(password, compromised_passwords=None):\n    compromised_passwords = compromised_passwords or set()\n    is_breached = is_compromised(password, compromised_passwords)\n    pool_size = get_pool_size(password)\n    entropy_bits = calculate_entropy(len(password), pool_size)\n\n    return {\n        'password_length': len(password),\n        'pool_size': pool_size,\n        'entropy_bits': entropy_bits,\n        'strength_label': 'Compromised/Very Weak' if is_breached else get_strength_label(entropy_bits),\n        'is_compromised': is_breached,\n    }",
                'expected_output' => 'Calling the master function returns a JSON-ready dictionary with all key analysis fields.',
                'preview_title' => 'Your analyzer now has one professional entry point.',
                'task' => 'Combine functions to return the final structured JSON response.',
                'hint' => 'Fail early for compromised passwords, but still include enough context for reporting.',
                'mentor_tip' => 'This is where the project starts feeling like a product instead of disconnected functions.',
                'preview_points' => ['Returns password_length', 'Returns entropy_bits', 'Returns is_compromised'],
                'mistakes' => ['Returning mixed text instead of structured data', 'Hiding compromise status', 'Duplicating logic from utility functions'],
                'tips' => ['Keep the response keys stable.', 'Avoid returning the raw password in logs or API responses.'],
            ],
            [
                'number' => 8,
                'slug' => 'cli-or-micro-api',
                'nav_label' => 'CLI or API',
                'title' => 'Wrap in a CLI or Micro-API',
                'description' => 'Make the utility usable for external applications.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Expose the analyzer through command-line arguments or a lightweight HTTP endpoint.',
                'why_text' => 'A reusable interface turns the analyzer into something another application can call.',
                'lesson' => 'Choose a deployment method: argparse or process.argv for CLI usage, or Flask/FastAPI/Express for POST requests with password payloads.',
                'file_name' => 'analyzer.py',
                'code_snippet' => "import argparse\nimport json\n\nparser = argparse.ArgumentParser()\nparser.add_argument('--pwd', required=True)\nargs = parser.parse_args()\n\nprint(json.dumps(analyze_password(args.pwd), indent=2))",
                'expected_output' => 'Running the CLI or sending a local API request returns the analyzer result as JSON.',
                'preview_title' => 'Your analyzer can now be used outside the code editor.',
                'task' => 'Expose the analyzer logic to the command line or a local HTTP port.',
                'hint' => 'Start with CLI first if you want the fastest path; build API after the logic is stable.',
                'mentor_tip' => 'Interfaces are contracts. Keep them small and predictable.',
                'preview_points' => ['Accepts a password input', 'Returns JSON output', 'Does not print the password unnecessarily'],
                'mistakes' => ['Mixing CLI parsing inside utility functions', 'Logging sensitive input', 'Building the API before the analyzer is tested'],
                'tips' => ['Use JSON output so other tools can consume it.', 'Validate that empty input fails gracefully.'],
            ],
            [
                'number' => 9,
                'slug' => 'unit-tests',
                'nav_label' => 'Unit Tests',
                'title' => 'Write Unit Tests',
                'description' => 'Validate the logic against edge cases.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Create tests for empty strings, simple pools, complex pools, dictionary matches, and manual entropy calculations.',
                'why_text' => 'Security utilities need reliable behavior across edge cases and boundary values.',
                'lesson' => 'Implement pytest for Python or Jest for JavaScript. Write test cases for empty strings, lowercase-only strings, complex strings, and known dictionary words.',
                'file_name' => 'tests/test_analyzer.py',
                'code_snippet' => "def test_empty_password_entropy_is_zero():\n    assert calculate_entropy(0, 0) == 0.0\n\ndef test_lowercase_and_digits_pool_size():\n    assert get_pool_size('abc123') == 36",
                'expected_output' => 'The test suite passes and verifies the analyzer logic against expected outputs.',
                'preview_title' => 'Your security logic is now backed by repeatable checks.',
                'task' => 'Write at least 5 test cases covering edge cases and standard inputs.',
                'hint' => 'Test utility functions first, then test analyze_password.',
                'mentor_tip' => 'Tests give you confidence when you turn a script into a reusable tool.',
                'preview_points' => ['Empty password test exists', 'Pool-size test exists', 'Compromised-password test exists'],
                'mistakes' => ['Only testing happy paths', 'Skipping boundary thresholds', 'Using test data that cannot be manually verified'],
                'tips' => ['Keep tests small and named clearly.', 'Add one test for each threshold boundary.'],
            ],
            [
                'number' => 10,
                'slug' => 'documentation-github-push',
                'nav_label' => 'Documentation',
                'title' => 'Technical Documentation & GitHub Push',
                'description' => 'Prepare the utility for enterprise review and deployment.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build_goal' => 'Publish a documented project with setup steps, execution examples, JSON payload structure, and final source code.',
                'why_text' => 'A reviewer should understand the math, run the tool, and inspect the implementation without needing extra explanation.',
                'lesson' => 'Write a comprehensive README.md explaining entropy calculation, installation, CLI/API usage, and dictionary handling. Commit your code and push it to a public GitHub repository.',
                'file_name' => 'README.md',
                'code_snippet' => "# Enterprise Password Strength Analyzer\n\n## Run\npython analyzer.py --pwd \"MySecret123!\"\n\n## Output\nReturns password_length, pool_size, entropy_bits, strength_label, and is_compromised.",
                'expected_output' => 'The final project is committed, documented, and ready to share through GitHub.',
                'preview_title' => 'Your portfolio submission is ready for review.',
                'task' => 'Document setup, execution commands, JSON output, and push the final working directory.',
                'hint' => 'Open the README from a fresh clone perspective: can someone run your tool from zero?',
                'mentor_tip' => 'Documentation is part of the product, especially for security tools.',
                'preview_points' => ['README explains the math', 'Run command is documented', 'GitHub repository is pushed'],
                'mistakes' => ['Leaving setup steps vague', 'Committing large dictionary files accidentally', 'Submitting without testing the documented command'],
                'tips' => ['Include one sample JSON response.', 'Mention whether the tool supports CLI, API, or both.'],
            ],
        ];
    }

    protected function resources(): array
    {
        return [
            [
                'category' => 'Documentation',
                'label' => 'Understanding Password Entropy',
                'icon' => 'fi fi-rr-document',
                'sort_order' => 1,
                'href' => 'https://en.wikipedia.org/wiki/Password_strength#Entropy_as_a_measure_of_password_strength',
                'description' => 'A detailed breakdown of the mathematics of information entropy as applied to cryptographic passwords.',
            ],
            [
                'category' => 'Guidelines',
                'label' => 'NIST Password Guidelines (SP 800-63B)',
                'icon' => 'fi fi-rr-shield',
                'sort_order' => 2,
                'href' => 'https://pages.nist.gov/800-63-3/sp800-63b.html',
                'description' => 'Official enterprise standards and recommendations for password security, replacing outdated complexity rules.',
            ],
            [
                'category' => 'Examples',
                'label' => 'SecLists: Common Passwords',
                'icon' => 'fi fi-rr-list',
                'sort_order' => 3,
                'href' => 'https://github.com/danielmiessler/SecLists/tree/master/Passwords',
                'description' => 'A standard repository for downloading text files of compromised passwords to use in dictionary checks.',
            ],
        ];
    }

    protected function goals(): array
    {
        return [
            [
                'title' => 'Master Information Entropy',
                'duration' => '1-2 hours',
                'type' => 'daily',
                'body' => 'Move beyond arbitrary regex validation and understand how to calculate true cryptographic difficulty using character pools and logarithmic math.',
            ],
            [
                'title' => 'Implement Dictionary Defenses',
                'duration' => '1-2 hours',
                'type' => 'daily',
                'body' => 'Learn how to implement O(1) Hash Set lookups to instantly flag breached or highly predictable passwords, regardless of their length.',
            ],
            [
                'title' => 'Deploy a Reusable Utility',
                'duration' => '1 hour',
                'type' => 'daily',
                'body' => 'Structure your code into a professional API or CLI tool that can be modularly injected into any future enterprise application you build.',
            ],
        ];
    }
}
