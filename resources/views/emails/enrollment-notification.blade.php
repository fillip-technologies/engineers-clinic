<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You've been enrolled — Engineers Clinic</title>
    <style>
        @media only screen and (max-width: 600px) {
            .responsive-table { width: 100% !important; }
            .responsive-padding { padding: 24px 20px !important; }
        }
    </style>
</head>
<body style="margin:0;padding:0;background:#eef2f8;font-family:'Segoe UI', Roboto, 'Helvetica Neue', Helvetica, Arial, sans-serif;color:#1a2c3e;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f8;padding:36px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;margin:0 auto;">
                    <tr>
                        <td style="background:#ffffff;border-radius:28px;overflow:hidden;box-shadow:0 20px 35px -12px rgba(0,0,0,0.08),0 1px 3px rgba(0,0,0,0.02);">

                            <!-- Hero -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="background:#0f766e;padding:42px 36px 38px;color:#ffffff;">
                                        <div style="font-size:13px;font-weight:800;letter-spacing:1.4px;text-transform:uppercase;opacity:0.85;margin-bottom:18px;">Engineers Clinic</div>
                                        <h1 style="margin:0 0 12px 0;font-size:34px;line-height:1.2;font-weight:800;letter-spacing:-0.3px;">You've been enrolled,<br>{{ $user->name }}.</h1>
                                        <p style="margin:0;font-size:17px;line-height:1.6;color:#e6fffa;opacity:0.95;">Your college has allocated an internship seat to you. Log in to your dashboard to get started.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Content -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding:36px 36px 20px;">
                                        <p style="margin:0 0 16px 0;font-size:17px;line-height:1.6;color:#2c3e50;">Great news! Your institution has enrolled you in the following internship program:</p>

                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f9fbfd;border:1px solid #e2edf2;border-radius:20px;margin:28px 0 24px;">
                                            <tr>
                                                <td style="padding:24px 28px;">
                                                    <div style="font-size:12px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#2c7a6e;margin-bottom:16px;border-left:3px solid #0f766e;padding-left:12px;">📋 Enrollment Details</div>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="padding:10px 0;border-bottom:1px solid #e9edf2;color:#4a5b6e;font-size:15px;width:140px;font-weight:500;">Program</td>
                                                            <td style="padding:10px 0;border-bottom:1px solid #e9edf2;color:#0f2b3d;font-size:15px;font-weight:700;">{{ $courseTitle }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:10px 0;color:#4a5b6e;font-size:15px;width:140px;font-weight:500;">Status</td>
                                                            <td style="padding:10px 0;color:#0f2b3d;font-size:15px;font-weight:700;">
                                                                <span style="background:#e6f4f0;padding:4px 12px;border-radius:40px;font-size:13px;">Active</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- CTA -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:30px 0 20px;">
                                            <tr>
                                                <td style="border-radius:14px;background:#0f766e;box-shadow:0 4px 8px rgba(15,118,110,0.12);">
                                                    <a href="{{ $dashboardUrl }}" style="display:inline-block;padding:14px 28px;background:#0f766e;color:#ffffff;text-decoration:none;font-size:16px;font-weight:700;border-radius:14px;">📊 Open your dashboard</a>
                                                </td>
                                            </tr>
                                        </table>

                                        <div style="border-top:1px solid #e2edf2;padding-top:24px;margin-top:16px;">
                                            <p style="margin:0;font-size:15px;line-height:1.6;color:#476b7a;">Log in with your existing credentials. If you've forgotten your password, use the "Forgot password" option on the login page.</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="background:#fafcfd;border-top:1px solid #e2edf2;padding:20px 36px 24px;">
                                        <p style="margin:0;font-size:13px;line-height:1.5;color:#6c8b9b;text-align:center;">This message was sent by Engineers Clinic because your institution enrolled you in a program.</p>
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
