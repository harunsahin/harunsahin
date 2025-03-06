@push('styles')
<style>
/* Gradient Arkaplanlar */
.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #f6c23e, #f4b619);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a, #169b6b);
}

.bg-gradient-danger {
    background: linear-gradient(45deg, #e74a3b, #c72114);
}

/* Kart Stilleri */
.card {
    transition: transform 0.2s ease-in-out;
    border: none !important;
}

.card:hover {
    transform: translateY(-5px);
}

.card .card-body {
    padding: 1.5rem;
}

/* İstatistik Kartları */
.card h6 {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

.card h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0;
}

.card .small {
    font-size: 0.8rem;
}

/* Progress Bar Stilleri */
.progress {
    overflow: hidden;
    border-radius: 2px;
}

.progress-bar {
    transition: width 0.6s ease;
}

/* İkon Kutusu */
.icon-box {
    transition: transform 0.2s ease-in-out;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card:hover .icon-box {
    transform: scale(1.1);
}

/* Metin Renkleri */
.text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

.text-dark.text-opacity-75 {
    color: rgba(0, 0, 0, 0.75) !important;
}

/* Gölgeler */
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Tablo Stilleri */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 0.75rem;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
    padding: 0.75rem;
    font-size: 0.875rem;
}

.table tbody tr:hover {
    background-color: rgba(0,0,0,.025);
}

.table .btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
    font-size: 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .card {
        margin-bottom: 1rem;
    }
    
    .card h3 {
        font-size: 1.5rem;
    }
    
    .icon-box {
        width: 40px;
        height: 40px;
    }
    
    .icon-box i {
        font-size: 1.25rem !important;
    }

    .table-responsive {
        border-radius: 0.5rem;
    }

    .table th, 
    .table td {
        white-space: nowrap;
    }
}
</style>
@endpush 