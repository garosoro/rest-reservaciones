<script lang="ts" setup>
import { onMounted, ref, shallowRef } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { Reservation } from '@/types/reservation'
import axios from 'axios';

onMounted(() => {
    // Fetch initial data for diners and tables
    fetchTables();
});

const dialog = shallowRef(false)
const isEditing = shallowRef(false)
const modal2 = ref(false)
// Default reservation data
const formData = useForm<Reservation>({
    id: null,
    date: '',
    time: '',
    number_of_people: 0,
    diner_id: '',
    table_id: '',
    status: '',
})

// Form validation rules
const form = ref(); // Reference to the form

const rules = {
    date: [
        (value: string) => !!value || 'La fecha es obligatoria.',
    ],
    time: [
        (value: string) => !!value || 'La hora es obligatoria.',
    ],
    number_of_people: [
        (value: number) => !!value || 'El número de personas es obligatorio.',
        (value: number) => Number.isInteger(value) || 'Debe ser un número entero.',
        (value: number) => value > 0 || 'Debe ser mayor que 0.',
    ],
    diner_id: [
        (value: string) => !!value || 'El comensal es obligatorio.',
    ],
    table_id: [
        (value: string) => !!value || 'La mesa es obligatoria.',
    ],
    status: [
        (value: string) => !!value || 'El estado es obligatorio.',
    ],
};

// Props
const props = defineProps<{
    reservations: {
        data: Array<Reservation>;
    };
}>();

// Breadcrumbs
const breadcrumbs = [
    { title: 'Reservas', href: '/reservations' },
]

// Table Headers
const headers: Array<{
    title: string;
    key: string;
    align?: 'start' | 'end' | 'center';
    sortable?: boolean;
    value?: (item: Reservation) => string | number;
}> = [
        { title: 'Id', key: 'id', align: 'start' },
        {
            title: 'Fecha y Hora',
            key: 'datetime',
            align: 'start',
        },
        {
            title: 'Comensal',
            key: 'diner',
            align: 'start',
        },
        {
            title: 'Mesa',
            key: 'table',
            align: 'start',
        },
        { title: 'Estado', key: 'status', align: 'start' },
        { title: 'Personas', key: 'number_of_people', align: 'start' },
        { title: 'Acciones', key: 'actions', align: 'end', sortable: false },
    ];

// Function to open the modal for creating a reservation
function add() {
    isEditing.value = false
    formData.reset()
    dialog.value = true
}

// Function to edit a reservation
function edit(id: number) {
    isEditing.value = true
    dialog.value = true
    console.log("Edit" + id)
    // Fetch reservation data using axios
    axios
        .get(route('reservations.edit', id))
        .then((response) => {
            const reservation = response.data.reservation;
            console.log(reservation)
            Object.assign(formData, reservation)
            console.log('Reservation data loaded successfully:', formData);
        })
        .catch((error) => {
            console.error('Error fetching reservation data:', error);
        });
    console.log(formData)
}

// Function to delete a reservation
function remove(id: number) {
    console.log("Remove" + id)
    if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
        formData.delete(route('reservations.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Reserva eliminada con éxito');
            },
            onError: (errors) => {
                console.error('Error deleting reservation:', errors);
            },
        })
    }
    console.log(formData)
}

// Function to cancel the form
function cancel() {
    dialog.value = false
    isEditing.value = false
    formData.reset()
}

// Function to save a reservation
function save() {
    console.log("form.value")
    console.log(form.value)
    console.log("formData.value")
    console.log(formData)
    console.log(isEditing.value)
    form.value.validate().then((success: { valid: boolean }) => {
        if (success.valid) {
            if (isEditing.value && formData.id) {
                console.log('Updating reservation:', formData);
                // Update the reservation
                formData.put(route('reservations.update', formData.id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        dialog.value = false
                        form.value.reset();
                        formData.reset();
                    },
                    onError: (errors) => {
                        console.error('Error updating reservation:', errors);
                    },
                })
            } else {
                console.log('Creating new reservation:', formData);
                // Create a new reservation
                formData.post(route('reservations.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        dialog.value = false
                        form.value.reset();
                        formData.reset();
                        diners.value = [];
                    },
                    onError: (errors) => {
                        console.error('Error creating reservation:', errors);
                    },
                })
            }
        }
    });
}

//Lista de comensales
const diners = ref<{ text: string; value: number }[]>([]);
const loadingDiners = ref(false);

// funcion para buscar comensales
// Esta función se llama cuando el usuario escribe en el campo de búsqueda
function fetchDiners(search: string | null = '') {
    search = search || '';
    if (search.length < 3) {
        diners.value = [];
        return;
    }

    loadingDiners.value = true; // Set loading state
    axios
        .get(route('reservations.search', { query: search })) // Replace with your backend route
        .then((response) => {
            console.log('Diners:', response.data.diners);
            diners.value = response.data.diners.map((diner: { id: number; name: string }) => ({
                text: diner.name,
                value: diner.id,
            }));
            console.log('Diners loaded successfully:', diners.value);
        })
        .catch((error) => {
            console.error('Error fetching diners:', error);
        })
        .finally(() => {
            loadingDiners.value = false; // Reset loading state
        });
}
//Lista de mesas
const tables = ref<{ text: string; value: number }[]>([]);
function fetchTables() {
    axios
        .get(route('tables.availables')) // Replace with your backend route
        .then((response) => {
            console.log('Tables:', response.data.tables);
            tables.value = response.data.tables.map((table: { id: number; number: string }) => ({
                text: table.number,
                value: table.id,
            }));
            console.log('Diners loaded successfully:', tables.value);
        })
        .catch((error) => {
            console.error('Error fetching diners:', error);
        })
        .finally(() => {
        });
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Reservas" />
        <div class="container mx-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Lista de Reservas</h1>
                <v-btn class="me-2" prepend-icon="mdi-plus" rounded="lg" text="Crear Reserva" border
                    @click="add"></v-btn>
            </div>
            <div class="overflow-x-auto">
                <v-data-table :headers="headers" :hide-default-footer="reservations.data.length < 11"
                    :items="reservations.data">
                    <template #item.actions="{ item }">
                        <div class="flex gap-2 justify-end">
                            <!-- <v-icon color="info" icon="mdi-pencil" size="large" @click="edit(item.id!)"></v-icon> -->
                            <v-icon color="error" icon="mdi-delete" size="large" @click="remove(item.id!)"></v-icon>
                        </div>
                    </template>
                </v-data-table>
            </div>
        </div>

        <v-dialog v-model="dialog" max-width="500">
            <v-form ref="form" @submit.prevent="save">
                <v-card :subtitle="`${isEditing ? 'Cambia' : 'Añade'} la información de una reserva`"
                    :title="`${isEditing ? 'Edita' : 'Crea'} una Reserva`">
                    <template v-slot:text>
                        <input type="hidden" name="id" v-model="formData.id">
                        <v-date-input v-model="formData.date" :rules="rules.date" :error-messages="formData.errors.date"
                            :clearable="true" label="Fecha de la reserva"></v-date-input>
                        <v-text-field v-model="formData.time" :active="modal2" :rules="rules.time"
                            :error-messages="formData.errors.time" :focused="modal2" label="Hola de la reserva"
                            :clearable="true" prepend-icon="mdi-clock-time-four-outline" readonly>
                            <v-dialog v-model="modal2" activator="parent" width="auto">
                                <v-time-picker v-if="modal2" v-model="formData.time" format="24hr"></v-time-picker>
                            </v-dialog>
                        </v-text-field>
                        <v-number-input v-model="formData.number_of_people" :reverse="false" controlVariant="split"
                            label="Número de Personas" :rules="rules.number_of_people" :hideInput="false"
                            :inset="false"></v-number-input>
                        <v-autocomplete v-model="formData.diner_id" label="Comensal" :rules="rules.diner_id"
                            :items="diners" :loading="loadingDiners" @update:search="fetchDiners" item-title="text"
                            item-value="value" />
                        <v-autocomplete v-model="formData.table_id" label="Mesa" :rules="rules.table_id" :items="tables"
                            item-title="text" item-value="value" />
                    </template>

                    <v-divider></v-divider>

                    <v-card-actions class="bg-surface-light">
                        <v-btn text="Cancelar" variant="plain" @click="cancel"></v-btn>
                        <v-spacer></v-spacer>
                        <v-btn type="submit" text="Guardar"></v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </v-dialog>
    </AppLayout>
</template>

<style lang="css" scoped></style>
