<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ @$title == '' ? env('APP_NAME') : $title }}</title>
</head>
</head>

<body>
    <div
        style="max-width: 480px;margin: 1rem auto;border-radius: 10px;border-top: #2355C4 2px solid;border-bottom: #2355C4 2px solid;box-shadow: 0 2px 18px rgba(0, 0, 0, 0.2);padding: 1.5rem;font-family: Arial, Helvetica, sans-serif;">
        <div style="border-bottom: 1px solid rgba(0, 0, 0, 0.2);padding-bottom: 1rem;">
            <div style="max-width: 240px;padding: 0 0.5rem;display: block;margin: 0 auto;">
                <img src="{{ image_path('logo.png') }}" style="width: 100%;" alt="logo" />
            </div>
        </div>
        <div class="email-body">
            <div style="padding: 2rem 0 1rem;text-align: center;font-size: 1.15rem;">
                <div style="font-weight: bold;">Dear, {{ @$users_name }}!</div>
            </div>
            <p style="text-align:center; ">We are sending this email as part of our commitment to ensuring the security
                of your account,
                we have generated a temporary password for your account.<br><strong>we strongly recommend that you
                    change it immediately upon logging in</strong>.</p>

            <p style="color:#455056; font-size:18px;line-height:20px; margin:0; font-weight: 500;text-align:center;">
                <strong
                    style="display: block; font-size: 13px; margin: 24px 0 4px 0; font-weight:normal; color:rgba(0,0,0,.64);">Here
                    is the temporary password.</strong>
                {{ $password }}
            </p>

            <p style="text-align:center;">Remember to keep your password confidential and avoid sharing it with anyone.
                Regularly update your password and refrain from using easily guessable information or sequences.</p>
            <p style="text-align:center;">Best regards,<br>[{{ env('APP_NAME') }} team]</p>

            <div
                style="padding: 1rem 0 1rem;text-align: center;font-size: 0.8rem;border-top: 1px solid rgba(0, 0, 0, 0.2);border-bottom: 1px solid rgba(0, 0, 0, 0.2);margin-top:1rem;">
                If you did not sign up for this account you can safely ignore and delete this email. </div>
            <div style="display: inline-block; text-align: center; color: rgba(119, 119, 119, 1); font-family: &quot;Lato&quot;, Helvetica, Arial, sans-serif"
                align="center">
                <div
                    style="font-size: 12px; color: rgba(119, 119, 119, 1); margin: 12px auto 0px; font-family: &quot;Lato&quot;, Helvetica, Arial, sans-serif">
                    <p style="font-size: 11px"><b> Note :- </b> Do not reply to this email,this email was auto-generated
                        by the sender's security system. </p>
                    <p
                        style="line-height: 1; font-size: 12px; margin-bottom:0px; padding: 0; color: rgba(119, 119, 119, 1); font-family: &quot;Lato&quot;, Helvetica, Arial, sans-serif">
                        All Rights Reserved. </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
