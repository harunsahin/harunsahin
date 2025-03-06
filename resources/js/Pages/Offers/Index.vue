<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <h1 class="mb-4">Teklifler</h1>
        <DataTable :offers="offers" />
      </div>
    </div>
  </div>
</template>

<script>
import DataTable from '@/Components/DataTable.vue'

export default {
  components: {
    DataTable
  },
  
  props: {
    offers: {
      type: Array,
      required: true
    }
  }
}
</script> 