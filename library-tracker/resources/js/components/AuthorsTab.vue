<template>
  <v-card flat>
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-h6">Authors</span>
      <div class="d-flex align-center ga-2">
        <v-btn size="small" color="primary" variant="flat" @click="addAuthor">
          <v-icon icon="mdi-plus" class="me-1" /> Add Author
        </v-btn>
        <v-btn size="small" variant="tonal" :loading="loading" @click="loadAuthors">
          Refresh
        </v-btn>
      </div>
    </v-card-title>

    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items-per-page-options="[25, 50, 100]"
      :items-per-page="25"
      :items="authors"
      :loading="loading"
    >
      <template #loading>
        <v-sheet class="pa-4 text-center">Loading authors...</v-sheet>
      </template>

      <template #item.actions="{ item }">
        <div class="d-flex flex-nowrap">
          <v-btn
            size="small"
            variant="text"
            icon="mdi-pencil"
            :disabled="loading"
            @click="editAuthor(item.id)"
          />
          <v-btn
            size="small"
            variant="text"
            color="error"
            icon="mdi-delete"
            :disabled="loading"
            @click="deleteAuthor(item.id)"
          />
        </div>
      </template>
    </v-data-table>

    <!-- Add / Edit Dialog -->
    <v-dialog persistent v-model="dialog.open" max-width="640">
      <v-card>
        <v-card-title class="text-h6">{{ dialog.form.id ? 'Edit Author' : 'Add Author' }}</v-card-title>

        <v-card-text>
          <v-form ref="authorForm" @submit.prevent="submitDialog">
            <v-row dense>
              <v-col cols="12" sm="6">
                <v-text-field
                  label="First Name"
                  v-model.trim="dialog.form.first_name"
                  autocomplete="off"
                />
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  label="Last Name"
                  v-model.trim="dialog.form.last_name"
                  autocomplete="off"
                />
              </v-col>
              <v-col cols="12">
                <v-textarea
                  label="Biography"
                  v-model.trim="dialog.form.biography"
                  :rows="4"
                  auto-grow
                />
              </v-col>
            </v-row>

            <button type="submit" class="d-none" />
          </v-form>
        </v-card-text>

        <v-card-actions class="justify-end">
          <v-btn variant="text" @click="closeDialog" :disabled="dialog.saving">Cancel</v-btn>
          <v-btn color="primary" :loading="dialog.saving" @click="submitDialog">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { toast } from 'vue3-toastify';
import axios from 'axios';

export default {
  name: 'AuthorsTab',

  data () {
    return {
      loading: false,
      authors: [],
      headers: [
        { title: 'ID', key: 'id' },
        { title: 'First Name', key: 'first_name' },
        { title: 'Last Name', key: 'last_name' },
        { title: 'Biography', key: 'biography' },
        { title: '', key: 'actions', sortable: false, align: 'end' },
      ],

      dialog: {
        open: false,
        saving: false,
        form: {
          id        : '',
          first_name: '',
          last_name : '',
          biography : '',
        },
      },
    };
  },

  methods: {
    loadAuthors () {
      this.loading = true;

      return axios.get('/api/v1/authors')
        .then(r => this.authors = r.data)
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        })
        .finally(() => this.loading = false);
    },

    dialogInit (form) {
      this.dialog.form = {
          id        : '',
          first_name: '',
          last_name : '',
          biography : '',
        };
    },

    addAuthor () {
      this.dialogInit();
      this.dialog.open = true;
    },

    editAuthor (id) {
      return axios.get(`/api/v1/authors/${id}`)
        .then(r => {
          this.dialog.form = r.data;
          this.dialog.open = true;
        })
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        });
    },

    submitDialog () {
      if (this.dialog.saving) return;
      this.dialog.saving = true;

      const payload = {
        first_name: this.dialog.form.first_name.trim(),
        last_name : this.dialog.form.last_name.trim(),
        biography : this.dialog.form.biography.trim() || '',
      };

      const editing = !!this.dialog.form.id;
      const url = editing ? `/api/v1/authors/${this.dialog.form.id}` : '/api/v1/authors';

      axios[editing ? 'put' : 'post'](url, payload)
        .then(() => {
          toast(editing ? 'Author updated' : 'Author created', { type: 'success' });
          this.loadAuthors();
          this.closeDialog();
        })
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', { type: 'error' });
          console.error(e);
        })
        .finally(() => this.dialog.saving = false);
    },

    closeDialog () {
      this.dialog.open = false;
      this.dialogInit();
    },

    deleteAuthor (id) {
      if (!confirm('Are you sure you want to delete this author?')) return;
      this.loading = true;

      axios.delete(`/api/v1/authors/${id}`)
        .then(() => {
          toast('Author deleted', { type: 'success' });
          this.loadAuthors();
        })
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', { type: 'error' });
          console.error(e);
          this.dialog.saving = false;
        });
    },
  },

  mounted () {
    this.loadAuthors();
  },
};
</script>
