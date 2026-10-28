// resources/js/stores/posStore.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const usePosStore = defineStore('pos', {
    state: () => ({
        selectedCategory: 'All Menu',
        categories: ['All Menu', 'Coffee', 'Tea', 'Non Coffee', 'Sandwich', 'Snack'],
        menuItems: [],
        cart: [],
        selectedTable: '',
        orderType: 'Dine In',
        paymentMethod: 'Cash',
        orderNumber: '#' + Math.floor(100000 + Math.random() * 900000)
    }),

    getters: {
        filteredItems: (state) => {
            if (state.selectedCategory === 'All Menu') return state.menuItems;
            return state.menuItems.filter(item => item.category === state.selectedCategory);
        },
        subTotal: (state) => {
            return state.cart.reduce((total, item) => total + (Number(item.unitPrice || 0) * item.quantity), 0);
        },
        // Removed taxAmount getter entirely
        grandTotal() {
            return this.subTotal; // Grand total is now equal to subtotal
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

        addToCart(product, selectedOption = null) {
            const optionTag = selectedOption || (Array.isArray(product.options) ? product.options[0] : product.options) || 'Default';

            const existingItem = this.cart.find(
                item => item.id === product.id && item.tag === optionTag
            );

            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    tag: optionTag,
                    image: product.image,
                    unitPrice: Number(product.price || product.unitPrice || 0),
                    quantity: 1
                });
            }
        },

        updateQuantity(itemId, delta, selectedOption = null) {
            const index = this.cart.findIndex(item => {
                const matchesId = item.id === itemId;
                const matchesOption = selectedOption ? item.tag === selectedOption : true;
                return matchesId && matchesOption;
            });

            if (index !== -1) {
                this.cart[index].quantity += delta;
                if (this.cart[index].quantity <= 0) {
                    this.cart.splice(index, 1);
                }
            }
        },

        async placeOrder(customerName) {
            if (this.cart.length === 0) {
                throw new Error('Cart is empty!');
            }

            try {
                const payload = {
                    order_number: this.orderNumber,
                    customer_name: customerName || 'Guest',
                    table_number: this.selectedTable || 'N/A',
                    order_type: this.orderType || 'Dine In',
                    payment_method: this.paymentMethod || 'Cash',
                    sub_total: this.subTotal,
                    tax_amount: 0,
                    grand_total: this.grandTotal,
                    cart: this.cart
                };

                await axios.post('/api/orders', payload);

                // Reset store state after successful placement
                this.cart = [];
                this.orderNumber = '#' + Math.floor(100000 + Math.random() * 900000);
                this.selectedTable = '';

                return true;
            } catch (error) {
                console.error('Order submission error:', error.response?.data || error.message);
                throw error;
            }
        }
    }
});