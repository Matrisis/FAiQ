<template>
    <div>
        <h1>Manage Your Subscription</h1>

        <div v-if="loading">
            Loading...
        </div>

        <div v-else>
            <div v-if="subscription">
                <p><strong>Status:</strong> {{ subscription.stripe_status }}</p>
                <p><strong>Plan:</strong> {{ subscription.name }}</p>

                <div v-if="subscriptionEndsAt">
                    <p>Your subscription will end on {{ subscriptionEndsAt }}.</p>
                </div>

                <button v-if="canCancel" @click="cancelSubscription">Cancel Subscription</button>
                <button v-if="canResume" @click="resumeSubscription">Resume Subscription</button>
            </div>
            <div v-else>
                <p>You do not have an active subscription.</p>
            </div>

            <h2>Invoices</h2>
            <div v-if="invoices.length">
                <table>
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Download</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="invoice in invoices" :key="invoice.id">
                        <td>{{ formatDate(invoice.date) }}</td>
                        <td>{{ invoice.total }}</td>
                        <td><a :href="downloadInvoiceUrl(invoice.id)">Download</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <p>No invoices found.</p>
            </div>

            <h2>Request a Refund</h2>
            <button @click="requestRefund">Request Refund for Last Invoice</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'SubscriptionManagement',
    data() {
        return {
            loading: true,
            subscription: null,
            invoices: [],
        };
    },
    computed: {
        subscriptionEndsAt() {
            if (this.subscription && this.subscription.ends_at) {
                return new Date(this.subscription.ends_at).toLocaleDateString();
            }
            return null;
        },
        canCancel() {
            return this.subscription && !this.subscription.on_grace_period;
        },
        canResume() {
            return this.subscription && this.subscription.on_grace_period;
        },
    },
    methods: {
        fetchSubscriptionData() {
            axios
                .get('/api/subscription')
                .then((response) => {
                    this.subscription = response.data.subscription;
                    this.invoices = response.data.invoices;
                })
                .catch((error) => {
                    console.error('Error fetching subscription data:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        cancelSubscription() {
            axios
                .post('/api/subscription/cancel')
                .then((response) => {
                    alert(response.data.message);
                    this.fetchSubscriptionData();
                })
                .catch((error) => {
                    console.error('Error cancelling subscription:', error);
                });
        },
        resumeSubscription() {
            axios
                .post('/api/subscription/resume')
                .then((response) => {
                    alert(response.data.message);
                    this.fetchSubscriptionData();
                })
                .catch((error) => {
                    console.error('Error resuming subscription:', error);
                });
        },
        requestRefund() {
            axios
                .post('/api/subscription/refund')
                .then((response) => {
                    alert(response.data.message);
                })
                .catch((error) => {
                    console.error('Error requesting refund:', error);
                    alert('Failed to request refund: ' + error.response.data.message);
                });
        },
        formatDate(timestamp) {
            return new Date(timestamp * 1000).toLocaleDateString();
        },
        downloadInvoiceUrl(invoiceId) {
            return `/api/subscription/invoice/${invoiceId}`;
        },
    },
    mounted() {
        this.fetchSubscriptionData();
    },
};
</script>

<style scoped>
/* Add your styles here */
table {
    width: 100%;
    border-collapse: collapse;
}
th,
td {
    border: 1px solid #ccc;
    padding: 8px;
}
</style>
