<template>
  <BootstrapModal :showModal="showDeleteModal" :body="deleteBody" :title="deleteTitle" @closeModal="closeDeleteModal" @validModal="validDeleteModal"></BootstrapModal>
  <Loading v-show="loading" />
  <div v-show="!loading"  class="d-flex justify-content-between p-4 align-items-center">
    <h5>Employees List</h5>
    <a href="/employee/add" class="btn btn-success col-2" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Add employee</a>
    <Paginate :pages="pages" :count="count" :page="page" :perPage="perPage" :nbPages="nbPages" @change-per-page="changePerPage" @click-page="clickPage" />
  </div>
  <div v-show="count === 0" class="noResult">
      No employee yet
  </div>
  <div v-show="!loading && count > 0" class="table-responsive p-4">
    <table class="table table-striped table-hover table-responsive align-middle">
      <thead class="table-info">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Birthday</th>
          <th scope="col">Country</th>
          <th scope="col">First day</th>
          <th scope="col">Role</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in employees">
          <th scope="row">{{ employee.id }}</th>
          <td>{{ employee.name }}</td>
          <td>{{ employee.birthday }}</td>
          <td>{{ employee.country }}</td>
          <td>{{ employee.firstday }}</td>
          <td>{{ employee.role }}</td>
          <td>
            <a :href="`/employee/${employee.id}`" class="btn btn-secondary" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Edit</a>
            <button class="btn btn-danger" @click="toggleModal(employee.id)" :class="this.$store.getters.getRole !== 'admin' ? 'disabled' : ''">Delete</button>
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
      employees: [],
      page: 1,
      perPage: 10,
      count: 0,
      nbPages: 0,
      pages: [],
      showDeleteModal: false,
      currentDelete: 0,
      deleteTitle: "Delete this employee",
      deleteBody: "Are you sure ?",
      loading: false
    };
  },
  methods: {
    retrieveEmployees() {
      this.loading = true;
      DataService.getPaginate('employee', this.page, this.perPage)
        .then(response => {
          this.employees = response.data.content;
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
        this.retrieveEmployees();
      }
    },
    changePerPage(perPage) {
      if (parseInt(perPage) > 0) {
        this.perPage = parseInt(perPage);
        this.page = 1;
        this.retrieveEmployees();
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
      DataService.delete('employee', this.currentDelete)
        .then(response => {
          this.retrieveEmployees();
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
    this.retrieveEmployees();
  }
}
</script>