<template>
  <div class="">
    <router-link to="/quotes-ui" class="back-btn">← Back</router-link>
    <div v-if="quote" class="detail-card">
      <h2 class="">"{{ quote.quote }}"</h2>
      <p class="">- {{ quote.author }}</p>
    </div>
    <div v-else class="">Getting quote #{{ $route.params.id }}...</div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const quote = ref<{id: number, quote: string, author: string} | null>(null);

onMounted(async () => {
    const id = route.params.id;
    const response = await fetch(`/api/quotes-package/${id}`);
    quote.value = await response.json();
});
</script>