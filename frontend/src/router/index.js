import { createWebHistory, createRouter } from "vue-router";
import Home from "../pages/Home.vue";
import ListCompany from "../pages/company/ListCompany.vue";
import ListProduct from "../pages/product/ListProduct.vue";
import ListProvider from "../pages/provider/ListProvider.vue";
import ListClient from "../pages/client/ListClient.vue";
import ListEmployee from "../pages/employee/ListEmployee.vue";
import ListTransaction from "../pages/transaction/ListTransaction.vue";
import ManageCompany from "../pages/company/ManageCompany.vue";
import ManageProduct from "../pages/product/ManageProduct.vue";
import ManageProvider from "../pages/provider/ManageProvider.vue";
import ManageClient from "../pages/client/ManageClient.vue";
import ManageEmployee from "../pages/employee/ManageEmployee.vue";
import ManageTransaction from "../pages/transaction/ManageTransaction.vue";
import Login from "../pages/Login.vue";

import { store } from '../store';
const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: "/",
      name: "Home",
      component: Home,
    },
    {
      path: "/company",
      name: "ListCompany",
      component: ListCompany,
    },
    {
      path: '/company/add',
      name: "AddCompany",
      component: ManageCompany
    },
    {
      path: '/company/:id',
      name: "EditCompany",
      component: ManageCompany
    },
    {
      path: "/product",
      name: "ListProduct",
      component: ListProduct,
    },
    {
      path: '/product/add',
      name: "AddProduct",
      component: ManageProduct
    },
    {
      path: '/product/:id',
      name: "EditProduct",
      component: ManageProduct
    },
    {
      path: "/provider",
      name: "ListProvider",
      component: ListProvider,
    },
    {
      path: '/provider/add',
      name: "AddProvider",
      component: ManageProvider
    },
    {
      path: '/provider/:id',
      name: "EditProvider",
      component: ManageProvider
    },
    {
      path: "/client",
      name: "ListClient",
      component: ListClient,
    },
    {
      path: '/client/add',
      name: "AddClient",
      component: ManageClient
    },
    {
      path: '/client/:id',
      name: "EditClient",
      component: ManageClient
    },
    {
      path: "/employee",
      name: "ListEmployee",
      component: ListEmployee,
    },
    {
      path: '/employee/add',
      name: "AddEmployee",
      component: ManageEmployee
    },
    {
      path: '/employee/:id',
      name: "EditEmployee",
      component: ManageEmployee
    },
    {
      path: "/transaction",
      name: "ListTransaction",
      component: ListTransaction,
    },
    {
      path: '/transaction/add',
      name: "AddTransaction",
      component: ManageTransaction
    },
    {
      path: '/transaction/:id',
      name: "EditTransaction",
      component: ManageTransaction
    },
    {
      path: "/login",
      name: "Login",
      component: Login,
    },
  ],
});


router.beforeEach((to, from, next) => {
  if (to.name !== 'Login' && !store.getters.isAuthenticated) {
    next({ name: 'Login' })
  }
  if (to.name === 'Login' && store.getters.isAuthenticated) {
    next({ name: 'Home' })
  }
  next()
})

export default router;