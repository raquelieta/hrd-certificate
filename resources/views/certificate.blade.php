<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="https://cdn.tailwindcss.com"></script>
        <title inertia>{{ config('app.name', 'Certificate') }}</title>
    </head>

    <body>
        <div class="">
            <div class="w-3/6 mx-auto sm:px-6 lg:px-8">
                <h1>Certificate of Completion</h1>
                <p style="color:#000;">{{ $participant->name }}</p>
            </div>
        </div>
    </body>
</html>
