<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 403</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }

        h1 {
            font-size: 3rem;
            color: #333;
        }

        p {
            font-size: 1.2rem;
            color: #666;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<h1>Error 403</h1>
<p>Forbidden: You do not have permission to access this page.</p>
<a href="{{ route('home.'.app()->getLocale()) }}">Go to Homepage</a>
</body>
</html>