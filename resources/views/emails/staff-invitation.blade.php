<!DOCTYPE html>
<html>
<head>
    <title>Staff Registration Invitation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #bc3737; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background-color: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { 
            display: inline-block; 
            background-color: #bc3737; 
            color: white; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 5px; 
            margin: 20px 0;
            font-weight: bold;
        }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>You're Invited to Join Hasta!</h1>
        </div>
        
        <div class="content">
            <h2>Staff Registration Invitation</h2>
            
            <p>Hello,</p>
            
            <p>You have been invited to join <strong>Hasta</strong> as a staff member.</p>
            
            <p><strong>Your assigned role:</strong> {{ ucfirst($user->userType) }}</p>
            
            <p>To complete your registration and set up your account, please click the button below:</p>
            
            <p style="text-align: center;">
                <a href="{{ $registrationUrl }}" class="button">
                    Complete Registration
                </a>
            </p>
            
            <p><strong>Important:</strong> This invitation link will expire on <strong>{{ $expiresAt }}</strong>.</p>
            
            <p>If you did not expect this invitation, please ignore this email.</p>
            
            <p>Best regards,<br>The Hasta Team</p>
        </div>
        
        <div class="footer">
            <p>This email was sent to: {{ $user->email }}</p>
            <p>If you're having trouble with the button above, copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #bc3737;">{{ $registrationUrl }}</p>
        </div>
    </div>
</body>
</html>