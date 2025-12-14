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
            background: linear-gradient(135deg, #34a853 0%, #1e8e3e 100%);
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
            color: #34a853;
            font-size: 18px;
            margin-top: 0;
        }
        .success-icon {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
        .details-box {
            background-color: #f0f8f0;
            border-left: 4px solid #34a853;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #34a853;
            display: inline-block;
            width: 120px;
        }
        .action-button {
            display: inline-block;
            background-color: #34a853;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .action-button:hover {
            background-color: #1e8e3e;
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
            background-color: #34a853;
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
            <h1>✅ Fully Approved</h1>
        </div>
        
        <div class="email-body">
            <div class="success-icon">✓</div>
            
            <h2>Congratulations! Your {{ $modelType }} Has Been Approved</h2>
            
            <p>Dear Requester,</p>
            
            <p>We're pleased to inform you that your <strong>{{ $modelType }}</strong> (ID: <strong>#{{ $modelId }}</strong>) has successfully passed all approval steps and is now <span class="status-badge">APPROVED</span>.</p>
            
            <p>Your request is now fully approved and ready for implementation.</p>
            
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
            
            @if($viewUrl)
                <center>
                    <a href="{{ $viewUrl }}" class="action-button">View Details</a>
                </center>
            @endif
            
            <p>Thank you for submitting your request. If you have any questions, please contact the relevant department.</p>
            
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
