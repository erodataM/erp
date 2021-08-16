<template>
  <Loading v-show="loading" />
  <div v-if="!loading && noResult" class="styleHome">Company not found</div>
  <div v-if="!loading && !noResult" class="styleHomeTop">
    <form novalidate>
      <div>
        <h3 class="mb-4">{{ type }} company</h3>
      </div>
      <div v-if="errorGlobal.length > 0" class="text-danger" v-for="error in errorGlobal">
        {{ error }}
      </div>
      <InputText id="name" label="name" type="text" :errors="errorName" v-model="company.name"/>
      <InputText id="balance" label="balance" type="text" :errors="errorBalance" v-model="company.balance"/>
      <InputText id="country" label="country" type="text" :errors="errorCountry" v-model="company.country"/>
      <div class="d-flex justify-content-center mt-4">
        <a href="/company" class="btn btn-secondary btn-lg">Cancel</a>
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
      company: {},
      noResult: false,
      errorBalance: [],
      errorCountry: [],
      errorName: [],
      errorGlobal: [],
      type: 'edit'
    };
  },
  methods: {
    retrieveCompany(id) {
      this.loading = true;
      DataService.get('company', id)
        .then(response => {
          this.company = response.data;
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
          DataService.edit('company', this.company.id, {'name': this.company.name, 'balance': this.company.balance, 'country': this.company.country})
            .then(response => {
              this.$router.push("/company");
            })
            .catch(e => {
              this.errorGlobal.push('An error occurred');
            })
            .finally(() => {
              this.loading = false;
            });
        } else if (this.type === 'Add') {
          DataService.add('company', {'name': this.company.name, 'balance': this.company.balance, 'country': this.company.country})
            .then(response => {
              this.$router.push("/company");
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
      this.errorBalance = [];
      this.errorCountry = [];
      if (!EmptyValidator.validate(this.company.name)) {
        this.errorName.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.company.balance)) {
        this.errorBalance.push(EmptyValidator.message());
      }
      if (!NumericValidator.validate(this.company.balance)) {
        this.errorBalance.push(NumericValidator.message());
      }
      if (!EmptyValidator.validate(this.company.country)) {
        this.errorCountry.push(EmptyValidator.message());
      }
      const errors = this.errorName + this.errorBalance + this.errorCountry;
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
      this.retrieveCompany(this.$route.params.id);
    }
  }
}
</script>
