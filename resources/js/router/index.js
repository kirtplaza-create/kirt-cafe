import { createRouter, createWebHistory } from 'vue-router';
import PosView from '../views/PosView.vue';
import OrderListView from '../views/OrderListView.vue';

const routes = [
    {
        path: '/',
        name: 'pos',
        component: PosView,
    },
    {
        path: '/orders',
        name: 'order-list',
        component: OrderListView,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;