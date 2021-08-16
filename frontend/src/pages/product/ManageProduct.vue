<template>
  <Loading v-show="loading" />
  <div v-if="!loading && noResult" class="styleHome">Product not found</div>
  <div v-if="!loading && !noResult" class="styleHomeTop">
    <form novalidate>
      <div>
        <h3 class="mb-4">{{ type }} product</h3>
      </div>
      <div v-if="errorGlobal.length > 0" class="text-danger" v-for="error in errorGlobal">
        {{ error }}
      </div>
      <InputText id="name" label="name" type="text" :errors="errorName" v-model="product.name"/>
      <InputText id="price" label="price" type="text" :errors="errorPrice" v-model="product.price"/>
      <InputText id="tax" label="tax" type="text" :errors="errorTax" v-model="product.tax"/>
      <InputText id="stock" label="stock" type="text" :errors="errorStock" v-model="product.stock"/>
      <div class="d-flex justify-content-center mt-4">
        <a href="/product" class="btn btn-secondary btn-lg">Cancel</a>
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
      product: {},
      noResult: false,
      errorName: [],
      errorPrice: [],
      errorTax: [],
      errorStock: [],
      errorGlobal: [],
      type: 'edit'
    };
  },
  methods: {
    retrieveProduct(id) {
      this.loading = true;
      DataService.get('product', id)
        .then(response => {
          this.product = response.data;
        })
        .catch(e => {
          this.noResult = true;
        })
        .finally(()=>{
          this.loading = false;
        })
    },
    submit(evt) {
      evt.preventDefault()
      if (this.validate() === true) {
        this.loading = true;
        this.errorGlobal = [];
        if (this.type === 'Edit') {
          DataService.edit('product', this.product.id, {'name': this.product.name, 'price': this.product.price, 'tax': this.product.tax, 'stock': this.product.stock})
            .then(response => {
              this.$router.push("/product");
            })
            .catch(e => {
              this.errorGlobal.push('An error occurred');
            })
            .finally(() => {
              this.loading = false;
            });
        } else if (this.type === 'Add') {
          DataService.add('product',{'name': this.product.name, 'price': this.product.price, 'tax': this.product.tax, 'stock': this.product.stock})
            .then(response => {
              this.$router.push("/product");
            })
            .catch(e => {
              this.errorGlobal.push('An error occurred');
            })
            .finally(() => {
              this.loading = false;
            })
        }
      }
    },
    validate() {
      this.errorName = [];
      this.errorPrice = [];
      this.errorTax = [];
      this.errorStock = [];
      if (!EmptyValidator.validate(this.product.name)) {
        this.errorName.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.product.price)) {
        this.errorPrice.push(EmptyValidator.message());
      }
      if (!NumericValidator.validate(this.product.price)) {
        this.errorPrice.push(NumericValidator.message());
      }
      if (!EmptyValidator.validate(this.product.tax)) {
        this.errorTax.push(EmptyValidator.message());
      }
      if (!NumericValidator.validate(this.product.tax)) {
        this.errorTax.push(NumericValidator.message());
      }
      if (!EmptyValidator.validate(this.product.stock)) {
        this.errorStock.push(EmptyValidator.message());
      }
      if (!NumericValidator.validate(this.product.stock)) {
        this.errorStock.push(NumericValidator.message());
      }
      const errors = this.errorName + this.errorPrice + this.errorTax + this.errorStock;
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
      this.retrieveProduct(this.$route.params.id);
    }
  }
}
</script>
