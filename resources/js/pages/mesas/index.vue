<script lang="ts" setup>
import { ref, shallowRef } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { Table } from '@/types/table'
import axios from 'axios';

const dialog = shallowRef(false)
const isEditing = shallowRef(false)

//Registro por defecto
const formData = useForm<Table>({
    id: null, // Added id property
    number: '',
    capacity: 0,
    status: '',
})

//validacion de los campos del formulario
const form = ref(); // Reference to the form


const rules = {
    capacity: [
        (value: number) => !!value || 'La capacidad es obligatoria.',
        (value: number) => Number.isInteger(value) || 'La capacidad debe ser un número entero.',
        (value: number) => value > 0 || 'La capacidad debe ser mayor que 0.',
        (value: number) => value <= 20 || 'La capacidad no puede ser mayor que 20.',
    ],
};

//props
defineProps<{
    tables: {
        data: Array<Table>;
    };
}>();

// Breadcrumbs
const breadcrumbs = [
    { title: 'Mesas', href: '/tables' },
]
//Table Headers
const headers: Array<{
    title: string;
    key: string;
    align?: 'start' | 'end' | 'center';
    sortable?: boolean;
}> = [
        // { title: 'Id', key: 'id', align: 'start' },
        { title: 'N° de Mesa', key: 'number', align: 'start' },
        { title: 'Capacidad', key: 'capacity', align: 'start' },
        { title: 'Estado', key: 'status', align: 'start' },
        { title: 'Acciones', key: 'actions', align: 'end', sortable: false },
    ];

//funcion para abrir el modal de registro
function add() {
    isEditing.value = false
    formData.reset()
    dialog.value = true
}
//funcion de editar el comensal
function edit(id: number) {
    isEditing.value = true
    dialog.value = true
    console.log("Edit" + id)
    //Hacer una llamada en axios para el comensal
    axios
        .get(route('tables.edit', id))
        .then((response) => {
            const table = response.data.table;
            console.log(table)
            Object.assign(formData, table)
            console.log('Table data loaded successfully:', formData);
        })
        .catch((error) => {
            console.error('Error fetching table data:', error);
        });
    console.log(formData)
}
//funcion para eliminar el comensal
function remove(id: number) {
    console.log("Remove" + id)
    if (confirm('¿Estás seguro de que deseas eliminar esta mesa?')) {
        formData.delete(route('tables.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Mesa eliminado con éxito');
            },
            onError: (errors) => {
                console.error('Error deleting record:', errors);
            },
        })
    }
    console.log(formData)
}
//funcion para cancelar el registro
function cancel() {
    dialog.value = false
    isEditing.value = false
    formData.reset()
}
//funcion para guardar el comensal
function save() {
    console.log("form.value")
    console.log(form.value)
    console.log("formData.value")
    console.log(formData)
    console.log(isEditing.value)
    form.value.validate().then((success: { valid: boolean }) => {
        if (success.valid) {
            if (isEditing.value && formData.id) {
                console.log('Updating record:', formData);
                // Add logic to update the record
                formData.put(route('tables.update', formData.id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        dialog.value = false
                        form.value.reset();
                        formData.reset();
                    },
                    onError: (errors) => {
                        console.error('Error updating record:', errors);
                    },
                })
            } else {
                console.log('Creating new record:', formData);
                // Add logic to create a new record
                formData.post(route('tables.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        dialog.value = false
                        form.value.reset();
                        formData.reset();
                    },
                    onError: (errors) => {
                        console.error('Error creating record:', errors);
                    },
                })
            }
        }
    });
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Mesas" />
        <div class="container mx-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Lista de Mesas</h1>
                <v-btn class="me-2" prepend-icon="mdi-plus" rounded="lg" text="Crear Mesa" border @click="add"></v-btn>
            </div>
            <div class="overflow-x-auto">
                <v-data-table :headers="headers" :hide-default-footer="tables.data.length < 11" :items="tables.data">
                    <template #item.actions="{ item }">
                        <div class="flex gap-2 justify-end">
                            <v-icon color="info" icon="mdi-pencil" size="large" @click="edit(item.id!)"></v-icon>
                            <v-icon color="error" icon="mdi-delete" size="large" @click="remove(item.id!)"></v-icon>
                        </div>
                    </template>
                </v-data-table>
            </div>
        </div>

        <v-dialog v-model="dialog" max-width="500">
            <v-form ref="form" @submit.prevent="save">
                <v-card :subtitle="`${isEditing ? 'Cambia' : 'Añade'} la información una mesa`"
                    :title="`${isEditing ? 'Edita' : 'Crea'} una mesa`">
                    <template v-slot:text>
                        <v-number-input v-model="formData.capacity" :reverse="false" controlVariant="split"
                            label="Capacidad de la mesa" :rules="rules.capacity" :hideInput="false"
                            :inset="false"></v-number-input>
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
