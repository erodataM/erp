<template>
  <Loading v-show="loading" />
  <div v-if="!loading && noResult" class="styleHome">Client not found</div>
  <div v-if="!loading && !noResult" class="styleHomeTop">
    <form novalidate>
      <div>
        <h3 class="mb-4">{{ type }} client</h3>
      </div>
      <div v-if="errorGlobal.length > 0" class="text-danger" v-for="error in errorGlobal">
        {{ error }}
      </div>
      <InputText id="name" label="name" type="text" :errors="errorName" v-model="client.name"/>
      <InputText id="address" label="address" type="text" :errors="errorAddress" v-model="client.address"/>
      <InputText id="country" label="country" type="text" :errors="errorCountry" v-model="client.country"/>
      <div class="d-flex justify-content-center mt-4">
        <a href="/client" class="btn btn-secondary btn-lg">Cancel</a>
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
      client: {},
      noResult: false,
      errorAddress: [],
      errorCountry: [],
      errorName: [],
      errorGlobal: [],
      type: 'edit'
    };
  },
  methods: {
    retrieveClient(id) {
      this.loading = true;
      DataService.get('client', id)
        .then(response => {
          this.client = response.data;
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
          DataService.edit('client', this.client.id, {'name': this.client.name, 'address': this.client.address, 'country': this.client.country})
            .then(response => {
              this.$router.push("/client");
            })
            .catch(e => {
              this.errorGlobal.push('An error occurred');
            })
            .finally(() => {
              this.loading = false;
            });
        } else if (this.type === 'Add') {
          DataService.add('client',{'name': this.client.name, 'address': this.client.address, 'country': this.client.country})
            .then(response => {
              this.$router.push("/client");
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
      if (!EmptyValidator.validate(this.client.name)) {
        this.errorName.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.client.address)) {
        this.errorAddress.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.client.country)) {
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
      this.retrieveClient(this.$route.params.id);
    }
  }
}
</script>
