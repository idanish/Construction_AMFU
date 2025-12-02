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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
            font-size: 18px;
            margin-top: 0;
        }
        .details-box {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #667eea;
            display: inline-block;
            width: 120px;
        }
        .action-button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .action-button:hover {
            background-color: #5568d3;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
        }
        .step-badge {
            display: inline-block;
            background-color: #667eea;
            color: white;
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
            <h1>⏳ Approval Pending</h1>
        </div>
        
        <div class="email-body">
            <h2>Action Required - {{ $modelType }} Approval</h2>
            
            <p>Dear Team,</p>
            
            <p>A new <strong>{{ $modelType }}</strong> (ID: <strong>#{{ $modelId }}</strong>) is waiting for your approval at the <span class="step-badge">{{ $approvalStep }}</span> step.</p>
            
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
            
            <p>Please review the details carefully and take action as soon as possible.</p>
            
            @if($actionUrl)
                <center>
                    <a href="{{ $actionUrl }}" class="action-button">Review & Approve</a>
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
