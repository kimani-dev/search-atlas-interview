<template>
  <v-card flat>
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-h6">Books</span>
      <v-btn size="small" variant="tonal" :loading="loading" @click="loadBooks">
        Refresh
      </v-btn>
    </v-card-title>

    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items-per-page-options="[25, 50, 100]"
      :items-per-page="25"
      :items="books"
      :loading="loading"
    >
      <template #item.author="{item}">
        {{ item.author.first_name }} {{ item.author.last_name }}
      </template>

      <template #item.published_at="{item}">
        {{ moment(item.published_at).format('MMMM YYYY') }}
      </template>

      <template #loading>
        <v-sheet class="pa-4 text-center">Loading books...</v-sheet>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { toast } from 'vue3-toastify';
import axios from 'axios';
import moment from 'moment';

export default {
  name: 'BooksTab',

  data () {
    return {
      moment,

      loading: false,
      books: [],
      headers: [
        { title: 'ID', key: 'id' },
        { title: 'Title', key: 'title' },
        { title: 'Author', key: 'author', sortable: false },
        { title: 'Genre', key: 'genre' },
        { title: 'ISBN', key: 'isbn' },
        { title: 'Publish Date', key: 'published_at' },
      ],
    };
  },

  methods: {
    loadBooks () {
      this.loading = true;

      return axios.get('/api/v1/books')
        .then(r => this.books = r.data)
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        })
        .finally(() => this.loading = false);
    },
  },

  mounted () {
    this.loadBooks();
  },
};
</script>
