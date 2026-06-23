@php
    $levelColorMap = [
        'Beginner'     => ['bg' => '#ecfdf5', 'border' => '#6ee7b7', 'text' => '#065f46', 'icon' => '🌱'],
        'Intermediate' => ['bg' => '#eff6ff', 'border' => '#93c5fd', 'text' => '#1e40af', 'icon' => '📈'],
        'Advanced'     => ['bg' => '#f5f3ff', 'border' => '#c4b5fd', 'text' => '#5b21b6', 'icon' => '🚀'],
    ];
    $levelLabel  = $student->level ?? 'Beginner';
    $streamLabel = $student->internship_stream ?? 'Technology';
    $lc          = $levelColorMap[$levelLabel] ?? $levelColorMap['Beginner'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Certificate – {{ $user->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 16px;
        }

        /* Toolbar */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            max-width: 860px;
            margin-bottom: 24px;
        }
        .toolbar-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #fff;
        }
        .toolbar-back:hover { border-color: #94a3b8; color: #1e293b; }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            background: #059669;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            cursor: pointer;
        }
        .btn-print:hover { background: #047857; }

        /* Certificate paper */
        .certificate {
            width: 100%;
            max-width: 860px;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 4px 32px rgba(0,0,0,.12);
            position: relative;
            overflow: hidden;
        }

        /* Decorative corner ornaments */
        .corner {
            position: absolute;
            width: 120px;
            height: 120px;
            opacity: .1;
            pointer-events: none;
        }
        .corner-tl { top: 0; left: 0; border-top: 6px solid #1e293b; border-left: 6px solid #1e293b; border-radius: 0 0 60px 0; }
        .corner-tr { top: 0; right: 0; border-top: 6px solid #1e293b; border-right: 6px solid #1e293b; border-radius: 0 0 0 60px; }
        .corner-bl { bottom: 0; left: 0; border-bottom: 6px solid #1e293b; border-left: 6px solid #1e293b; border-radius: 0 60px 0 0; }
        .corner-br { bottom: 0; right: 0; border-bottom: 6px solid #1e293b; border-right: 6px solid #1e293b; border-radius: 60px 0 0 0; }

        /* Gold accent bars */
        .gold-bar { height: 8px; background: linear-gradient(90deg, #b45309, #d97706, #fbbf24, #d97706, #b45309); }

        .cert-body {
            padding: 52px 72px 44px;
            position: relative;
        }
        .cert-body::before {
            content: '';
            position: absolute;
            inset: 20px;
            border: 1px solid #e2e8f0;
            pointer-events: none;
        }

        /* Header */
        .cert-header { text-align: center; margin-bottom: 28px; }

        .org-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
        }
        .org-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #3730a3, #6d28d9);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 700; color: #fff; letter-spacing: -.5px;
        }
        .org-name { font-size: 22px; font-weight: 700; letter-spacing: -.5px; color: #1e293b; }
        .org-tagline {
            font-size: 9.5px; font-weight: 600;
            letter-spacing: 2.5px; text-transform: uppercase;
            color: #94a3b8; margin-top: 4px;
        }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 18px auto; max-width: 380px;
        }
        .divider-line { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, #d97706, transparent); }
        .divider-star { color: #d97706; font-size: 13px; }

        .cert-title {
            font-family: 'Playfair Display', serif;
            font-size: 38px; font-weight: 700;
            color: #0f172a; letter-spacing: -.5px; line-height: 1.15;
        }
        .cert-subtitle {
            font-size: 11px; font-weight: 600;
            letter-spacing: 3.5px; text-transform: uppercase;
            color: #d97706; margin-top: 6px;
        }

        /* Body */
        .cert-presents {
            text-align: center;
            font-size: 13px; color: #64748b;
            font-style: italic; margin-top: 24px;
            font-family: 'Playfair Display', serif;
        }

        .student-name {
            font-family: 'Playfair Display', serif;
            font-size: 42px; font-weight: 400; font-style: italic;
            color: #1e293b; text-align: center; margin: 10px 0 4px;
        }
        .student-name-underline {
            width: 200px; height: 2px; margin: 0 auto 14px;
            background: linear-gradient(90deg, transparent, #d97706, transparent);
        }

        .level-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 22px; border-radius: 999px;
            font-size: 14px; font-weight: 600; border: 1.5px solid;
        }

        .cert-statement {
            text-align: center; font-size: 13.5px;
            color: #475569; line-height: 1.75;
            margin: 14px auto 0; max-width: 560px;
        }

        /* Info pills */
        .info-row {
            display: flex; justify-content: center;
            gap: 12px; flex-wrap: wrap; margin: 20px 0;
        }
        .info-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 8px; padding: 7px 14px;
            font-size: 12px; font-weight: 500; color: #475569;
        }
        .info-pill strong { color: #1e293b; font-weight: 600; }

        /* Projects */
        .projects-heading {
            text-align: center; font-size: 10.5px; font-weight: 700;
            letter-spacing: 2.5px; text-transform: uppercase;
            color: #94a3b8; margin: 22px 0 12px;
        }
        .projects-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 10px; margin-bottom: 28px;
        }
        .project-card {
            border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 12px 14px; background: #f8fafc; text-align: center;
        }
        .project-num { font-size: 10px; font-weight: 700; color: #d97706; letter-spacing: 1.5px; margin-bottom: 4px; }
        .project-title { font-size: 12px; font-weight: 600; color: #1e293b; line-height: 1.4; }
        .project-cat { font-size: 10px; color: #94a3b8; margin-top: 2px; }

        /* Footer */
        .cert-footer {
            display: flex; align-items: flex-end; justify-content: space-between;
            gap: 16px; padding-top: 22px; border-top: 1px solid #f1f5f9;
        }
        .sig-block { text-align: center; }
        .sig-line { width: 150px; height: 1px; background: #cbd5e1; margin: 0 auto 5px; }
        .sig-name { font-size: 12px; font-weight: 600; color: #1e293b; }
        .sig-title { font-size: 10px; color: #94a3b8; margin-top: 1px; }

        .cert-seal {
            width: 88px; height: 88px; border-radius: 50%;
            border: 3px solid #d97706;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; background: #fffbeb;
            box-shadow: inset 0 0 0 4px #fef3c7;
            flex-shrink: 0;
        }
        .seal-text { font-size: 7px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #92400e; line-height: 1.4; }
        .seal-star { font-size: 18px; color: #d97706; margin: 2px 0; }

        .cert-meta { text-align: right; font-size: 10px; color: #94a3b8; line-height: 1.9; }
        .cert-meta strong { color: #475569; font-weight: 600; }

        /* Print */
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none !important; }
            .certificate { box-shadow: none; border-radius: 0; max-width: 100%; }
            .cert-body { padding: 40px 60px 36px; }
        }
    </style>
</head>
<body>

    {{-- Toolbar --}}
    <div class="toolbar">
        <a href="{{ route('dashboard') }}" class="toolbar-back">← Back to Dashboard</a>
        <button class="btn-print" onclick="window.print()">⬇ Download / Print Certificate</button>
    </div>

    {{-- Certificate --}}
    <div class="certificate">
        <div class="gold-bar"></div>

        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        <div class="cert-body">

            {{-- Header --}}
            <div class="cert-header">
                <div class="org-logo">
                    <div class="org-icon">EC</div>
                    <div>
                        <div class="org-name">Engineers Clinic</div>
                    </div>
                </div>
                <div class="org-tagline">Internship Excellence Program</div>

                <div class="divider">
                    <div class="divider-line"></div>
                    <div class="divider-star">✦</div>
                    <div class="divider-line"></div>
                </div>

                <div class="cert-title">Certificate of Completion</div>
                <div class="cert-subtitle">Internship Program</div>
            </div>

            {{-- Recipient --}}
            <div class="cert-presents">This is to certify that</div>

            <div class="student-name">{{ $user->name }}</div>
            <div class="student-name-underline"></div>

            <div style="text-align:center; margin-bottom: 6px;">
                <span class="level-badge"
                      style="background:{{ $lc['bg'] }}; border-color:{{ $lc['border'] }}; color:{{ $lc['text'] }};">
                    {{ $lc['icon'] }}&nbsp; {{ $levelLabel }} Level
                </span>
            </div>

            <p class="cert-statement">
                has successfully completed the <strong>{{ $levelLabel }} Internship Program</strong>
                in <strong>{{ $streamLabel }}</strong> at Engineers Clinic, demonstrating hands-on
                technical proficiency by completing three real-world industry projects.
            </p>

            {{-- Info pills --}}
            <div class="info-row">
                <span class="info-pill">📅 &nbsp;Issued: <strong>{{ $certificate->issued_date->format('d F Y') }}</strong></span>
                <span class="info-pill">🎓 &nbsp;Stream: <strong>{{ $streamLabel }}</strong></span>
                <span class="info-pill">🏷 &nbsp;Cert No: <strong>{{ $certificate->certificate_number }}</strong></span>
            </div>

            {{-- Projects completed --}}
            @if ($completedEnrollments->count() > 0)
                <div class="projects-heading">Projects Completed</div>
                <div class="projects-grid">
                    @foreach ($completedEnrollments->take(3) as $enrollment)
                        @php
                            $course     = $enrollment->course;
                            $curriculum = $course?->curriculum ?? [];
                            $firstItem  = $curriculum[0] ?? [];
                            $projTitle  = filled($firstItem['title'] ?? '') ? $firstItem['title'] : ($course?->title ?? 'Project');
                            $projCat    = $course?->category ?? 'Project';
                        @endphp
                        <div class="project-card">
                            <div class="project-num">PROJECT {{ $loop->iteration }}</div>
                            <div class="project-title">{{ $projTitle }}</div>
                            <div class="project-cat">{{ $projCat }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Footer --}}
            <div class="cert-footer">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">Program Director</div>
                    <div class="sig-title">Engineers Clinic</div>
                </div>

                <div class="cert-seal">
                    <div class="seal-text">Engineers<br>Clinic</div>
                    <div class="seal-star">★</div>
                    <div class="seal-text">Verified<br>{{ now()->year }}</div>
                </div>

                <div class="cert-meta">
                    <strong>Certificate No.</strong><br>
                    {{ $certificate->certificate_number }}<br>
                    <strong style="margin-top:6px; display:block;">Date of Issue</strong>
                    {{ $certificate->issued_date->format('d M Y') }}
                </div>
            </div>

        </div>{{-- /.cert-body --}}

        <div class="gold-bar"></div>
    </div>{{-- /.certificate --}}

</body>
</html>
