<?php

/*
|--------------------------------------------------------------------------
| Curated FAQ knowledge base
|--------------------------------------------------------------------------
| Plain, editable knowledge used to ground the chatbot. Add/edit freely —
| no code changes needed. "keywords" help the rule-based fallback match a
| question when the AI is disabled; the AI also receives matched entries
| as context so it stays accurate.
*/

return [
    [
        'q' => 'What is Engineers Clinic?',
        'a' => 'Engineers Clinic is an online platform offering project-based engineering courses, internships, college tie-ups, quizzes and certificates to help students build real, job-ready skills.',
        'keywords' => ['what is', 'about', 'engineers clinic', 'platform'],
    ],
    [
        'q' => 'How do I enroll in a course?',
        'a' => 'Create a free account or log in, open the course you want, tap "Reserve Your Seat" and complete checkout. Paid courses use Razorpay; free courses enroll instantly.',
        'keywords' => ['enroll', 'join', 'register course', 'sign up course', 'how to start', 'admission'],
    ],
    [
        'q' => 'What payment methods are supported?',
        'a' => 'Payments are handled securely by Razorpay, which supports UPI, debit/credit cards, net-banking and popular wallets.',
        'keywords' => ['payment', 'pay', 'upi', 'card', 'razorpay', 'netbanking', 'wallet', 'how to pay'],
    ],
    [
        'q' => 'Do you offer refunds?',
        'a' => 'For refund or billing questions, please tap "Talk to a human" so our support team can review your specific order and assist you.',
        'keywords' => ['refund', 'money back', 'cancel', 'cancellation', 'return money'],
    ],
    [
        'q' => 'How do I get my certificate?',
        'a' => 'Complete your course workspace, the assigned projects and the final quiz. Your certificate then appears in your student dashboard, ready to download and share.',
        'keywords' => ['certificate', 'certification', 'completion', 'download certificate'],
    ],
    [
        'q' => 'Where can I see my enrolled courses?',
        'a' => 'Log in and open your dashboard — your active courses are listed under "Enrolled Courses", where you can continue your workspace and track progress.',
        'keywords' => ['enrolled courses', 'my courses', 'dashboard', 'my learning', 'continue course'],
    ],
    [
        'q' => 'How do colleges partner with Engineers Clinic?',
        'a' => 'Colleges can partner to offer project-based training at scale. Visit the College Tie-up page (/college-tieup) and submit your institution details — our partnerships team will get in touch.',
        'keywords' => ['college', 'institution', 'tie-up', 'tieup', 'partner', 'partnership', 'university'],
    ],
    [
        'q' => 'Can I talk to a counsellor before joining?',
        'a' => 'Yes! Share your name and phone number and our counselling team will reach out to guide you on the right course. Tap "Talk to a human" and I will collect your details.',
        'keywords' => ['counsel', 'counselling', 'guidance', 'advice', 'which course', 'help me choose'],
    ],
    [
        'q' => 'Are the courses beginner friendly?',
        'a' => 'Most courses are structured to take you from fundamentals to real projects step by step, so beginners can follow along while still being valuable for intermediate learners. Check each course page for its level.',
        'keywords' => ['beginner', 'newbie', 'basic', 'no experience', 'level', 'difficulty'],
    ],
    [
        'q' => 'How long do courses take?',
        'a' => 'Course duration varies — many run over a few months with monthly phases of projects and modules. The exact duration is shown on each course page.',
        'keywords' => ['duration', 'how long', 'months', 'time', 'length'],
    ],
    [
        'q' => 'How do I log in or reset access?',
        'a' => 'Use the Login page (/login) with your registered email and password. If you cannot access your account, tap "Talk to a human" and our team will help restore it.',
        'keywords' => ['login', 'log in', 'password', 'reset', 'forgot', 'cannot access', 'account'],
    ],
];
