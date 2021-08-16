<template>
<div class="d-flex">
  <div class="input-group">
    <span class="input-group-text" id="basic-addon1">Per Page</span>
    <select class="form-select" aria-label="Default select example" v-model="perPage" @change="this.$emit('changePerPage', $event.target.value)">
      <option value="5" :selected="this.perPage === 5">5</option>
      <option value="10" :selected="this.perPage === 10">10</option>
      <option value="20"  :selected="this.perPage === 20">20</option>
    </select>
  </div>
  <ul class="pagination ms-3">
    <li v-for="buttonPage in pages" class="page-item" :class="this.page === buttonPage ? 'active' : ''" >
      <a href="#" class="page-link" @click="this.$emit('clickPage', $event.target.innerHTML)">{{buttonPage}}</a>
    </li>
  </ul>
</div>
</template>

<script>
export default {
  emits: ['changePage', 'changePerPage', 'clickPage'],
  props: {
    page: {
      type: Number,
      required: true
    },
    perPage: {
      type: Number,
      required: true
    },
    count: {
      type: Number,
      required: true
    },
    nbPages: {
      type: Number,
      required: true
    },
    pages: {
      type: Array,
      required: true
    },
  }
}
</script>