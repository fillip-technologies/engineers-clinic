<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counselling Request Received</title>
</head>
<body style="margin:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e6ecf5;box-shadow:0 18px 45px rgba(18,32,58,0.08);">
                    <tr>
                        <td style="background:#4f46e5;padding:34px 32px;color:#ffffff;">
                            <div style="font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;opacity:0.9;">Engineers Clinic Counselling</div>
                            <h1 style="margin:14px 0 8px;font-size:30px;line-height:1.2;font-weight:800;">Thank you, {{ $lead->name }}.</h1>
                            <p style="margin:0;font-size:16px;line-height:1.7;color:#eef2ff;">We have received your counselling request. Our team will contact you shortly.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 32px 8px;">
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.7;color:#344054;">A counsellor will review your details and reach out to help you choose the right learning path, internship level, or program option.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc;border:1px solid #e5edf6;border-radius:14px;margin:22px 0;">
                                <tr>
                                    <td style="padding:20px 22px;">
                                        <div style="font-size:13px;font-weight:700;letter-spacing:0.05em;text-transform:uppercase;color:#64748b;margin-bottom:14px;">Submitted details</div>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:120px;">Name</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $lead->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:8px 0;color:#64748b;font-size:14px;width:120px;">Phone</td>
                                                <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $lead->phone }}</td>
                                            </tr>
                                            @if(filled($lead->email))
                                                <tr>
                                                    <td style="padding:8px 0;color:#64748b;font-size:14px;width:120px;">Email</td>
                                                    <td style="padding:8px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $lead->email }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div style="border-top:1px solid #edf2f7;padding-top:22px;margin-top:8px;">
                                <p style="margin:0 0 12px;font-size:15px;line-height:1.7;color:#344054;font-weight:700;">What happens next?</p>
                                <p style="margin:0;font-size:15px;line-height:1.7;color:#475467;">Our team will call you on the submitted phone number. Keep your questions ready so the conversation can be useful and specific.</p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:24px 32px 30px;">
                            <div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:14px;padding:16px 18px;color:#3730a3;font-size:14px;line-height:1.6;">
                                This is only a confirmation email. No payment, password, or login credentials are included.
                            </div>
                        </td>
                    </tr>
                </table>

                <p style="max-width:680px;margin:18px auto 0;font-size:12px;line-height:1.6;color:#667085;text-align:center;">This message was sent by Engineers Clinic because a counselling request was submitted with this email address.</p>
            </td>
        </tr>
    </table>
</body>
</html>
