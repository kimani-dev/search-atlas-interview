<template>
  <v-app>
    <v-main class="pa-6">
      <v-container class="py-8 d-flex justify-center">
        <div style="width: 100%; max-width: 1200px;">
          <v-card elevation="2">
            <v-card-title class="d-flex align-center justify-space-between">
              <div class="text-h5 font-weight-bold">Library Tracker</div>
              <div class="text-caption text-medium-emphasis">Technical Assessment</div>
            </v-card-title>

            <v-divider></v-divider>

            <v-tabs v-model="tab" grow class="px-4">
              <v-tab value="authors" prepend-icon="mdi-account">Authors</v-tab>
              <v-tab value="books" prepend-icon="mdi-book-open-page-variant">Books</v-tab>
              <v-tab value="loans" prepend-icon="mdi-book-arrow-right">Loans</v-tab>
            </v-tabs>

            <v-window v-model="tab" class="pa-4">
              <v-window-item value="authors">
                <AuthorsTab />
              </v-window-item>

              <v-window-item value="books">
                <BooksTab />
              </v-window-item>

              <v-window-item value="loans">
                <LoansTab />
              </v-window-item>
            </v-window>
          </v-card>
        </div>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import AuthorsTab from './components/AuthorsTab.vue'
import BooksTab from './components/BooksTab.vue'
import LoansTab from './components/LoansTab.vue'

export default {
  name: 'App',

  components: {
    AuthorsTab,
    BooksTab,
    LoansTab,
  },

  data () {
    return {
      tab: 'authors',
    };
  },
};
</script>
