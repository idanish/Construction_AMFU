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
            background: linear-gradient(135deg, #4285f4 0%, #1f6ef0 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body h2 {
            color: #4285f4;
            font-size: 18px;
            margin-top: 0;
        }
        .info-icon {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
        .details-box {
            background-color: #f0f5ff;
            border-left: 4px solid #4285f4;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #4285f4;
            display: inline-block;
            width: 120px;
        }
        .submitted-by {
            background-color: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 14px;
        }
        .action-button {
            display: inline-block;
            background-color: #4285f4;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .action-button:hover {
            background-color: #1f6ef0;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
        }
        .status-badge {
            display: inline-block;
            background-color: #fbbc04;
            color: #333;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📋 New Submission</h1>
        </div>
        
        <div class="email-body">
            <div class="info-icon">📤</div>
            
            <h2>New {{ $modelType }} Submitted for Approval</h2>
            
            <p>Dear Team,</p>
            
            <p>A new <strong>{{ $modelType }}</strong> (ID: <strong>#{{ $modelId }}</strong>) has been submitted by <strong>{{ $submittedBy }}</strong> and is now in <span class="status-badge">PENDING APPROVAL</span> status.</p>
            
            @if(!empty($modelDetails))
                <div class="details-box">
                    @foreach($modelDetails as $key => $value)
                        <p>
                            <span class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            {{ $value }}
                        </p>
                    @endforeach
                </div>
            @endif
            
            <div class="submitted-by">
                <strong>Submitted By:</strong> {{ $submittedBy }}<br>
                <strong>Submission Date:</strong> {{ date('Y-m-d H:i:s') }}
            </div>
            
            <p>This {{ $modelType }} will go through a 5-step approval process:</p>
            <ol>
                <li>PM Manager Review</li>
                <li>PMO Review</li>
                <li>FCO Review</li>
                <li>CSO Review</li>
                <li>Admin Final Approval</li>
            </ol>
            
            <p>You will receive notifications at each approval step.</p>
            
            @if($approvalUrl)
                <center>
                    <a href="{{ $approvalUrl }}" class="action-button">View Submission</a>
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
