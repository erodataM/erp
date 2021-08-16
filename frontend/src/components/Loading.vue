<template>
  <div class="styleHome">
    <img :src="image" alt="loading">
  </div>
</template>

<script>

import img from '../../public/loading.gif';

export default {
  components: {
  },

  data() {
    return {
      image: img,
    }
  }
}
</script>