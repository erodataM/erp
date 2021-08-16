<template>
  <Loading v-show="loading" />
  <div v-if="!loading && noResult" class="styleHome">Employee not found</div>
  <div v-if="!loading && !noResult" class="styleHomeTop">
    <form novalidate>
      <div>
        <h3 class="mb-4">{{ type }} employee</h3>
      </div>
      <div v-if="errorGlobal.length > 0" class="text-danger" v-for="error in errorGlobal">
        {{ error }}
      </div>
      <InputText id="name" label="name" type="text" :errors="errorName" v-model="employee.name"/>
      <label class="form-label">birthday</label>
      <datepicker id="birthday" name="birthday" v-model="employee.birthday" class="form-control"/>
      <InputText id="country" label="country" type="text" :errors="errorCountry" v-model="employee.country"/>
      <label class="form-label">firstday</label>
      <datepicker id="firstday" name="firstday" v-model="employee.firstday" class="form-control"/>
      <label class="form-label">role</label>
      <select id="role" class="form-control" v-model="employee.role">
        <option value="admin">admin</option>
        <option value="user">user</option>
      </select>
      <div class="d-flex justify-content-center mt-4">
        <a href="/employee" class="btn btn-secondary btn-lg">Cancel</a>
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
import Datepicker from 'vue3-datepicker'

export default {
  components: {
    InputText,
    Loading,
    Datepicker
  },
  data() {
    return {
      loading: false,
      employee: {},
      noResult: false,
      errorBirthday: [],
      errorFirstday: [],
      errorCountry: [],
      errorName: [],
      errorGlobal: [],
      type: 'edit'
    };
  },
  methods: {
    retrieveEmployee(id) {
      this.loading = true;
      DataService.get('employee', id)
        .then(response => {
          this.employee = response.data;
          this.employee.birthday = new Date(this.employee.birthday);
          this.employee.firstday = new Date(this.employee.firstday);
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
        const data = {
          'name': this.employee.name,
          'birthday': this.formatDate(this.employee.birthday),
          'country': this.employee.country,
          'firstday' : this.formatDate(this.employee.firstday),
          'role': this.employee.role
        };
        if (this.type === 'Edit') {
          DataService.edit('employee', this.employee.id, data)
            .then(response => {
              this.$router.push("/employee");
            })
            .catch(e => {
              this.errorGlobal.push('An error occurred');
            })
            .finally(() => {
              this.loading = false;
            });
        } else if (this.type === 'Add') {
          DataService.add('employee', data)
            .then(response => {
              this.$router.push("/employee");
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
      this.errorBirthday = [];
      this.errorFirstday = [];
      this.errorCountry = [];
      if (!EmptyValidator.validate(this.employee.name)) {
        this.errorName.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.employee.birthday)) {
        this.errorBirthday.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.employee.country)) {
        this.errorCountry.push(EmptyValidator.message());
      }
      if (!EmptyValidator.validate(this.employee.firstday)) {
        this.errorFirstday.push(EmptyValidator.message());
      }
      const errors = this.errorName + this.errorBirthday + this.errorCountry + this.errorFirstday;
      if (errors.length === 0) {
        return true;
      }
      return errors;
    }
  },
  formatDate(date) {
    const offset = date.getTimezoneOffset();
    date = new Date(date.getTime() - (offset*60*1000));
    return date.toISOString().split('T')[0];
  },
  mounted() {
    if (!this.$route.params.id) {
      this.type = 'Add';
    } else {
      this.type = 'Edit';
      this.retrieveEmployee(this.$route.params.id);
    }
  }
}
</script>
