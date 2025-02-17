<x-mail::message>
    # You have been contact via your listing on CircleCity.Pro *Name:* {{ $contact->name }} *Email:*
    {{ $contact->email }} *Message:*

    {{ $contact->message }}

    Thanks,
    <br />
    {{ config('app.name') }}
</x-mail::message>
