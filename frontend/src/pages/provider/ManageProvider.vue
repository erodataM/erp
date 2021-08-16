<template>
  <Loading v-show="loading" />
  <div v-if="!loading && noResult" class="styleHome">Provider not found</div>
  <div v-if="!loading && !noResult" class="styleHomeTop">
    <form novalidate>
      <div>
        <h3 class="mb-4">{{ type }} provider</h3>
      </div>
      <div v-if="errorGlobal.length > 0" class="text-danger" v-for="error in errorGlobal">
        {{ error }}
      </div>
      <InputText id="name" label="name" type="text" :errors="errorName" v-model="provider.name"/>
      <InputText id="address" label="address" type="text" :errors="errorAddress" v-model="provider.address"/>
      <InputText id="country" label="country" type="text" :errors="errorCountry" v-model="provider.country"/>
      <div class="d-flex justify-content-center mt-4">
        <a href="/provider" class="btn btn-secondary btn-lg">Cancel</a>
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

export default {
  components: {
    InputText,
    Loading,
  },
  data() {
    return {
      loading: false,
      provider: {},
      noResult: false,
      errorAddress: [],
      errorCountry: [],
      errorName: [],
      errorGlobal: [],
      type: 'edit'
    };
  },
  methods: {
    retrieveProvider(id) {
      this.loading = true;
      DataService.get('provider', id)
        .then(response => {
          this.provider = response.data;
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
          DataService.edit('provider', this.provider.id, {'name': this.provider.name, 'address': this.provider.address, 'country': this.provider.country})
            .then(response => {
              this.$router.push("/provider");
            })
            .catch(e => {
              this.errorGlobal.push('An error occurred');
            })
            .finally(() => {
              this.loading = false;
            });
        } else if (this.type === 'Add') {
          DataService.add('provider', {'name': this.provider.name, 'address': this.provider.address, 'country': this.provider.country})
            .then(response => {
              this.$router.push("/provider");
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
      this.errorAddress = [];
      this.errorCountry = [];
      if (!EmptyValidator.validate(this.provider.name)) {
        this.errorName.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.provider.address)) {
        this.errorAddress.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.provider.country)) {
        this.errorCountry.push(EmptyValidator.message());
      }
      const errors = this.errorName + this.errorAddress + this.errorCountry;
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
      this.retrieveProvider(this.$route.params.id);
    }
  }
}
</script>
