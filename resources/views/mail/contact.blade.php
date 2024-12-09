<x-mail::message>
    Nom: {{ $name }}
    Email: {{ $email }}
    Company: {{ $company }}
    Phone: {{ $phone }}
    Connecté : {{ $loggedIn ? 'Oui' : 'Non' }}

    Sujet : {{ $contactSubject }}

    Message:

    {{ $message }}

<br>
{{ config('app.name') }}
</x-mail::message>
