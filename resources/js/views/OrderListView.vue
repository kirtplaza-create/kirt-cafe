<template>
    <div class="flex gap-6 max-w-[1440px] mx-auto min-h-[90vh]">
        <Sidebar />

        <main class="flex-1 min-w-0 bg-white rounded-3xl p-6 shadow-sm flex flex-col justify-start">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Order List</h1>
                    <p class="text-xs text-gray-400">Manage live kitchen and customer orders</p>
                </div>
                <button 
                    @click="fetchOrders"
                    class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-bold hover:bg-blue-100 transition-colors cursor-pointer"
                >
                    Refresh List
                </button>
            </div>

            <!-- Empty State -->
            <div v-if="orders.length === 0" class="text-center py-16 text-gray-400 text-sm">
                No active pending orders right now.
            </div>

            <!-- Orders Grid -->
            <div v-else class="grid grid-cols-2 gap-4">
                <div 
                    v-for="order in orders" 
                    :key="order.id" 
                    class="border border-gray-100 rounded-2xl p-4 shadow-xs bg-gray-50 flex flex-col justify-between"
                >
                    <div>
                        <!-- Order Header -->
                        <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200">
                            <div>
                                <span class="font-bold text-blue-600 text-sm">{{ order.order_number || '#000000' }}</span>
                                <span class="text-xs text-gray-500 ml-2">({{ order.order_type || 'Dine In' }})</span>
                            </div>
                            <span class="bg-yellow-100 text-yellow-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">
                                {{ order.status || 'pending' }}
                            </span>
                        </div>

                        <!-- Customer Details -->
                        <div class="text-xs text-gray-600 mb-3 space-y-1">
                            <div><span class="font-semibold">Customer:</span> {{ order.customer_name || 'Guest' }}</div>
                            <div><span class="font-semibold">Table:</span> {{ order.table_number || 'N/A' }}</div>
                            <div><span class="font-semibold">Payment:</span> {{ order.payment_method || 'Cash' }}</div>
                        </div>

                        <!-- Items List -->
                        <div class="bg-white rounded-xl p-3 space-y-2 mb-4 border border-gray-100 max-h-36 overflow-y-auto">
                            <div 
                                v-for="item in (order.items || [])" 
                                :key="item.id"
                                class="flex justify-between items-center text-xs"
                            >
                                <span class="text-gray-700 font-medium">
                                    {{ item.quantity }}x {{ item.product_name }}
                                </span>
                                <span class="text-gray-500 font-semibold">
                                    ₱{{ formatPrice(item.subtotal) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total & Actions -->
                    <div class="pt-2 border-t border-gray-200 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 block">Total Amount</span>
                            <span class="text-base font-bold text-gray-800">
                                ₱{{ formatPrice(order.grand_total) }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <button 
                                @click="updateStatus(order.id, 'cancelled')"
                                class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-colors cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button 
                                @click="updateStatus(order.id, 'completed')"
                                class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition-colors cursor-pointer"
                            >
                                Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Sidebar from '@/components/Sidebar.vue';

const orders = ref([]);

const formatPrice = (val) => {
    const num = parseFloat(val);
    return isNaN(num) ? '0.00' : num.toFixed(2);
};

const fetchOrders = async () => {
    try {
        const response = await axios.get('/api/orders');
        console.log('Orders API Response:', response.data);
        orders.value = Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        console.error('Failed to fetch orders:', error);
        orders.value = [];
    }
};

const updateStatus = async (orderId, status) => {
    try {
        await axios.patch(`/api/orders/${orderId}/status`, { status });
        orders.value = orders.value.filter(o => o.id !== orderId);
    } catch (error) {
        console.error('Failed to update status:', error);
        alert('Failed to update order status.');
    }
};

onMounted(() => {
    fetchOrders();
});
</script>