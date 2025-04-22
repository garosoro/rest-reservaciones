<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import axios from 'axios';
import { ref } from 'vue';

const chartNext7Data = ref<{ value: number; label: string }[]>([])

const chartLast7Data = ref<{ value: number; label: string }[]>([])

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
    getLast7DaysReservations();
    getNext7DaysReservations();
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
function getLast7DaysReservations() {
    axios.get(route('search.last7days'))
        .then((response) => {
            console.log('Available tables:', response.data);
            chartLast7Data.value = response.data.data.map((item: { date: string; count: number }) => ({
                value: item.count,
                label: formatDate(item.date),
            }));
            console.log('chartLast7Data', chartLast7Data.value);
        })
        .catch((error) => {
            console.error('Error fetching available tables:', error);
            if (error.response) {
                console.error('Response data:', error.response.data);
                console.error('Response status:', error.response.status);
            }
        });
}
function getNext7DaysReservations() {
    axios.get(route('search.next7days'))
        .then((response) => {
            console.log('Available tables:', response.data);
            chartNext7Data.value = response.data.data.map((item: { date: string; count: number }) => ({
                value: item.count,
                label: formatDate(item.date),
            }));
            console.log('chartNext7Data', chartNext7Data.value);
        })
        .catch((error) => {
            console.error('Error fetching available tables:', error);
            if (error.response) {
                console.error('Response data:', error.response.data);
                console.error('Response status:', error.response.status);
            }
        });
}
function formatDate(dateString: string): string {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-ES').format(date); // 'en-GB' formats as dd-mm-yyyy
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
            <div class="grid auto-rows-min gap-4">
                <v-card class="mx-auto w-full text-center" color="green" dark>
                    <v-card-text>
                        <v-sheet color="rgba(0, 0, 0, 1)">
                            <v-sparkline :auto-draw="true" :auto-draw-duration="80" :model-value="chartLast7Data"
                                color="rgba(255, 255, 255, .7)" height="100" padding="24" stroke-linecap="round" smooth>
                                <template v-slot:label="{ index, value }">
                                    {{ chartLast7Data[index].label }}: {{ value }}
                                </template>
                            </v-sparkline>
                        </v-sheet>
                    </v-card-text>

                    <v-card-text>
                        <div class="text-h4 font-weight-thin">Reservas de 7 días anteriores</div>
                    </v-card-text>
                </v-card>
                <v-card class="mx-auto w-full text-center" color="green" dark>
                    <v-card-text>
                        <v-sheet color="rgba(0, 0, 0, 1)">
                            <v-sparkline :model-value="chartNext7Data" color="rgba(255, 255, 255, .7)" height="100"
                                padding="24" stroke-linecap="round" smooth>
                                <template v-slot:label="{ index, value }">
                                    {{ chartNext7Data[index].label }}: {{ value }}
                                </template>
                            </v-sparkline>
                        </v-sheet>
                    </v-card-text>

                    <v-card-text>
                        <div class="text-h4 font-weight-thin">Reservas de los proximos 7 días</div>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </AppLayout>
</template>
