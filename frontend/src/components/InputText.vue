<template>
  <div class="mb-6">
    <label :for="id" class="form-label">{{ label }}</label>
    <input :type="type" class="form-control" :id="id" v-model="inputVal" :class="errors.length > 0 ? 'is-invalid' : ''">
    <div v-if="errors.length > 0" class="invalid-feedback" v-for="error in errors">
      {{ error }}
    </div>
  </div>
</template>
<script>

export default {
  props: {
    label: String,
    id: String,
    errors: Array,
    modelValue: String,
    type: String
  },
  computed: {
    inputVal: {
      get() {
        return this.modelValue;
      },
      set(val) {
        this.$emit('update:modelValue', val);
      }
    }
  },
  components: {
  },
}
</script>