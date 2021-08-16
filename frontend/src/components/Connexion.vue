<template>
  <div class="nav-item d-flex align-items-center">
    <font-awesome-icon :icon="this.label === 'Login' ? 'sign-in-alt' : 'sign-out-alt'" />
    <router-link
      to="#"
      class="nav-link"
      :class="this.$router.currentRoute.value.path === '/'  + this.label.toLowerCase() ? 'active' : ''"
      @click="this.$emit(this.label.toLowerCase())"
      >
        {{ this.label }}
    </router-link>
  </div>

</template>

<script>

export default {
  props: {
    label: {
      label: String,
      required: true
    },
  },
  emits: ['logout', 'login']
}

</script>