<template>
  <BootstrapModal :showModal="showDeleteModal" :body="deleteBody" :title="deleteTitle" @closeModal="closeDeleteModal" @validModal="validDeleteModal"></BootstrapModal>
  <Loading v-show="loading" />
  <div v-show="!loading"  class="d-flex justify-content-between p-4 align-items-center">
    <h5>Transactions List</h5>
    <a href="/transaction/add" class="btn btn-success col-2" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Add transaction</a>
    <Paginate :pages="pages" :count="count" :page="page" :perPage="perPage" :nbPages="nbPages" @change-per-page="changePerPage" @click-page="clickPage" />
  </div>
  <div v-show="count === 0" class="noResult">
      No transaction yet
  </div>
  <div v-show="!loading && count > 0" class="table-responsive p-4">
    <table class="table table-striped table-hover table-responsive align-middle">
      <thead class="table-info">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Company name</th>
          <th scope="col">Provider name</th>
          <th scope="col">Client name</th>
          <th scope="col">Product name</th>
          <th scope="col">Employee name</th>
          <th scope="col">Quantity</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transaction in transactions">
          <th scope="row">{{ transaction.id }}</th>
          <td>{{ transaction.company_name }}</td>
          <td>{{ transaction.provider_name }}</td>
          <td>{{ transaction.client_name }}</td>
          <td>{{ transaction.product_name }}</td>
          <td>{{ transaction.employee_name }}</td>
          <td>{{ transaction.quantity }}</td>
          <td>
            <a :href="`/transaction/${transaction.id}`" class="btn btn-secondary" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Edit</a>
            <button class="btn btn-danger" @click="toggleModal(transaction.id)" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Delete</button>
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
      transactions: [],
      page: 1,
      perPage: 10,
      count: 0,
      nbPages: 0,
      pages: [],
      showDeleteModal: false,
      currentDelete: 0,
      deleteTitle: "Delete this transaction",
      deleteBody: "Are you sure ?",
      loading: false
    };
  },
  methods: {
    retrieveTransactions() {
      this.loading = true;
      DataService.getPaginate('transaction', this.page, this.perPage)
        .then(response => {
          this.transactions = response.data.content;
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
        this.retrieveTransactions();
      }
    },
    changePerPage(perPage) {
      if (parseInt(perPage) > 0) {
        this.perPage = parseInt(perPage);
        this.page = 1;
        this.retrieveTransactions();
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
      DataService.delete('transaction', this.currentDelete)
        .then(response => {
          this.retrieveTransactions();
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
    this.retrieveTransactions();
  }
}
</script>