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
            padding: 40px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body h2 {
            color: #667eea;
            font-size: 18px;
            margin-top: 0;
        }
        .welcome-box {
            background-color: #f0f5ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .welcome-box p {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
            color: #667eea;
            display: inline-block;
            width: 140px;
        }
        .credentials-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .credentials-box h3 {
            color: #856404;
            margin-top: 0;
            font-size: 14px;
        }
        .credentials-box p {
            margin: 5px 0;
            font-family: monospace;
            background-color: #fff;
            padding: 8px;
            border-radius: 3px;
            word-break: break-all;
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
        .steps-list {
            background-color: #f9f9f9;
            padding: 15px 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .steps-list ol {
            margin: 0;
            padding-left: 20px;
        }
        .steps-list li {
            margin: 10px 0;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
        }
        .security-notice {
            background-color: #ffe8e8;
            border-left: 4px solid #ea4335;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            font-size: 13px;
            color: #666;
        }
        .security-notice strong {
            color: #ea4335;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎉 Welcome to AMFU!</h1>
            <p>Your account has been successfully created</p>
        </div>
        
        <div class="email-body">
            <h2>Hello {{ $userName }}!</h2>
            
            <p>We're excited to welcome you to the AMFU (Architecture & Financial Management Unit) system. Your account has been set up and is ready to use.</p>
            
            <div class="welcome-box">
                <p>
                    <span class="label">Account Email:</span>
                    <strong>{{ $userEmail }}</strong>
                </p>
                <p>
                    <span class="label">Account Name:</span>
                    <strong>{{ $userName }}</strong>
                </p>
                <p>
                    <span class="label">Status:</span>
                    <strong style="color: #34a853;">Active ✓</strong>
                </p>
            </div>
            
            @if($temporaryPassword)
                <div class="credentials-box">
                    <h3>🔐 Temporary Login Credentials</h3>
                    <p><strong>Email:</strong><br>{{ $userEmail }}</p>
                    <p><strong>Temporary Password:</strong><br>{{ $temporaryPassword }}</p>
                </div>
                
                <div class="security-notice">
                    <strong>⚠ Security Notice:</strong> Please change your password immediately after your first login. Do not share this temporary password with anyone.
                </div>
            @endif
            
            <div class="steps-list">
                <strong>Next Steps:</strong>
                <ol>
                    <li>Visit the login page using the button below</li>
                    <li>Enter your email and temporary password</li>
                    @if($temporaryPassword)
                        <li>Change your password to something secure</li>
                    @endif
                    <li>Start using the AMFU system</li>
                </ol>
            </div>
            
            @if($loginUrl)
                <center>
                    <a href="{{ $loginUrl }}" class="action-button">Go to Login</a>
                </center>
            @endif
            
            <p><strong>Features available to you:</strong></p>
            <ul>
                <li>Submit and track requests</li>
                <li>Approve finance documents (if authorized)</li>
                <li>View approval history and status</li>
                <li>Manage notifications</li>
                <li>Access reports and dashboards</li>
            </ul>
            
            <p>If you have any questions or need assistance, please contact the IT department or reply to this email.</p>
            
            <p style="color: #999; font-size: 12px; margin-top: 30px;">
                This is an automated notification. Please do not reply directly to this email unless you're responding to IT support.
            </p>
        </div>
        
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} AMFU. All rights reserved.</p>
            <p>If you did not create this account, please contact us immediately.</p>
        </div>
    </div>
</body>
</html>
