@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Teklif Düzenle</h2>
        <form action="{{ route('offers.update', $offer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="agency" class="form-label">Acenta</label>
                    <input type="text" class="form-control" id="agency" name="agency" value="{{ $offer->agency }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="company" class="form-label">Firma</label>
                    <input type="text" class="form-control" id="company" name="company" value="{{ $offer->company }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="full_name" class="form-label">Adı Soyadı</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $offer->full_name }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="checkin_date" class="form-label">Giriş Tarihi</label>
                    <input type="date" class="form-control" id="checkin_date" name="checkin_date" value="{{ $offer->checkin_date }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="checkout_date" class="form-label">Çıkış Tarihi</label>
                    <input type="date" class="form-control" id="checkout_date" name="checkout_date" value="{{ $offer->checkout_date }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="room_count" class="form-label">Oda Sayısı</label>
                    <input type="number" class="form-control" id="room_count" name="room_count" value="{{ $offer->room_count }}" min="1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pax_count" class="form-label">Pax Sayısı</label>
                    <input type="number" class="form-control" id="pax_count" name="pax_count" value="{{ $offer->pax_count }}" min="1" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="option_date" class="form-label">Opsiyon Tarihi</label>
                    <input type="date" class="form-control" id="option_date" name="option_date" value="{{ $offer->option_date }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status_id" class="form-label">Durum</label>
                    <select class="form-select" id="status_id" name="status_id" required>
                        <option value="">Seçiniz</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" 
                                    {{ $offer->status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="file" class="form-label">Dosya Ekle / Güncelle</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
            </div>

            <button type="submit" class="btn btn-success">Güncelle</button>
            <a href="{{ route('offers.index') }}" class="btn btn-secondary">Geri Dön</a>
        </form>
    </div>
@endsection
