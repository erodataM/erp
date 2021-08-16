<template>
  <Loading v-show="loading" />
  <div v-if="!loading && noResult" class="styleHome">Transaction not found</div>
  <div v-if="!loading && !noResult" class="styleHomeTop">
    <form novalidate>
      <div>
        <h3 class="mb-4">{{ type }} transaction</h3>
      </div>
      <div v-if="errorGlobal.length > 0" class="text-danger" v-for="error in errorGlobal">
        {{ error }}
      </div>
      <label for="company">company</label>
      <select id="company" class="form-control" v-model="transaction.company_id" :class="errorCompany.length > 0 ? 'is-invalid' : ''">
        <option value=""></option>
        <option v-for="company in this.companies" :value="company.id">{{ company.name }}</option>
      </select>
      <div v-if="errorCompany.length > 0" class="text-danger" v-for="error in errorCompany">
        {{ error }}
      </div>
      <label for="client">client</label>
      <select id="client" class="form-control" v-model="transaction.client_id">
        <option value=""></option>
        <option v-for="client in this.clients" :value="client.id">{{ client.name }}</option>
      </select>
      <label for="provider">provider</label>
      <select id="provider" class="form-control" v-model="transaction.provider_id">
        <option value=""></option>
        <option v-for="provider in this.providers" :value="provider.id">{{ provider.name }}</option>
      </select>
      <label for="product">product</label>
      <select id="product" class="form-control" v-model="transaction.product_id" :class="errorProduct.length > 0 ? 'is-invalid' : ''">
        <option value=""></option>
        <option v-for="product in this.products" :value="product.id">{{ product.name }}</option>
      </select>
      <div v-if="errorProduct.length > 0" class="text-danger" v-for="error in errorProduct">
        {{ error }}
      </div>
      <InputText id="quantity" label="quantity" type="text" :errors="errorQuantity" v-model="transaction.quantity"/>
      <div class="d-flex justify-content-center mt-4">
        <a href="/transaction" class="btn btn-secondary btn-lg">Cancel</a>
        <input type="submit" class="btn btn-primary btn-lg" :class="type === 'Add' ? 'btn-success' : 'btn-primary'" @click="submit" :value="type"/>
      </div>
    </form>
  </div>
</template>
<script>

import DataService from "../../services/DataService";
import Loading from "../../components/Loading.vue";
import InputText from "../../components/InputText.vue";
import EmptyValidator from "../../validators/EmptyValidator";
import NumericValidator from "../../validators/NumericValidator";

export default {
  components: {
    InputText,
    Loading,
  },
  data() {
    return {
      loading: false,
      transaction: {},
      companies: [],
      clients: [],
      providers: [],
      products: [],
      employees: [],
      noResult: false,
      errorGlobal: [],
      errorCompany: [],
      errorProduct: [],
      errorQuantity: [],
      type: 'edit'
    };
  },
  methods: {
    retrieveTransaction(id) {
      this.loading = true;
      DataService.get('transaction', id)
        .then(response => {
          this.transaction = response.data;
        })
        .catch(e => {
          this.noResult = true;
        })
        .finally(()=>{
          this.loading = false;
        })
    },
    retrieveSelectCompany() {
      DataService.getSelect('company')
        .then(response => {
          this.companies = response.data.content;
        });
    },
    retrieveSelectClient() {
      DataService.getSelect('client')
        .then(response => {
          this.clients = response.data.content;
        });
    },
    retrieveSelectProvider() {
      DataService.getSelect('provider')
        .then(response => {
          this.providers = response.data.content;
        });
    },
    retrieveSelectProduct() {
      DataService.getSelect('product')
        .then(response => {
          this.products = response.data.content;
        });
    },
    submit(evt) {
      evt.preventDefault()
      if (this.validate() === true) {
        this.loading = true;
        this.errorGlobal = [];
        const data = {
          company_id: this.transaction.company_id,
          client_id: this.transaction.client_id,
          provider_id: this.transaction.provider_id,
          product_id: this.transaction.product_id,
          quantity: this.transaction.quantity
        };
        if (this.type === 'Edit') {
          DataService.edit('transaction', this.transaction.id, data)
            .then(response => {
              this.$router.push("/transaction");
            })
            .catch(error => {
              if (error.response && error.response.data.status[0].message) {
                this.errorGlobal.push(error.response.data.status[0].message);
              } else {
                this.errorGlobal.push('An error occurred');
              }
            })
            .finally(() => {
              this.loading = false;
            });
        } else if (this.type === 'Add') {
          DataService.add('transaction', data)
            .then(response => {
              this.$router.push("/transaction");
            })
            .catch(error => {
              if (error.response && error.response.data.status[0].message) {
                this.errorGlobal.push(error.response.data.status[0].message);
              } else {
                this.errorGlobal.push('An error occurred');
              }
            })
            .finally(() => {
              this.loading = false;
            })
        }
      }
    },
    validate() {
      this.errorCompany = [];
      this.errorProduct = [];
      this.errorQuantity = [];

      if (!EmptyValidator.validate(this.transaction.company_id)) {
        this.errorCompany.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.transaction.product_id)) {
        this.errorProduct.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.transaction.quantity)) {
        this.errorQuantity.push(EmptyValidator.message());
      }
      if (!NumericValidator.validate(this.transaction.quantity)) {
        this.errorQuantity.push(NumericValidator.message());
      }
      const errors = this.errorCompany + this.errorProduct + this.errorQuantity;
      if (errors.length === 0) {
        return true;
      }
      return errors;
    }
  },
  mounted() {
    if (!this.$route.params.id) {
      this.type = 'Add';
    } else {
      this.type = 'Edit';
      this.retrieveTransaction(this.$route.params.id);
    }
    this.retrieveSelectCompany();
    this.retrieveSelectClient();
    this.retrieveSelectProvider();
    this.retrieveSelectProduct();
  }
}
</script>
