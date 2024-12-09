<x-mail::message>

    Bonjour,

    Nom: {{ $name }}
    Email: {{ $email }}
    Company: {{ $company }}
    Phone: {{ $phone }}

    Message:

    {{ $message }}

<br>
{{ config('app.name') }}
</x-mail::message>
