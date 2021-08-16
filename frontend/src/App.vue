<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <img :src="image" alt="" width="30" height="24">
        ERP
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div v-if="this.$store.getters.isAuthenticated" class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <div class="nav-item">
            <router-link to="/company" class="nav-link" :class="$router.currentRoute.value.path === '/company' ? 'active' : ''">Companies</router-link>
          </div>
          <div class="nav-item">
            <router-link to="/product" class="nav-link" :class="$router.currentRoute.value.path === '/product' ? 'active' : ''">Products</router-link>
          </div>
          <div class="nav-item">
            <router-link to="/provider" class="nav-link" :class="$router.currentRoute.value.path === '/provider' ? 'active' : ''">Providers</router-link>
          </div>
          <div class="nav-item">
            <router-link to="/client" class="nav-link" :class="$router.currentRoute.value.path === '/client' ? 'active' : ''">Clients</router-link>
          </div>
          <div class="nav-item">
            <router-link to="/employee" class="nav-link" :class="$router.currentRoute.value.path === '/employee' ? 'active' : ''">Employees</router-link>
          </div>
          <div class="nav-item">
            <router-link to="/transaction" class="nav-link" :class="$router.currentRoute.value.path === '/transaction' ? 'active' : ''">Transactions</router-link>
          </div>
        </div>
        <div class="navbar-nav ms-4">
          <Connexion @login="login" @logout="logout" :label="this.$store.getters.isAuthenticated ? 'Logout' : 'Login'"/>
        </div>
      </div>
    </div>
  </nav>
  <router-view></router-view>
</template>

<script>

import img from '../public/vue.png';
import Connexion from "./components/Connexion.vue";
import AuthService from "./services/AuthService";

export default {
  components: {
    Connexion
  },

  data() {
    return {
      image: img,
    }
  },
  methods: {
    login() {

    },
    logout() {
      AuthService.logout();
      this.$store.commit('setAuthenticated', false);
      this.$store.commit('setRole', '');
      this.$router.push('/login');
    }
  }
}
</script>