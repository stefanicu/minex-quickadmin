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
            font-size: 20px;
            color: #0056b3;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        strong {
            font-weight: bold;
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
            @if($contact->product)
                <li><strong>Produs de interes:</strong> ID {{ $contact->product }}</li>
            @endif
            <li><strong>Sursă:</strong> {{ $contact->how_about }}</li>
        </ul>
        <hr>
        <p><strong>Mesaj:</strong><br>{{ $contact->message }}</p>
    </div>
</body>
</html>
