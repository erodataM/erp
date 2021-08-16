<template>
  <Loading v-show="loading"/>
  <div v-show="!loading" class="styleHome">
    <div v-if="errorGlobal.length > 0" class="text-danger" v-for="error in errorGlobal">
      {{ error }}
    </div>
    <form>

      <InputText id="name" label="name" type="text" :errors="errorName" v-model="name"/>
      <InputText id="password" label="password" type="password" :errors="errorPassword" v-model="password"/>
      <div class="d-flex justify-content-center mt-4">
        <input type="submit" class="btn btn-success" @click="submitLogin" value="Login"/>
      </div>
    </form>
  </div>
</template>

<script>
import AuthService from '../services/AuthService';
import EmptyValidator from "../validators/EmptyValidator";

import Loading from "../components/Loading.vue";
import InputText from "../components/InputText.vue";

export default {
  components: {
    InputText,
    Loading
  },
  data() {
    return {
      loading: false,
      name: '',
      password: '',
      errorName: [],
      errorPassword: [],
      errorGlobal: []
    }
  },
  methods: {
    submitLogin(e) {
      e.preventDefault();
      if (this.validate() === true) {
        this.loading = true;
        this.errorGlobal = [];
        AuthService.login(this.name, this.password)
          .then((response) => {
            this.$store.commit('setAuthenticated', true);
            this.$store.commit('setRole', response.data.role);
            this.$router.push('/');
          })
          .catch(() => {
            this.errorGlobal.push('Bad credentials');
          })
          .finally(()=>{
            this.loading = false;
          });
      }
    },
    validate() {
      this.errorName = [];
      this.errorPassword = [];
      if (!EmptyValidator.validate(this.name)) {
        this.errorName.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.password)) {
        this.errorPassword.push(EmptyValidator.message());
      }

      const errors = this.errorName + this.errorPassword;
      if (errors.length === 0) {
        return true;
      }
      return errors;
    }
  }
}
</script>