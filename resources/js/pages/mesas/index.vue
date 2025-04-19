<script lang="ts" setup>
import { ref, shallowRef } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { TableDiner } from '@/types/diner'

const dialog = shallowRef(false)
const isEditing = shallowRef(false)

//registro por defecto y objecto para el comensal
const DEFAULT_RECORD = {
    name: '',
    email: '',
    telephone: '',
    address: '',
}
const record = ref(DEFAULT_RECORD)
//validacion de los campos del formulario
const form = ref(); // Reference to the form

const rules = {
    name: [
        (value: string) => {
            if (value) return true
            return 'You must enter a first name.'
        },
        (value: string) => value.length >= 3 || 'El nombre debe tener al menos 3 caracteres.',
    ],
    email: [
        (value: string) => !!value || 'El correo electrónico es obligatorio.',
        (value: string) => /.+@.+\..+/.test(value) || 'Debe ser un correo electrónico válido.',
    ],
    telephone: [
        (value: string) => !!value || 'El teléfono es obligatorio.',
        (value: string) => /^\d{10}$/.test(value) || 'El teléfono debe tener 10 dígitos.',
    ],
    address: [
        (value: string) => !!value || 'La dirección es obligatoria.',
        (value: string) => value.length >= 5 || 'La dirección debe tener al menos 5 caracteres.',
    ],
};

//props
const props = defineProps<{
    diners: {
        data: Array<TableDiner>;
    };
}>();

// Breadcrumbs
const breadcrumbs = [
    { title: 'Posts', href: '/posts' },
]
//Table Headers
const headers: Array<{
    title: string;
    key: string;
    align?: 'start' | 'end' | 'center';
    sortable?: boolean;
}> = [
        { title: 'Id', key: 'id', align: 'start' },
        { title: 'Nombre', key: 'name', align: 'start' },
        { title: 'Email', key: 'email', align: 'start' },
        { title: 'Acciones', key: 'actions', align: 'end', sortable: false },
    ];

//funcion para abrir el modal de registro
function add() {
    isEditing.value = false
    record.value = DEFAULT_RECORD
    dialog.value = true
}
//funcion de editar el comensal
function edit(id: number) {
    isEditing.value = true
    dialog.value = true
    console.log("Edit" + id)
}
//funcion para eliminar el comensal
function remove(id: number) {
    console.log("Remove" + id)
}
//funcion para cancelar el registro
function cancel() {
    dialog.value = false
    record.value = DEFAULT_RECORD
}
//funcion para guardar el comensal
function save() {
    console.log(form.value)
    form.value.validate().then((success: { valid: boolean }) => {
        if (success.valid) {
            if (isEditing.value) {
                console.log('Updating record:', record.value);
                // Add logic to update the record
            } else {
                console.log('Creating new record:', record.value);
                // Add logic to create a new record
            }
            dialog.value = false
            form.value.reset();
            record.value = DEFAULT_RECORD
        }
    });
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Comensales" />
        <div class="container mx-auto p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Lista de Comensales</h1>
                <v-btn class="me-2" prepend-icon="mdi-plus" rounded="lg" text="Crear Comensal" border
                    @click="add"></v-btn>
            </div>
            <div class="overflow-x-auto">
                <v-data-table :headers="headers" :hide-default-footer="diners.data.length < 11" :items="diners.data">
                    <template #item.actions="{ item }">
                        <div class="flex gap-2 justify-end">
                            <v-icon color="info" icon="mdi-pencil" size="large" @click="edit(item.id)"></v-icon>
                            <v-icon color="error" icon="mdi-delete" size="large" @click="remove(item.id)"></v-icon>
                        </div>
                    </template>
                </v-data-table>
            </div>
        </div>

        <v-dialog v-model="dialog" max-width="500">
            <v-form ref="form" @submit.prevent="save">
                <v-card :subtitle="`${isEditing ? 'Cambia' : 'Añade'} la información un comensal`"
                    :title="`${isEditing ? 'Edita' : 'Crea'} un Comensal`">
                    <template v-slot:text>

                        <v-text-field v-model="record.name" :rules="rules.name" label="Nombre"></v-text-field>
                        <v-text-field v-model="record.email" :rules="rules.email"
                            label="Correo Electrónico"></v-text-field>
                        <v-text-field v-model="record.telephone" :rules="rules.telephone"
                            label="Teléfono"></v-text-field>
                        <v-text-field v-model="record.address" :rules="rules.address" label="Direccion"></v-text-field>
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
