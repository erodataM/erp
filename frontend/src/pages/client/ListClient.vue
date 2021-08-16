<template>
  <BootstrapModal :showModal="showDeleteModal" :body="deleteBody" :title="deleteTitle" @closeModal="closeDeleteModal" @validModal="validDeleteModal"></BootstrapModal>
  <Loading v-show="loading" />
  <div v-show="!loading"  class="d-flex justify-content-between p-4 align-items-center">
    <h5>Clients List</h5>
    <a href="/client/add" class="btn btn-success col-2" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Add client</a>
    <Paginate :pages="pages" :count="count" :page="page" :perPage="perPage" :nbPages="nbPages" @change-per-page="changePerPage" @click-page="clickPage" />
  </div>
  <div v-show="count === 0" class="noResult">
      No client yet
  </div>
  <div v-show="!loading && count > 0" class="table-responsive p-4">
    <table class="table table-striped table-hover table-responsive align-middle">
      <thead class="table-info">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Address</th>
          <th scope="col">Country</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="client in clients">
          <th scope="row">{{ client.id }}</th>
          <td>{{ client.name }}</td>
          <td>{{ client.address }}</td>
          <td>{{ client.country }}</td>
          <td>
            <a :href="`/client/${client.id}`" class="btn btn-secondary" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Edit</a>
            <button class="btn btn-danger" @click="toggleModal(client.id)" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>

import Paginate from '../../components/Paginate.vue';
import Loading from '../../components/Loading.vue';
import BootstrapModal from "../../components/BootstrapModal.vue";
import DataService from "../../services/DataService";

export default {
  components: {
    BootstrapModal,
    Paginate,
    Loading,
  },
  data() {
    return {
      clients: [],
      page: 1,
      perPage: 10,
      count: 0,
      nbPages: 0,
      pages: [],
      showDeleteModal: false,
      currentDelete: 0,
      deleteTitle: "Delete this client",
      deleteBody: "Are you sure ?",
      loading: false
    };
  },
  methods: {
    retrieveClients() {
      this.loading = true;
      DataService.getPaginate('client', this.page, this.perPage)
        .then(response => {
          this.clients = response.data.content;
          this.count = response.data.count;
          this.nbPages = Math.ceil(this.count / this.perPage);
          this.pages = Array.from(Array(this.nbPages).keys(), n => n + 1);
        })
        .catch(e => {
        })
        .finally(()=>{
          this.loading = false;
        })
    },
    changePage(page) {
      if (parseInt(page) > 0) {
        this.page = parseInt(page);
        this.retrieveClients();
      }
    },
    changePerPage(perPage) {
      if (parseInt(perPage) > 0) {
        this.perPage = parseInt(perPage);
        this.page = 1;
        this.retrieveClients();
      }
    },
    clickPage(page) {
      this.changePage(page);
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
    },
    validDeleteModal() {
      this.loading = true;
      DataService.delete('client', this.currentDelete)
        .then(response => {
          this.retrieveClients();
          this.showDeleteModal = false;
        })
        .finally(() => {
          this.loading = false;
        })
    },
    toggleModal(id) {
      this.currentDelete = id;
      this.showDeleteModal = !this.showDeleteModal;
    }
  },
  mounted() {
    this.retrieveClients();
  }
}
</script>