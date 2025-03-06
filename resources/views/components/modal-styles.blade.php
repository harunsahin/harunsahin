@push('styles')
<style>
/* Modal Genel Stiller */
.modal-content {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.25rem 1.5rem;
    background-color: #f8f9fa;
    border-top-left-radius: 1rem;
    border-top-right-radius: 1rem;
}

.modal-header .modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #212529;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
    padding: 1.25rem 1.5rem;
    background-color: #f8f9fa;
    border-bottom-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
}

/* Info Section Stiller */
.info-section {
    background-color: #fff;
    border-radius: 0.75rem;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e9ecef;
}

.info-section:last-child {
    margin-bottom: 0;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

/* Info Item Stiller */
.info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background-color: #fff;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-item i {
    font-size: 1rem;
    color: #6c757d;
    margin-right: 0.75rem;
    margin-top: 0.25rem;
}

.info-item div {
    flex: 1;
}

.info-item small {
    display: block;
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.info-item strong {
    display: block;
    font-size: 0.875rem;
    color: #212529;
    font-weight: 500;
}

/* Badge Stiller */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.5em 0.75em;
    border-radius: 0.375rem;
}

/* Dosya Listesi Stiller */
.files-list .list-group-item {
    border: 1px solid #e9ecef;
    margin-bottom: 0.5rem;
    border-radius: 0.5rem;
    padding: 0.75rem;
    background-color: #fff;
}

.files-list .list-group-item:last-child {
    margin-bottom: 0;
}

.files-list .list-group-item a {
    color: #212529;
    display: flex;
    align-items: center;
}

.files-list .list-group-item i {
    color: #6c757d;
    margin-right: 0.5rem;
}

/* Not İçeriği Stiller */
.notes-content {
    padding: 0.75rem;
    background-color: #fff;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
    font-size: 0.875rem;
    color: #212529;
    line-height: 1.5;
}

/* Responsive Düzenlemeler */
@media (max-width: 768px) {
    .modal-body {
        padding: 1rem;
    }

    .info-section {
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .info-item {
        padding: 0.5rem;
    }

    .files-list .list-group-item {
        padding: 0.5rem;
    }
}
</style>
@endpush 