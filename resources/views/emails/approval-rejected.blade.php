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
            background: linear-gradient(135deg, #ea4335 0%, #c5221f 100%);
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
            color: #ea4335;
            font-size: 18px;
            margin-top: 0;
        }
        .warning-icon {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
        .reason-box {
            background-color: #ffebee;
            border-left: 4px solid #ea4335;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .reason-box h3 {
            margin: 0 0 10px 0;
            color: #ea4335;
        }
        .reason-box p {
            margin: 0;
            color: #666;
            font-style: italic;
        }
        .details-box {
            background-color: #f9f9f9;
            border-left: 4px solid #999;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box p {
            margin: 8px 0;
        }
        .label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 120px;
        }
        .action-button {
            display: inline-block;
            background-color: #ea4335;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }
        .action-button:hover {
            background-color: #c5221f;
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
            background-color: #ea4335;
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
            <h1>❌ Request Rejected</h1>
        </div>
        
        <div class="email-body">
            <div class="warning-icon">⚠</div>
            
            <h2>Your {{ $modelType }} Requires Revision</h2>
            
            <p>Dear Requester,</p>
            
            <p>Your <strong>{{ $modelType }}</strong> (ID: <strong>#{{ $modelId }}</strong>) was rejected at the <span class="step-badge">{{ $approvalStep }}</span> approval step.</p>
            
            <p><strong>Rejected By:</strong> {{ $rejectedBy }}</p>
            
            @if($rejectionReason)
                <div class="reason-box">
                    <h3>Reason for Rejection:</h3>
                    <p>{{ $rejectionReason }}</p>
                </div>
            @endif
            
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
            
            <p><strong>What's Next?</strong></p>
            <ul>
                <li>Review the rejection reason carefully</li>
                <li>Make the necessary corrections</li>
                <li>Resubmit the {{ $modelType }} for approval</li>
            </ul>
            
            @if($resubmitUrl)
                <center>
                    <a href="{{ $resubmitUrl }}" class="action-button">Edit & Resubmit</a>
                </center>
            @endif
            
            <p>If you have questions about the rejection, please contact the approver or your department head.</p>
            
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
