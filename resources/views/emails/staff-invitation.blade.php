<!DOCTYPE html>
<html>
<head>
    <title>Staff Invitation - Hasta Car Rental</title>
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background-color: #bc3737; color: white; padding: 25px; text-align: center; }
        .logo { max-width: 150px; margin-bottom: 15px; }
        .content { padding: 30px; }
        .button { display: inline-block; padding: 12px 30px; background-color: #bc3737; color: white; text-decoration: none; border-radius: 25px; font-weight: 600; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; padding: 20px; background-color: #f8f9fa; color: #666; font-size: 12px; border-top: 1px solid #eee; }
        .link-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; word-break: break-all; font-size: 14px; border-left: 4px solid #bc3737; }
        .steps { margin: 25px 0; padding-left: 20px; }
        .steps li { margin-bottom: 10px; }
        .expiry-note { background: #fff3cd; padding: 10px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 You're Invited!</h1>
            <p>Join Hasta Car Rental as Staff Member</p>
        </div>
        
        <div class="content">
            <h2>Hello!</h2>
            
            <p>You have been invited by <strong>{{ $inviterName ?? 'Hasta Admin' }}</strong> to join Hasta Car Rental as a staff member.</p>
            
            <h3>📋 Registration Steps:</h3>
            <ol class="steps">
                <li><strong>Click the registration button below</strong></li>
                <li><strong>Complete your personal details</strong> (name, IC number, phone)</li>
                <li><strong>Set your password</strong></li>
                <li><strong>Confirm your position</strong></li>
            </ol>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $registrationUrl }}" class="button">🚀 Complete Registration</a>
            </div>
            
            <p>Or copy and paste this registration link into your browser:</p>
            <div class="link-box">
                {{ $registrationUrl }}
            </div>
            
            <div class="expiry-note">
                ⏰ <strong>Important:</strong> This invitation link will expire on <strong>{{ \Carbon\Carbon::parse($user->invitation_expires_at)->format('F j, Y \\a\\t g:i A') }}</strong>
            </div>
            
            <p><strong>Role:</strong> {{ ucfirst($user->userType) }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            
            <p>If you have any questions or didn't request this invitation, please contact your administrator.</p>
            
            <p>Best regards,<br>
            <strong>Hasta Car Rental Team</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Hasta Car Rental. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>