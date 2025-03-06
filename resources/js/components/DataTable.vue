<template>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Başlık</th>
              <th>Acente</th>
              <th>Şirket</th>
              <th>Durum</th>
              <th>Oluşturulma Tarihi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="offer in offers" 
                :key="offer.id"
                @dblclick="showModal(offer)">
              <td>{{ offer.id }}</td>
              <td>{{ offer.title }}</td>
              <td>{{ offer.agency?.name }}</td>
              <td>{{ offer.company?.name }}</td>
              <td>
                <span :class="'badge bg-' + offer.status?.color">
                  {{ offer.status?.name }}
                </span>
              </td>
              <td>{{ formatDate(offer.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <Modal v-model="isModalOpen" title="Teklif Detayları">
      <template v-if="selectedOffer">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Teklif ID:</strong> {{ selectedOffer.id }}</p>
            <p><strong>Başlık:</strong> {{ selectedOffer.title }}</p>
            <p><strong>Acente:</strong> {{ selectedOffer.agency?.name }}</p>
            <p><strong>Şirket:</strong> {{ selectedOffer.company?.name }}</p>
            <p><strong>Durum:</strong> 
              <span :class="'badge bg-' + selectedOffer.status?.color">
                {{ selectedOffer.status?.name }}
              </span>
            </p>
          </div>
          <div class="col-md-6">
            <p><strong>Oluşturulma Tarihi:</strong> {{ formatDate(selectedOffer.created_at) }}</p>
            <p><strong>Güncellenme Tarihi:</strong> {{ formatDate(selectedOffer.updated_at) }}</p>
          </div>
        </div>
        
        <!-- Dosyalar -->
        <div v-if="selectedOffer.offer_files?.length" class="mt-4">
          <h6>Dosyalar</h6>
          <div class="list-group">
            <a v-for="file in selectedOffer.offer_files" 
               :key="file.id"
               :href="file.file_path"
               class="list-group-item list-group-item-action"
               target="_blank">
              {{ file.original_name }}
            </a>
          </div>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import moment from 'moment'
import 'moment/locale/tr'
import Modal from './Modal.vue'

export default {
  components: {
    Modal
  },

  props: {
    offers: {
      type: Array,
      required: true
    }
  },

  setup() {
    const selectedOffer = ref(null)
    const isModalOpen = ref(false)

    onMounted(() => {
      console.log('DataTable bileşeni yüklendi')
    })

    const formatDate = (date) => {
      return moment(date).format('DD.MM.YYYY HH:mm')
    }

    const showModal = (offer) => {
      console.log('Modal açılıyor:', offer)
      selectedOffer.value = offer
      isModalOpen.value = true
    }

    return {
      selectedOffer,
      isModalOpen,
      formatDate,
      showModal
    }
  }
}
</script>

<style scoped>
tr {
  cursor: pointer;
}
tr:hover {
  background-color: rgba(0,0,0,.075);
}
</style> 