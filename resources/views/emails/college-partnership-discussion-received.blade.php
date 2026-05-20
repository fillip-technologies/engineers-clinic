<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partnership Request Received</title>
</head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e6ecf5;box-shadow:0 18px 45px rgba(18,32,58,0.08);">
                    <tr>
                        <td style="background:#164e63;padding:34px 32px;color:#ffffff;">
                            <div style="font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;opacity:0.86;">Engineers Clinic Partnerships</div>
                            <h1 style="margin:14px 0 8px;font-size:30px;line-height:1.2;font-weight:800;">Thank you, {{ $discussion->full_name }}.</h1>
                            <p style="margin:0;font-size:16px;line-height:1.7;color:#dff7ff;">We have received your request to discuss a college partnership with Engineers Clinic.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 32px 8px;">
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.7;color:#344054;">Our team will review your institution details and contact you shortly. We are excited to learn more about your goals and explore how our practical learning programs can support your students.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;border:1px solid #e5edf6;border-radius:14px;margin:22px 0;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <div style="font-size:13px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#64748b;margin-bottom:14px;">Submitted details</div>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:150px;">Institution</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $discussion->institution_name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:150px;">Designation</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $discussion->designation }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:150px;">Students</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ number_format($discussion->number_of_students) }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:150px;">Department</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $discussion->department_stream }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div style="border-top:1px solid #edf2f7;padding-top:22px;margin-top:8px;">
                                <p style="margin:0 0 12px;font-size:15px;line-height:1.7;color:#344054;font-weight:700;">What happens next?</p>
                                <p style="margin:0;font-size:15px;line-height:1.7;color:#475467;">A member of our team will reach out using your official email or phone number to understand your requirements and schedule the discussion.</p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 32px 30px;">
                            <div style="background:#ecfeff;border:1px solid #a5f3fc;border-radius:14px;padding:16px 18px;color:#155e75;font-size:14px;line-height:1.6;">
                                This email is only a confirmation of your request. No account password or login credentials are included.
                            </div>
                        </td>
                    </tr>
                </table>

                <p style="max-width:680px;margin:18px auto 0;font-size:12px;line-height:1.6;color:#667085;text-align:center;">This message was sent by Engineers Clinic because a partnership discussion request was submitted with this email address.</p>
            </td>
        </tr>
    </table>
</body>
</html>
