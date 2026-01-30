import { createRouter, createWebHistory } from 'vue-router';
import QuoteList from './components/QuoteList.vue';
import QuoteDetail from './components/QuoteDetail.vue';

const routes = [
    { path: '/quotes-ui', component: QuoteList },
    { path: '/quotes-ui/:id', component: QuoteDetail, props: true },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});