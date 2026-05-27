<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enquiry Received</title>
</head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e6ecf5;box-shadow:0 18px 45px rgba(18,32,58,0.08);">
                    <tr>
                        <td style="background:#0f172a;padding:34px 32px;color:#ffffff;">
                            <div style="font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;opacity:0.9;">Engineers Clinic Course Enquiry</div>
                            <h1 style="margin:14px 0 8px;font-size:30px;line-height:1.2;font-weight:800;">Thank you, {{ $enquiry->name }}.</h1>
                            <p style="margin:0;font-size:16px;line-height:1.7;color:#e2e8f0;">We have received your enquiry. Our team will contact you shortly.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 32px 8px;">
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.7;color:#344054;">A counsellor will review your details and help you with course guidance, seat availability, and the next step.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;border:1px solid #e5edf6;border-radius:14px;margin:22px 0;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <div style="font-size:13px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#64748b;margin-bottom:14px;">Submitted details</div>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:120px;">Name</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $enquiry->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:120px;">Phone</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $enquiry->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:120px;">Email</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $enquiry->email }}</td>
                                            </tr>
                                            @if(filled($enquiry->course_title))
                                                <tr>
                                                    <td style="padding:8px 0;color:#64748b;font-size:14px;width:120px;">Course</td>
                                                    <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $enquiry->course_title }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 32px 30px;">
                            <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:14px;padding:16px 18px;color:#334155;font-size:14px;line-height:1.6;">
                                This is only a confirmation email. No payment, password, or login credentials are included.
                            </div>
                        </td>
                    </tr>
                </table>

                <p style="max-width:680px;margin:18px auto 0;font-size:12px;line-height:1.6;color:#667085;text-align:center;">This message was sent by Engineers Clinic because a course enquiry was submitted with this email address.</p>
            </td>
        </tr>
    </table>
</body>
</html>
