<x-mail::message>
# Yeni iletişim mesajı

**Ad:** {{ $contactMessage->name }}

**E-posta:** {{ $contactMessage->email }}

@if($contactMessage->phone)
**Telefon:** {{ $contactMessage->phone }}
@endif

@if($contactMessage->subject)
**Konu:** {{ $contactMessage->subject }}
@endif

**Mesaj:**

{{ $contactMessage->message }}

<x-mail::button :url="url('/admin/contact-messages')">
Admin panelde görüntüle
</x-mail::button>

</x-mail::message>
