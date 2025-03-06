@props([
    'title',
    'createButtonText' => 'Yeni Ekle',
    'createModalId',
])

<div class="card">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i>{{ $title }}
            </h5>
            <button type="button" 
                    class="btn btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#{{ $createModalId }}">
                <i class="fas fa-plus-circle me-1"></i>{{ $createButtonText }}
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" id="dataTable">
                {{ $slot }}
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Arama fonksiyonu
    function filterTable() {
        var searchText = $('#searchText').val().toLowerCase();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var statusFilter = $('#statusFilter').val().toLowerCase();

        $('#dataTable tbody tr').each(function() {
            var row = $(this);
            var text = row.text().toLowerCase();
            var dateCell = row.find('td:eq(4)').text(); // Giriş tarihi sütunu
            var statusCell = row.find('td:eq(6) .badge').text().toLowerCase(); // Durum sütunu
            
            var dateMatch = true;
            if(startDate && endDate) {
                var rowDate = new Date(dateCell.split('.').reverse().join('-'));
                var start = new Date(startDate);
                var end = new Date(endDate);
                dateMatch = rowDate >= start && rowDate <= end;
            }

            var statusMatch = !statusFilter || statusCell.includes(statusFilter);
            var textMatch = !searchText || text.includes(searchText);

            row.toggle(dateMatch && statusMatch && textMatch);
        });
    }

    // Event listeners
    $('#searchButton').on('click', filterTable);
    $('#searchText').on('keyup', function(e) {
        if(e.key === 'Enter') {
            filterTable();
        }
    });
    $('#startDate, #endDate, #statusFilter').on('change', filterTable);

    // Tarih aralığı kontrolü
    $('#endDate').on('change', function() {
        var startDate = $('#startDate').val();
        var endDate = $(this).val();
        
        if(startDate && endDate && startDate > endDate) {
            toastr.error('Bitiş tarihi başlangıç tarihinden önce olamaz!');
            $(this).val('');
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* Arama input'u için stiller */
#tableSearchInput {
    border-left: 0;
}

#tableSearchInput:focus {
    box-shadow: none;
    border-color: #dee2e6;
}

.input-group-text {
    border-right: 0;
}

/* Tablo için stiller */
.table {
    margin-bottom: 0;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td, .table th {
    padding: 0.75rem;
    vertical-align: middle;
}
</style>
@endpush 