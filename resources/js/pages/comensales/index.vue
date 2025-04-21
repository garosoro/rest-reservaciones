<script lang="ts" setup>
import { ref, shallowRef } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue';
import { Diner } from '@/types/diner'
import axios from 'axios';

const dialog = shallowRef(false)
const isEditing = shallowRef(false)

//Registro por defecto
const formData = useForm<Diner>({
    id: null, // Added id property
    name: '',
    email: '',
    telephone: '',
    address: ''
})

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
        (value: string) => /^\d{9}$/.test(value) || 'El teléfono debe tener 9 dígitos.',
    ],
    address: [
        (value: string) => !!value || 'La dirección es obligatoria.',
        (value: string) => value.length >= 5 || 'La dirección debe tener al menos 5 caracteres.',
    ],
};

//props
const props = defineProps<{
    diners: {
        data: Array<Diner>;
    };
}>();

// Breadcrumbs
const breadcrumbs = [
    { title: 'Comensales', href: '/diners' },
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
        .get(route('diners.edit', id))
        .then((response) => {
            const diner = response.data.diner;
            console.log(diner)
            Object.assign(formData, diner)
            console.log('Diner data loaded successfully:', formData);
        })
        .catch((error) => {
            console.error('Error fetching diner data:', error);
        });
    console.log(formData)
}
//funcion para eliminar el comensal
function remove(id: number) {
    console.log("Remove" + id)
    if (confirm('¿Estás seguro de que deseas eliminar este comensal?')) {
        formData.delete(route('diners.destroy', id), {
            preserveScroll: true,
            onSuccess: () => {
                console.log('Comensal eliminado con éxito');
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
                formData.put(route('diners.update', formData.id), {
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
                formData.post(route('diners.store'), {
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
                            <v-icon color="info" icon="mdi-pencil" size="large" @click="edit(item.id!)"></v-icon>
                            <v-icon color="error" icon="mdi-delete" size="large" @click="remove(item.id!)"></v-icon>
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
                        <input type="hidden" name="id" v-model="formData.id">
                        <v-text-field v-model="formData.name" :rules="rules.name" label="Nombre"></v-text-field>
                        <v-text-field v-model="formData.email" :rules="rules.email"
                            label="Correo Electrónico"></v-text-field>
                        <v-text-field v-model="formData.telephone" :rules="rules.telephone"
                            label="Teléfono"></v-text-field>
                        <v-text-field v-model="formData.address" :rules="rules.address"
                            label="Direccion"></v-text-field>
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
