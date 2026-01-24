<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; }
        .button { display: inline-block; padding: 15px 30px; background: #3b82f6; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">{{ $ad->title }}</h1>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>
            
            @if($ad->image_url)
                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="width: 100%; border-radius: 8px; margin: 20px 0;">
            @endif

            <p>{{ $ad->description }}</p>

            @if($ad->link_url)
                <div style="text-align: center;">
                    <a href="{{ $ad->link_url }}" class="button">Learn More</a>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} FarmVax. All rights reserved.</p>
        </div>
    </div>
</body>
</html>