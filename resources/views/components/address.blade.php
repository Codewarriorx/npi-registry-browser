@props(['address'])
<div>
    <span class="d-block">{{ $address['address_1'] }}</span>
    <span class="d-block">{{ $address['address_2'] ?? '' }}</span>
    {{ $address['city'] }},
    {{ $address['state'] }}
    {{ substr($address['postal_code'], 0, 5) . '-' . substr($address['postal_code'], 5) }}
    <span class="d-block">{{ $address['country_name'] }}</span>

    <span class="d-block">
        @if (isset($address['telephone_number']))
        Phone: {{ $address['telephone_number'] }}
        @endif
        @if (isset($address['fax_number']))
        | Fax: {{ $address['fax_number'] }}
        @endif
    </span>
</div>
