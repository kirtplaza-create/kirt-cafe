import { defineStore } from 'pinia';
import axios from 'axios';

export const usePosStore = defineStore('pos', {
    state: () => ({
        selectedCategory: 'All Menu',
        categories: ['All Menu', 'Coffee', 'Tea', 'Non Coffee', 'Sandwich', 'Snack'],
        menuItems: [],
        cart: [],
        taxRate: 0.11,
        selectedTable: '',
        orderType: 'Dine In',
        orderNumber: '#' + Math.floor(100000 + Math.random() * 900000) // Live order ID
    }),
    getters: {
        filteredItems: (state) => {
            if (state.selectedCategory === 'All Menu') return state.menuItems;
            return state.menuItems.filter(item => item.category === state.selectedCategory);
        },
        subTotal: (state) => {
        return state.cart.reduce((total, item) => total + (item.unitPrice * item.quantity), 0);
        },
        taxAmount() {
            return this.subTotal * 0.12; // 12% PH VAT
        },
        grandTotal() {
            return this.subTotal + this.taxAmount;
        }
    },
    actions: {
        async fetchMenuItems() {
            try {
                const response = await axios.get('/api/menu');
                this.menuItems = response.data;
            } catch (error) {
                console.error('Failed to fetch menu items:', error);
            }
        },
        addToCart(product) {
            const existingItem = this.cart.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    tag: product.options?.[0] || 'Default',
                    unitPrice: product.price,
                    quantity: 1
                });
            }
        },
        updateQuantity(itemId, delta) {
            const item = this.cart.find(i => i.id === itemId);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) {
                    this.cart = this.cart.filter(i => i.id !== itemId);
                }
            }
        },
        // resources/js/stores/posStore.js

        // resources/js/stores/posStore.js

        async placeOrder(customerName) {
            if (this.cart.length === 0) return alert('Cart is empty!');

            try {
                const payload = {
                    order_number: this.orderNumber,
                    customer_name: customerName || 'Guest',
                    table_number: this.selectedTable || 'N/A',
                    order_type: this.orderType || 'Dine In',
                    payment_method: this.paymentMethod || 'Cash',
                    sub_total: this.subTotal,
                    tax_amount: this.taxAmount,
                    grand_total: this.grandTotal,
                    cart: this.cart
                };

                const response = await axios.post('/api/orders', payload);

                alert(`Order ${this.orderNumber} successfully placed!`);

                // Clear cart & reset order details
                this.cart = [];
                this.orderNumber = '#' + Math.floor(100000 + Math.random() * 900000);
                this.selectedTable = '';
            } catch (error) {
                console.error('Order submission error:', error.response?.data || error.message);
                alert('Failed to place order. Check console for details.');
            }
        }
    }
});