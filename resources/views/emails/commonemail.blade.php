<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ @$title == '' ? env('APP_NAME') : $title }}</title>
</head>

<body>
    <div
        style="max-width: 480px;margin: 1rem auto;border-radius: 10px;border-top: #2355C4 2px solid;border-bottom: #2355C4 2px solid;box-shadow: 0 2px 18px rgba(0, 0, 0, 0.2);padding: 1.5rem;font-family: Arial, Helvetica, sans-serif;">
        <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.2);padding-bottom: 1rem;">
            <div style="max-width: 240px;padding: 0 0.5rem;display: block;margin: 0 auto;">
                <img src="{{ @$logourl }}" style="width: 100%;" alt="logo" />
            </div>
        </div>
        <div class="email-body">
            <div style="padding: 2rem 0 1rem;text-align: center;font-size: 1.15rem;">
                <div style="font-weight: bold;">Dear, {{ @$name }}!</div>
                {!! $messages !!}
            </div>
            <p style="text-align:center;">Best regards,<br>[{{ env('APP_NAME') }} team]</p>
            <div
                style="padding: 1rem 0 1rem;text-align: center;font-size: 0.8rem;border-top: 1px solid rgba(0, 0, 0, 0.2);border-bottom: 1px solid rgba(0, 0, 0, 0.2);margin-top:1rem;">
                If you did not sign up for this account you can safely ignore and delete this email. </div>
            <div style="display: inline-block; text-align: center; color: rgba(119, 119, 119, 1); font-family: &quot;Lato&quot;, Helvetica, Arial, sans-serif"
                align="center">
                <div
                    style="font-size: 12px; color: rgba(119, 119, 119, 1); margin: 12px auto 0px; font-family: &quot;Lato&quot;, Helvetica, Arial, sans-serif">
                    <p style="font-size: 11px"><b> Note :- </b> Do not reply to this email,this email was auto-generated by the sender's security system. </p>
                    <p
                        style="line-height: 1; font-size: 12px; margin-bottom:0px; padding: 0; color: rgba(119, 119, 119, 1); font-family: &quot;Lato&quot;, Helvetica, Arial, sans-serif">
                        All Rights Reserved. </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>