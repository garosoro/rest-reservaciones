<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { onMounted } from 'vue';
import axios from 'axios';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const tablesAvailables = ref(0);
const activeReservations = ref(0);

onMounted(() => {
    getCountTablesAvailables();
    getCountActiveReservations();
});

function getCountTablesAvailables() {
    axios.get(route('search.available'))
        .then((response) => {
            console.log('Available tables:', response.data);
            tablesAvailables.value = response.data.count;
        })
        .catch((error) => {
            console.error('Error fetching available tables:', error);
            if (error.response) {
                console.error('Response data:', error.response.data);
                console.error('Response status:', error.response.status);
            }
        });
}
function getCountActiveReservations() {
    axios.get(route('reservations.active'))
        .then((response) => {
            console.log('Available tables:', response.data);
            activeReservations.value = response.data.count;
        })
        .catch((error) => {
            console.error('Error fetching available tables:', error);
            if (error.response) {
                console.error('Response data:', error.response.data);
                console.error('Response status:', error.response.status);
            }
        });
}
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <v-card title="Mesas Disponibles">
                    <v-col class="text-[24px] font-bold" cols="6">
                        {{ tablesAvailables }}
                    </v-col>
                </v-card>
                <v-card title="Reservas Activas">
                    <v-col class="text-[24px] font-bold" cols="6">
                        {{ activeReservations }}
                    </v-col>
                </v-card>
            </div>
        </div>
    </AppLayout>
</template>
