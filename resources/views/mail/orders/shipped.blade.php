<x-mail::message>
# Introduction

The body of your message.
`hghgh`

<x-mail::button :url="''" color='success'>
        Button Text {{ $name }}
 </x-mail::button>

    {{-- ` Thanks `,<br> --}}
    {{ config('app.name') }}

<x-mail::panel color='success'>
    THis is ok
</x-mail::panel>

<x-mail::table>
        | Laravel | Table | Example |
        | ------------- |:-------------:| --------:|
        | Col 2 is | Centered | $10 |
        | Col 3 is | Right-Aligned | $20 |
</x-mail::table>
</x-mail::message>
