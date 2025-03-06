@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid py-4">
    <!-- İstatistik Kartları -->
    <div class="row g-3 mb-4">
        <!-- Toplam Teklif -->
        <div class="col-md-6 col-lg">
            <div class="card border-0 shadow-sm h-100 bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-dark mb-2">Toplam Teklif</h6>
                            <h3 class="mb-0 text-dark">{{ $totalOffers }}</h3>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3">
                            <i class="fas fa-file-invoice fa-2x text-dark"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-dark small">
                        <i class="fas fa-clock me-1"></i>
                        Son 30 gün: {{ $lastMonthOffers }}
                    </div>
                </div>
            </div>
        </div>

        @foreach($statusStats as $id => $stat)
        <div class="col-md-6 col-lg">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, {{ $stat['color'] }}, {{ adjustBrightness($stat['color'], 20) }});">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-dark mb-2">{{ $stat['name'] }}</h6>
                            <h3 class="mb-0 text-dark">{{ $stat['count'] }}</h3>
                        </div>
                        <div class="icon-box bg-white bg-opacity-25 rounded-3 p-3">
                            @if($id === 'others')
                                <i class="fas fa-ellipsis-h fa-2x text-dark"></i>
                            @else
                                <i class="fas {{ getStatusIcon($stat['name']) }} fa-2x text-dark"></i>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-dark small">Toplam Dağılım</span>
                            <span class="text-dark small">{{ number_format($stat['rate'], 1) }}%</span>
                        </div>
                        <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                            <div class="progress-bar bg-dark" role="progressbar" 
                                 style="width: {{ $stat['rate'] }}%;" 
                                 aria-valuenow="{{ $stat['rate'] }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <div class="mt-2 text-dark small">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Bu ay: {{ $stat['monthlyCount'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- En Çok Teklif Verenler -->
    <div class="row g-3 mb-4">
        <!-- En Çok Teklif Veren Acenteler -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-building text-primary me-2"></i>En Çok Teklif Veren Acenteler
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($topAgencies as $index => $agency)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="rank-circle bg-primary bg-opacity-10 text-primary me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ optional($agency->agency)->name ?? 'Silinmiş Acente' }}</h6>
                                    <small class="text-muted">{{ $agency->total }} Teklif</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="progress" style="width: 100px; height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: {{ ($agency->total / $totalOffers) * 100 }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format(($agency->total / $totalOffers) * 100, 1) }}%</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle mb-2 d-block"></i>
                            Henüz acente verisi bulunmuyor
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- En Çok Teklif Veren Firmalar -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-hotel text-success me-2"></i>En Çok Teklif Veren Firmalar
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($topCompanies as $index => $company)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="rank-circle bg-success bg-opacity-10 text-success me-3">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ optional($company->company)->name ?? 'Silinmiş Firma' }}</h6>
                                    <small class="text-muted">{{ $company->total }} Teklif</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="progress" style="width: 100px; height: 6px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ ($company->total / $totalOffers) * 100 }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format(($company->total / $totalOffers) * 100, 1) }}%</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle mb-2 d-block"></i>
                            Henüz firma verisi bulunmuyor
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
    .rank-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    </style>

    <!-- Teklif Tablosu -->
    <div class="row g-4">
        <!-- Devam Eden Teklifler -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-spinner text-warning me-2"></i>Devam Eden Teklifler
                        </h5>
                        <a href="{{ route('offers.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Tümünü Gör
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Acenta</th>
                                <th>Otel</th>
                                <th>Yetkili</th>
                                <th>Giriş</th>
                                <th>Çıkış</th>
                                <th>Oda/Kişi</th>
                                <th>Durum</th>
                                <th class="text-end">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingOffersList as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td>
                                <td>{{ optional($offer->agency)->name ?? '-' }}</td>
                                <td>{{ optional($offer->company)->name ?? '-' }}</td>
                                <td>{{ $offer->full_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($offer->checkin_date)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($offer->checkout_date)->format('d.m.Y') }}</td>
                                <td>{{ $offer->room_count }} / {{ $offer->pax_count }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $offer->status->color }}">
                                        {{ $offer->status->name }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm shadow-sm">
                                        <button type="button" 
                                                class="btn btn-xs btn-soft-info view-offer" 
                                                data-offer-id="{{ $offer->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewOfferModal">
                                            <i class="fas fa-eye fa-sm"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-xs btn-soft-primary edit-offer" 
                                                data-offer-id="{{ $offer->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editOfferModal_{{ $offer->id }}"
                                                title="Düzenle">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                        Devam eden teklif bulunmuyor
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Onaylı Teklifler -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-check-circle text-success me-2"></i>Onaylı Teklifler
                        </h5>
                        <a href="{{ route('offers.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i>Tümünü Gör
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>ID</th>
                                <th>Acenta</th>
                                <th>Otel</th>
                                <th>Yetkili</th>
                                <th>Giriş</th>
                                <th>Çıkış</th>
                                <th>Oda/Kişi</th>
                                <th>Durum</th>
                                <th class="text-end">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($approvedOffersList as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td>
                                <td>{{ optional($offer->agency)->name ?? '-' }}</td>
                                <td>{{ optional($offer->company)->name ?? '-' }}</td>
                                <td>{{ $offer->full_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($offer->checkin_date)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($offer->checkout_date)->format('d.m.Y') }}</td>
                                <td>{{ $offer->room_count }} / {{ $offer->pax_count }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $offer->status->color }}">
                                        {{ $offer->status->name }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm shadow-sm">
                                        <button type="button" 
                                                class="btn btn-xs btn-soft-info view-offer" 
                                                data-offer-id="{{ $offer->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewOfferModal">
                                            <i class="fas fa-eye fa-sm"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-xs btn-soft-primary edit-offer" 
                                                data-offer-id="{{ $offer->id }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editOfferModal_{{ $offer->id }}"
                                                title="Düzenle">
                                            <i class="fas fa-edit fa-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                        Onaylı teklif bulunmuyor
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<x-offer-view-modal id="viewOfferModal" />

<!-- Edit Modals -->
@foreach($pendingOffersList as $offer)
    <x-offer-modal 
        id="editOfferModal_{{ $offer->id }}"
        title="Teklif Düzenle"
        :statuses="$statuses"
        :companies="$companies"
        :agencies="$agencies"
        :offer="$offer"
        action="{{ route('offers.update', $offer->id) }}"
        method="PUT"
    />
@endforeach

@foreach($approvedOffersList as $offer)
    <x-offer-modal 
        id="editOfferModal_{{ $offer->id }}"
        title="Teklif Düzenle"
        :statuses="$statuses"
        :companies="$companies"
        :agencies="$agencies"
        :offer="$offer"
        action="{{ route('offers.update', $offer->id) }}"
        method="PUT"
    />
@endforeach

@include('dashboard.styles')

@push('scripts')
@include('dashboard.scripts')
<script>
function adjustBrightness(hex, percent) {
    hex = hex.replace('#', '');
    
    let r = parseInt(hex.substr(0, 2), 16);
    let g = parseInt(hex.substr(2, 2), 16);
    let b = parseInt(hex.substr(4, 2), 16);

    r = parseInt(r * (100 + percent) / 100);
    g = parseInt(g * (100 + percent) / 100);
    b = parseInt(b * (100 + percent) / 100);

    r = Math.min(r, 255);
    g = Math.min(g, 255);
    b = Math.min(b, 255);

    const rr = ((r.toString(16).length == 1) ? "0" + r.toString(16) : r.toString(16));
    const gg = ((g.toString(16).length == 1) ? "0" + g.toString(16) : g.toString(16));
    const bb = ((b.toString(16).length == 1) ? "0" + b.toString(16) : b.toString(16));

    return "#" + rr + gg + bb;
}

@php
function getStatusIcon($statusName) {
    $icons = [
        'Devam Ediyor' => 'fa-clock',
        'Onaylandı' => 'fa-check-circle',
        'İptal Edildi' => 'fa-times-circle',
        'Tamamlandı' => 'fa-check-double',
        'Beklemede' => 'fa-pause-circle',
        'Diğer Durumlar' => 'fa-ellipsis-h'
    ];
    
    return $icons[$statusName] ?? 'fa-circle';
}
@endphp
</script>
@endpush

@endsection

@section('styles')
<style>
.btn-xs {
    padding: 0.15rem 0.35rem;
    font-size: 0.7rem;
    line-height: 1;
    border-radius: 0.15rem;
    height: 22px;
}

.btn-xs i {
    font-size: 0.7rem;
    line-height: 1;
    vertical-align: middle;
    margin-top: -2px;
}

.btn-group-sm {
    height: 22px;
}

.btn-group-sm .btn + .btn {
    margin-left: 1px;
}

.btn-soft-primary {
    color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.1);
    border-color: transparent;
}

.btn-soft-primary:hover {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-soft-info {
    color: #0dcaf0;
    background-color: rgba(13, 202, 240, 0.1);
    border-color: transparent;
}

.btn-soft-info:hover {
    color: #fff;
    background-color: #0dcaf0;
    border-color: #0dcaf0;
}
</style>
@endsection