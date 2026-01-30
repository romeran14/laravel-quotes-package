<template>
  <div class="">
    <h1 class="">Quotes List</h1>
    <div v-if="loading">Loading...</div>
    <ul v-else class="quote-list">
      <li v-for="quote in paginatedQuotes" :key="quote.id" class="quote-card">
        <p class="quote-text">"{{ quote.quote }}"</p>
        <p class="quote-author">- {{ quote.author }}</p>
        <router-link :to="'/quotes-ui/' + quote.id" class="btn">See details</router-link>
      </li>
    </ul>

    <div class="pagination-controls">
      <button @click="page--" :disabled="page === 1" class="btn">Before</button>
      <span class="page-number">Page {{ page }}</span>
      <button @click="page++" :disabled="page >= maxPages" class="btn">Next</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';

interface Quote { id: number; quote: string; author: string; }

const quotes = ref<Quote[]>([]);
const page = ref(1);
const perPage = 5;
const loading = ref(true);

const fetchQuotes = async () => {
    const response = await fetch('/api/quotes-package');
    quotes.value = await response.json();
    loading.value = false;
};

const paginatedQuotes = computed(() => {
    const start = (page.value - 1) * perPage;
    return quotes.value.slice(start, start + perPage);
});

const maxPages = computed(() => Math.ceil(quotes.value.length / perPage));

onMounted(fetchQuotes);
</script>