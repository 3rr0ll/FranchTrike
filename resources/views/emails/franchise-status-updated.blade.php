<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Status Update</title>
    <style>
        /* Franchtrike color palette based on Tailwind config */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1e293b; /* slate-800 */
            background: #f8fafc; /* slate-50 */
        }
        .container {
            max-width: 600px;
            margin: 32px auto;
            padding: 32px 24px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px 0 rgba(16, 24, 40, 0.08);
            border: 1px solid #e5e7eb; /* gray-200 */
        }
        .header {
            background: #0a2240; /* primary-navy */
            color: #ffd700; /* primary-gold */
            padding: 20px 0 16px 0;
            border-radius: 12px 12px 0 0;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 24px;
        }
        .badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            margin-left: 6px;
            vertical-align: middle;
        }
        .approved {
            background: #e6ffed; 
            color: #0a2240;
            border: 1px solid #16a34a; 
        }
        .rejected {
            background: #fff1f2; 
            color: #991b1b; 
            border: 1px solid #dc2626; 
        }
        .under_review, .submitted {
            background: #fef9c3; 
            color: #854d0e;
            border: 1px solid #facc15; 
        }
        .info-box {
            background: #f3f4f6; 
            border-left: 4px solid #ffd700; 
            padding: 16px 20px;
            border-radius: 8px;
            margin: 18px 0;
            color: #0a2240;
        }
        .footer {
            margin-top: 32px;
            text-align: center;
            color: #64748b; 
            font-size: 15px;
        }
        a.button {
            display: inline-block;
            background: #ffd700; 
            color: #0a2240; 
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            margin-top: 24px;
            box-shadow: 0 2px 8px 0 rgba(16, 24, 40, 0.06);
            transition: background 0.2s;
        }
        a.button:hover {
            background: #facc15; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Franchtrike Franchise Update
        </div>
        <p style="font-size:1.1rem; margin-bottom: 18px;">
            Hello <strong>{{ $application->operator->full_name ?? $application->operator_name }}</strong>,
        </p>
        <div class="info-box">
            Your franchise application <strong>#{{ $application->application_number }}</strong> is now:
            <span class="badge {{ $status }}">{{ ucfirst(str_replace('_',' ', $status)) }}</span>
        </div>

        @if($status === 'approved')
            <p style="color:#16a34a; font-weight:600; margin-bottom:10px;">🎉 Congratulations! Your application has been <span style="color:#0a2240;">approved</span>.</p>
            @if($application->franchise_end_date)
                <p>Franchise End Date: <strong style="color:#0a2240;">{{ \Carbon\Carbon::parse($application->franchise_end_date)->format('M d, Y') }}</strong></p>
            @endif
            <a href="{{ url('/') }}" class="button">Go to Dashboard</a>
        @elseif($status === 'rejected')
            <p style="color:#991b1b; font-weight:600; margin-bottom:10px;">We’re sorry to inform you that your application was <span style="color:#991b1b;">rejected</span>.</p>
            @if($application->rejection_reason)
                <p>Reason: <em style="color:#991b1b;">{{ $application->rejection_reason }}</em></p>
            @endif
        @else
            <p>Your application is currently <span style="color:#854d0e;">under review</span>. We will notify you once a decision has been made.</p>
        @endif

        <div class="footer">
            Thank you,<br>
            <span style="color:#0a2240; font-weight:600;">Franchtrike Team</span>
        </div>
    </div>
</body>
</html>
