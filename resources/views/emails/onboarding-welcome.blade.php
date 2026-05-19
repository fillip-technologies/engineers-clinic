<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Welcome to Engineers Clinic</title>
    <style>
        /* Client-safe inline fallbacks, plus some modern touches */
        @media only screen and (max-width: 600px) {
            .responsive-table { width: 100% !important; }
            .responsive-padding { padding: 24px 20px !important; }
            .button-stack { display: block !important; width: 100% !important; text-align: center !important; margin-bottom: 12px !important; }
            .button-stack-td { display: block !important; width: 100% !important; margin: 0 0 12px !important; }
            .full-width-mobile { width: 100% !important; }
            .text-center-mobile { text-align: center !important; }
            .credits-mobile { font-size: 11px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:#eef2f8;font-family:'Segoe UI', Roboto, 'Helvetica Neue', Helvetica, Arial, sans-serif;color:#1a2c3e;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f8;padding:36px 20px;">
        <tr>
            <td align="center">
                <!-- Main card container -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;margin:0 auto;">
                    <tr>
                        <td style="background:#ffffff;border-radius:28px;overflow:hidden;box-shadow:0 20px 35px -12px rgba(0,0,0,0.08),0 1px 3px rgba(0,0,0,0.02);">

                            <!-- Hero / Header section -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="background:#0f766e;padding:42px 36px 38px;color:#ffffff;">
                                        <div style="font-size:13px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase;opacity:0.85;margin-bottom:18px;">Engineers Clinic</div>
                                        <h1 style="margin:0 0 12px 0;font-size:34px;line-height:1.2;font-weight:800;letter-spacing:-0.3px;">Welcome aboard,<br>{{ $user->name }}.</h1>
                                        <p style="margin:0;font-size:17px;line-height:1.6;color:#e6fffa;opacity:0.95;">Your {{ ucfirst($accountType) }} account is ready. You can now explore practical learning, guided progress, and career-focused tools on the platform.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Main content -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding:36px 36px 20px;">
                                        <p style="margin:0 0 16px 0;font-size:17px;line-height:1.6;color:#2c3e50;">We are glad to have you here. <strong>Engineers Clinic</strong> is built to help learners and institutions move from theory to real work — with structured courses, dashboards, tasks, quizzes, and progress tracking.</p>

                                        <!-- Credentials panel (elevated style) -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f9fbfd;border:1px solid #e2edf2;border-radius:20px;margin:28px 0 24px;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                                            <tr>
                                                <td style="padding:24px 28px;">
                                                    <div style="font-size:12px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#2c7a6e;margin-bottom:20px;border-left:3px solid #0f766e;padding-left:12px;">🔐 Your login credentials</div>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:4px;">
                                                        <tr>
                                                            <td style="padding:10px 0;border-bottom:1px solid #e9edf2;color:#4a5b6e;font-size:15px;width:110px;font-weight:500;">Email</td>
                                                            <td style="padding:10px 0;border-bottom:1px solid #e9edf2;color:#0f2b3d;font-size:15px;font-weight:700;">{{ $user->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0;border-bottom:1px solid #e9edf2;color:#4a5b6e;font-size:15px;width:110px;font-weight:500;">Password</td>
                                                            <td style="padding:10px 0;border-bottom:1px solid #e9edf2;color:#0f2b3d;font-size:15px;font-weight:700;font-family:monospace;letter-spacing:0.3px;">{{ $plainPassword }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0;color:#4a5b6e;font-size:15px;width:110px;font-weight:500;">Account Type</td>
                                                            <td style="padding:10px 0;color:#0f2b3d;font-size:15px;font-weight:700;">
                                                                <span style="background:#e6f4f0;padding:4px 12px;border-radius:40px;font-size:13px;letter-spacing:0.2px;">{{ ucfirst($accountType) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Action buttons (responsive group) -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:30px 0 20px;">
                                            <tr>
                                                <td style="border-radius:14px;background:#0f766e;box-shadow:0 4px 8px rgba(15,118,110,0.12);">
                                                    <a href="{{ $dashboardUrl }}" style="display:inline-block;padding:14px 28px;background:#0f766e;color:#ffffff;text-decoration:none;font-size:16px;font-weight:700;border-radius:14px;letter-spacing:0.2px;">📊 Open your dashboard</a>
                                                </td>
                                                <td style="width:16px;"></td>
                                                <td style="border-radius:14px;background:#f1f9f7;border:1px solid #cbdbe0;">
                                                    <a href="{{ $loginUrl }}" style="display:inline-block;padding:14px 28px;background:#f1f9f7;color:#0f766e;text-decoration:none;font-size:16px;font-weight:700;border-radius:14px;">🔑 Go to login</a>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Quick first step hint -->
                                        <div style="border-top:1px solid #e2edf2;padding-top:24px;margin-top:16px;">
                                            <p style="margin:0 0 8px 0;font-size:16px;line-height:1.5;color:#1a3a47;font-weight:800;">✨ A quick first step:</p>
                                            <p style="margin:0;font-size:15px;line-height:1.6;color:#476b7a;">Sign in, review your dashboard, and save this email until you store your credentials securely. Your personalized learning journey starts now.</p>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Security note (warm alert) -->
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="padding:0 36px 30px;">
                                            <div style="background:#fffbeb;border-left:4px solid #e67e22;border-radius:16px;padding:16px 20px;color:#a85d00;font-size:14px;line-height:1.6;">
                                                ⚠️ <strong>Security reminder:</strong> For your safety, do not share this password with anyone. You can update it anytime after logging in via account settings.
                                            </div>
                                         </td>
                                    </tr>
                                </table>
                            </table>

                            <!-- Subtle footer note inside card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="background:#fafcfd;border-top:1px solid #e2edf2;padding:20px 36px 24px;">
                                        <p style="margin:0;font-size:13px;line-height:1.5;color:#6c8b9b;text-align:center;">This message was sent by Engineers Clinic because an account was created with this email address.</p>
                                        <p style="margin:12px 0 0 0;font-size:12px;line-height:1.5;color:#90aebb;text-align:center;">© Engineers Clinic — practical engineering education</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
