<template>
  <Teleport to="body">
    <div v-if="modelValue" class="modal-backdrop" @click="$emit('update:modelValue', false)">
      <div class="modal-window" @click.stop>
        <div class="modal-header">
          <h5>{{ title }}</h5>
          <button class="close-button" @click="$emit('update:modelValue', false)">&times;</button>
        </div>
        
        <div class="modal-body">
          <slot></slot>
        </div>
        
        <div class="modal-footer">
          <slot name="footer">
            <button class="btn btn-secondary" @click="$emit('update:modelValue', false)">Kapat</button>
          </slot>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script>
export default {
  props: {
    modelValue: Boolean,
    title: {
      type: String,
      default: ''
    }
  },
  emits: ['update:modelValue']
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
}

.modal-window {
  background: white;
  border-radius: 8px;
  width: 90%;
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  padding: 1rem;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.close-button {
  border: none;
  background: none;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0;
  margin: 0;
}

.modal-body {
  padding: 1rem;
}

.modal-footer {
  padding: 1rem;
  border-top: 1px solid #eee;
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}
</style> 