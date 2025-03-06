<div class="modal fade" id="deleteResourceModal" tabindex="-1" aria-labelledby="deleteResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteResourceModalLabel">Kaynak Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteResourceForm">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Bu kaynağı silmek istediğinizden emin misiniz?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger">Sil</button>
                </div>
            </form>
        </div>
    </div>
</div> 