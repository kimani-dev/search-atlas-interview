<template>
  <v-card flat>
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-h6">Report</span>
      <v-btn size="small" variant="tonal" :loading="loading" @click="loadReport">
        Refresh
      </v-btn>
    </v-card-title>

    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items-per-page-options="[25, 50, 100]"
      :items-per-page="25"
      :items="users"
      :loading="loading"
    />
  </v-card>
</template>

<script>
import { toast } from 'vue3-toastify';
import axios from 'axios';
import moment from 'moment';

export default {
  name: 'ReportTab',

  data () {
    return {
      moment,

      loading: false,
      users: [],
      headers: [
        { title: 'Name', key: 'name' },
        { title: 'Email', key: 'email' },
        { title: 'Active Loans', key: 'active_loans' },
      ],
    };
  },

  methods: {
    loadReport () {
      this.loading = true;

      return axios.get('/api/v1/users/top-active')
        .then(r => this.users = r.data)
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        })
        .finally(() => this.loading = false);
    },
  },

  mounted () {
    this.loadReport();
  },
};
</script>
