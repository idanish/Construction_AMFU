<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-header .admin-label {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-top: 10px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body h2 {
            color: #7c3aed;
            font-size: 18px;
            margin-top: 0;
        }
        .admin-icon {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
        .action-badge {
            display: inline-block;
            background-color: #7c3aed;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
        .details-box {
            background-color: #f5f3ff;
            border-left: 4px solid #7c3aed;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #7c3aed;
            display: inline-block;
            width: 120px;
        }
        .timestamp {
            background-color: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 13px;
            color: #666;
        }
        .action-button {
            display: inline-block;
            background-color: #7c3aed;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .action-button:hover {
            background-color: #6d28d9;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔐 Admin Alert</h1>
            <div class="admin-label">ADMINISTRATIVE NOTIFICATION</div>
        </div>
        
        <div class="email-body">
            <div class="admin-icon">👤</div>
            
            <h2>Important Activity Alert</h2>
            
            <p>Dear Administrator,</p>
            
            <p>An important activity has occurred in the system.</p>
            
            <p style="text-align: center; margin: 20px 0;">
                <span class="action-badge">{{ strtoupper($action) }}</span>
            </p>
            
            <p><strong>{{ $modelType }}</strong> ID: <strong>#{{ $modelId }}</strong></p>
            
            @if(!empty($details))
                <div class="details-box">
                    @foreach($details as $key => $value)
                        <p>
                            <span class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <strong>{{ $value }}</strong>
                        </p>
                    @endforeach
                </div>
            @endif
            
            <div class="timestamp">
                <strong>Notification Time:</strong> {{ date('Y-m-d H:i:s') }}<br>
                <strong>Action Type:</strong> {{ ucfirst($action) }}<br>
                <strong>Model Type:</strong> {{ $modelType }}
            </div>
            
            @if($dashboardUrl)
                <center>
                    <a href="{{ $dashboardUrl }}" class="action-button">View in Dashboard</a>
                </center>
            @endif
            
            <p style="color: #999; font-size: 12px; margin-top: 30px;">
                This is an automated notification. Please do not reply directly to this email.
            </p>
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} AMFU. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
