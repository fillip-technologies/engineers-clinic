<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed — Engineers Clinic</title>
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
                                        <div style="display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25);border-radius:40px;padding:6px 16px;font-size:13px;font-weight:700;color:#e6fffa;margin-bottom:18px;">&#10003; Payment Confirmed</div>
                                        <h1 style="margin:0 0 12px 0;font-size:34px;line-height:1.2;font-weight:800;letter-spacing:-0.3px;">Thank you, {{ $payment->student->user->name }}.</h1>
                                        <p style="margin:0;font-size:17px;line-height:1.6;color:#e6fffa;opacity:0.95;">Your payment has been received and your enrollment is now active.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Payment Summary -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding:36px 36px 24px;">
                                        <p style="margin:0 0 20px 0;font-size:17px;line-height:1.6;color:#2c3e50;">You are now enrolled in the following program. Keep this email as your payment receipt.</p>

                                        <!-- Invoice Box -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f9fbfd;border:1px solid #e2edf2;border-radius:20px;margin:0 0 28px;">
                                            <tr>
                                                <td style="padding:24px 28px;">
                                                    <div style="font-size:12px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#2c7a6e;margin-bottom:20px;border-left:3px solid #0f766e;padding-left:12px;">Invoice / Receipt</div>

                                                    <!-- Invoice header row -->
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid #e2edf2;padding-bottom:14px;margin-bottom:14px;">
                                                        <tr>
                                                            <td style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b8ea0;">Description</td>
                                                            <td align="right" style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:#6b8ea0;">Amount</td>
                                                        </tr>
                                                    </table>

                                                    <!-- Line item -->
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-bottom:1px solid #e2edf2;padding-bottom:14px;margin-bottom:14px;">
                                                        <tr>
                                                            <td style="font-size:15px;color:#0f2b3d;font-weight:600;">{{ $payment->course->title }}</td>
                                                            <td align="right" style="font-size:15px;color:#0f2b3d;font-weight:700;white-space:nowrap;">&#8377;&nbsp;{{ number_format((float) $payment->amount, 2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="padding-top:4px;font-size:13px;color:#6b8ea0;">Program enrollment fee</td>
                                                        </tr>
                                                    </table>

                                                    <!-- Total -->
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="font-size:16px;font-weight:800;color:#0f2b3d;">Total Paid</td>
                                                            <td align="right" style="font-size:20px;font-weight:800;color:#0f766e;white-space:nowrap;">&#8377;&nbsp;{{ number_format((float) $payment->amount, 2) }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Payment Details -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f9fbfd;border:1px solid #e2edf2;border-radius:20px;margin-bottom:28px;">
                                            <tr>
                                                <td style="padding:24px 28px;">
                                                    <div style="font-size:12px;font-weight:800;letter-spacing:1px;text-transform:uppercase;color:#2c7a6e;margin-bottom:16px;border-left:3px solid #0f766e;padding-left:12px;">Payment Details</div>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td style="padding:9px 0;border-bottom:1px solid #e9edf2;color:#4a5b6e;font-size:14px;width:160px;font-weight:500;">Payment Date</td>
                                                            <td style="padding:9px 0;border-bottom:1px solid #e9edf2;color:#0f2b3d;font-size:14px;font-weight:700;">{{ $payment->payment_date ? $payment->payment_date->format('d M Y, h:i A') : now()->format('d M Y, h:i A') }}</td>
                                                        </tr>
                                                        @if($payment->razorpay_payment_id)
                                                        <tr>
                                                            <td style="padding:9px 0;border-bottom:1px solid #e9edf2;color:#4a5b6e;font-size:14px;font-weight:500;">Payment ID</td>
                                                            <td style="padding:9px 0;border-bottom:1px solid #e9edf2;color:#0f2b3d;font-size:14px;font-weight:700;word-break:break-all;">{{ $payment->razorpay_payment_id }}</td>
                                                        </tr>
                                                        @endif
                                                        @if($payment->order && $payment->order->receipt)
                                                        <tr>
                                                            <td style="padding:9px 0;border-bottom:1px solid #e9edf2;color:#4a5b6e;font-size:14px;font-weight:500;">Receipt No.</td>
                                                            <td style="padding:9px 0;border-bottom:1px solid #e9edf2;color:#0f2b3d;font-size:14px;font-weight:700;">{{ $payment->order->receipt }}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td style="padding:9px 0;color:#4a5b6e;font-size:14px;font-weight:500;">Status</td>
                                                            <td style="padding:9px 0;color:#0f2b3d;font-size:14px;font-weight:700;">
                                                                <span style="background:#dcfce7;color:#15803d;padding:4px 14px;border-radius:40px;font-size:13px;font-weight:700;">Paid</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- CTA -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" style="margin:8px 0 20px;">
                                            <tr>
                                                <td style="border-radius:14px;background:#0f766e;box-shadow:0 4px 8px rgba(15,118,110,0.12);">
                                                    <a href="{{ route('dashboard.enrolled-courses') }}" style="display:inline-block;padding:14px 28px;background:#0f766e;color:#ffffff;text-decoration:none;font-size:16px;font-weight:700;border-radius:14px;">Go to my dashboard</a>
                                                </td>
                                            </tr>
                                        </table>

                                        <div style="border-top:1px solid #e2edf2;padding-top:24px;margin-top:16px;">
                                            <p style="margin:0;font-size:15px;line-height:1.6;color:#476b7a;">If you have any questions about your enrollment or need support, please contact us. We're glad to have you on board.</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Footer -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="background:#fafcfd;border-top:1px solid #e2edf2;padding:20px 36px 24px;">
                                        <p style="margin:0;font-size:13px;line-height:1.5;color:#6c8b9b;text-align:center;">This receipt was automatically generated by Engineers Clinic upon payment confirmation.</p>
                                        <p style="margin:12px 0 0 0;font-size:12px;line-height:1.5;color:#90aebb;text-align:center;">&#169; Engineers Clinic — practical engineering education</p>
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
