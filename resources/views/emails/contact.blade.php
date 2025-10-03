<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Formular de Contact Nou</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        h1 {
            font-size: 28px;
            color: #ff5914;
        }

        h2 {
            font-size: 20px;
        }

        a {
            color: #ff5914;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        strong {
            font-weight: bold;
        }

        .type {
            background-color: rgba(255, 89, 20, 0.1); /* culoare + transparență */
            border: 1px solid #ff5914;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>A fost primită o nouă solicitare de contact!</h1>
    <p>Mai jos sunt detaliile completate în formular:</p>
    <ul>
        <li><strong>Nume:</strong> {{ $contact->name }} {{ $contact->surname }}</li>
        <li><strong>Email:</strong> {{ $contact->email }}</li>
        <li><strong>Telefon:</strong> {{ $contact->phone }}</li>
        <li><strong>Companie:</strong> {{ $contact->company ?? 'N/A' }}</li>
        <li><strong>Job:</strong> {{ $contact->job }}</li>
        <li><strong>Industrie:</strong> {{ $contact->industry }}</li>
        <li><strong>Țară:</strong> {{ $contact->country }}</li>
        <li><strong>Județ:</strong> {{ $contact->county }}</li>
        <li><strong>Oraș:</strong> {{ $contact->city }}</li>
        <li><strong>Sursă:</strong> {{ $contact->how_about }}</li>
    </ul>
    <hr>
    <p><strong>Mesaj:</strong><br>{{ $contact->message }}</p>

    <div class="type">
        @if($contact->product)
            <h2>PRODUS</H2>
            <p><strong>Admin {{ $contact->locale }}:</strong>
                <a href="{{ url('') . "/admin/products/$contact->product/edit" }}">{{ $contact->product_name }}</a>
            </p>
            <p><strong>Frontend {{ $contact->locale }}:</strong>
                <a href="{{ url('') . "/$contact->locale/$contact->application_slug/$contact->category_slug/$contact->product_slug" }}">{{ $contact->product_name }}</a>
            </p>
            @if($contact->locale != 'en')
                <p><strong>Frontend EN:</strong>
                    <a href="{{ url('') . "/en/$contact->application_slug_en/$contact->category_slug_en/$contact->product_slug_en" }}">{{ $contact->product_name_en }}</a>
                </p>
            @endif
        @else
            <h2>HOMEPAGE</H2>
        @endif
    </div>
</div>
</body>
</html>
