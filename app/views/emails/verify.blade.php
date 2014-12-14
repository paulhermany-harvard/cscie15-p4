<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Please verify your email address.</h2>
        <div>
            <p>Thanks for creating an account with Configurely.com.</p>
            <p>Please follow the link below to verify your email address:<br/>
            {{ URL::to('verify/' . $user->confirmation_code) }}</p>
        </div>
    </body>
</html>