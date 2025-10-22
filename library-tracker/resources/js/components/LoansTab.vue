<template>
  <v-card flat>
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-h6">Loans</span>
      <v-btn size="small" variant="tonal" :loading="loading" @click="loadLoans">
        Refresh
      </v-btn>
    </v-card-title>

    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items-per-page-options="[25, 50, 100]"
      :items-per-page="25"
      :items="loans"
      :loading="loading"
    >
      <template #item.loaned_at="{item}">
        {{ moment(item.loaned_at).format('MMM Do YYYY \\a\\t h:mm A') }}
      </template>

      <template #item.returned_at="{item}">
        {{ item.returned_at ? moment(item.returned_at).format('MMM Do YYYY \\a\\t h:mm A') : '-' }}
      </template>

      <template #item.due_at="{item}">
        <span v-if="!item.is_overdue">{{ moment(item.due_at).fromNow() }}</span>
        <v-chip v-else color="error" text="Overdue" />
      </template>

      <template #item.actions="{ item }">
        <v-icon icon="mdi-plus" @click="selectExtend(item)" />
      </template>

      <template #loading>
        <v-sheet class="pa-4 text-center">Loading loans...</v-sheet>
      </template>
    </v-data-table>
  </v-card>

  <!-- extend loan dialog -->
   <v-dialog v-model="extendLoanDialog">
    <v-row justify="center">
      <v-col cols="12" md="6" align-self="center">
        <v-card title="Extend Loan" subtitle="Extend your loan for a number of days">
          <v-card-text>
            <v-alert
              v-if="disableLoanExtension"
              type="error"
              text="Book has already been returned"
              variant="outlined"
              class="mb-2"
            />

            <v-form :disabled="disableLoanExtension" @submit.prevent="extendLoan">
              <v-select v-model="additionalDays" :items="loanExtendOptions"/>
              <div class="d-flex justify-end mt-2">
                <v-btn text="Extend Loan" type="submit" color="primary" :loading="extendLoading" :disabled="disableLoanExtension" />
              </div>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
   </v-dialog>
</template>

<script>
import { toast } from 'vue3-toastify';
import axios from 'axios';
import moment from 'moment';

export default {
  name: 'LoansTab',

  data () {
    return {
      moment,

      loading: false,
      loans: [],
      headers: [
        { title: 'ID', key: 'id' },
        { title: 'User', key: 'user.name' },
        { title: 'Book', key: 'book.title' },
        { title: 'Loan Date', key: 'loaned_at' },
        { title: 'Due Date', key: 'due_at' },
        { title: 'Return Date', key: 'returned_at' },
        { title: 'Actions', key: 'actions' },
      ],

      extendLoanDialog: false,
      loanToBeExtended: null,
      loanExtendOptions: [
        { title: "1 Day", value: 1 },
        { title: "3 Days", value: 3 },
        { title: "7 Days", value: 7 },
        { title: "14 Days", value: 14 },
      ],
      additionalDays: 1,
      extendLoading: false,
    };
  },

  computed: {
    disableLoanExtension() {
      return !!this.loanToBeExtended.returned_at;
    }
  },

  methods: {
    loadLoans () {
      this.loading = true;

      return axios.get('/api/v1/loans')
        .then(r => this.loans = r.data)
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        })
        .finally(() => this.loading = false);
    },
    selectExtend(loan) {
      this.extendLoanDialog = true;
      this.loanToBeExtended = loan;
    },
    extendLoan () {
      this.extendLoading = true;

      return axios.put(`/api/v1/loans/extend/${this.loanToBeExtended.id}`, { additional_days: this.additionalDays})
      .then(( () => {
          this.extendLoanDialog = false;
          toast("Loan extended succesfully", { type: 'success' });
      }))
      .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        })
        .finally(() => {
          this.extendLoading = false;
          this.loadLoans();
        });
    },
  },

  mounted () {
    this.loadLoans();
  },
};
</script>
