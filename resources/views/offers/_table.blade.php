@foreach($offers as $offer)
<tr>
    <td>{{ $offer->agency ?? '-' }}</td>
    <td>{{ $offer->company ?? '-' }}</td>
    <td>{{ $offer->full_name }}</td>
    <td>{{ $offer->checkin_date->format('d.m.Y') }}</td>
    <td>{{ $offer->checkout_date->format('d.m.Y') }}</td>
    <td>{{ $offer->room_count }}</td>
    <td>{{ $offer->pax_count }}</td>
    <td>{{ $offer->option_date->format('d.m.Y') }}</td>
    <td>
        <span class="badge" style="background-color: {{ $offer->status->color }}">
            {{ $offer->status->name }}
        </span>
    </td>
    <td>
        <button type="button" class="btn btn-sm btn-primary" onclick="editOffer({{ $offer->id }})">
            <i class="fas fa-edit"></i>
        </button>
        <button type="button" class="btn btn-sm btn-danger" onclick="deleteOffer({{ $offer->id }})">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
@endforeach 