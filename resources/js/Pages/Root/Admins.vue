<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';

const $q = useQuasar();

const props = defineProps(['errors', 'user', 'admins', 'admin'])

const dialog  = ref(false)
const defaultAdmin = {
  id: null,
  device_community_id: "",
  device_building_id: "",
  device_serial_number: "",
  device_visits_lifespan: "",
  name: "",
  nit: "",
  address: "",
  contact_email: "",
  phone: "",
  phone_2: "",
  email: "",
  password: "",
}
const form = useForm({...defaultAdmin})

const search  = ref('')
const columns = ref([
  {name: 'id', field: 'id', label: 'ID', align: 'left'},
  {name: 'name', field: 'name', label: 'Nombre', align: 'left'},
  {name: 'nit', field: 'nit', label: 'NIT', align: 'left'},
  {name: 'phone', field: 'phone', label: 'Teléfono', align: 'left'},
  {name: 'phone_2', field: 'phone_2', label: 'Teléfono 2', align: 'left'},
  {name: 'email', field: 'email', label: 'Email', align: 'left'},
  {name: 'actions', label: 'Opciones', align: 'right'},
])

function save(){
  if( form.id ){
    form.put(`/admin/admins/${form.id}`, {
      onSuccess: () => {
        dialog.value = false
        form.reset()
        $q.notify('Actualizado con éxito')
      }
    })
    return
  }
  form.post(`/admin/admins`, {
      onSuccess: () => {
        dialog.value = false
        form.reset()
        $q.notify('Registrado con éxito')
      }
    })
}

function deleteAdmin(admin){
  if( !window.confirm('Confirma eliminar al administrador?') ) return
  form.id = admin.id
  form.delete(`/admin/admins/${form.id}`, {
    onSuccess: ()=>{
      $q.notify('Eliminado exitosamente')
    }
  })
}
</script>

<template>
  <q-table
    hide-bottom
    title="Administradores"
    :filter="search"
    :rows="admins"
    :columns="columns">
    <template v-slot:top-right>
      <q-input borderless dense debounce="300" v-model="search" placeholder="Buscar...">
        <template v-slot:append>
          <q-icon name="search" />
        </template>
      </q-input>
    </template>
    <template v-slot:body-cell-actions="props">
      <q-td class="text-right">
        <q-btn flat round icon="sym_o_edit" @click="()=>{form.defaults({...props.row}); form.reset(); dialog = true}"/>
        <q-btn flat round icon="sym_o_delete" @click="deleteAdmin(props.row)"/>
      </q-td>
    </template>
  </q-table>

  <q-dialog v-model="dialog">
    <q-card style="min-width: 420px;">
      <q-card-section>
        Información del Administrador
      </q-card-section>
      <q-card-section class="q-gutter-y-md">
        <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.device_comunity_id)"
          :error-message="form.errors.device_comunity_id ? form.errors.device_comunity_id : ''"
          label="Zhyaf ID de Comunidad"
          v-model="form.device_comunity_id"/>

        <q-input
            hide-bottom-space
            outlined
            stack-label
            :error="Boolean(form.errors.device_building_id)"
            :error-message="form.errors.device_building_id ? form.errors.device_building_id : ''"
            label="Zhyaf ID de Edificio"
            v-model="form.device_building_id"/>

          <q-input
            hide-bottom-space
            outlined
            stack-label
            :error="Boolean(form.errors.device_serial_number)"
            :error-message="form.errors.device_serial_number ? form.errors.device_serial_number : ''"
            label="Zhyaf Serial de Dispositivo"
            v-model="form.device_serial_number"/>

          <q-input
            hide-bottom-space
            outlined
            stack-label
            :error="Boolean(form.errors.visits_lifespan)"
            :error-message="form.errors.visits_lifespan ? form.errors.visits_lifespan : ''"
            label="Plazo de validez de las visitas"
            v-model="form.visits_lifespan"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.name)"
          :error-message="form.errors.name ? form.errors.name : ''"
          label="Nombre"
          v-model="form.name"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.nit)"
          :error-message="form.errors.nit ? form.errors.nit : ''"
          label="NIT"
          v-model="form.nit"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.contact_email)"
          :error-message="form.errors.contact_email ? form.errors.contact_email : ''"
          label="Email"
          v-model="form.contact_email"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.address)"
          :error-message="form.errors.address ? form.errors.address : ''"
          label="Dirección"
          v-model="form.address"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.phone)"
          :error-message="form.errors.phone ? form.errors.phone : ''"
          label="Teléfono"
          v-model="form.phone"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.phone_2)"
          :error-message="form.errors.phone_2 ? form.errors.phone_2 : ''"
          label="Teléfono 2"
          v-model="form.phone_2"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.email)"
          :error-message="form.errors.email ? form.errors.email : ''"
          label="Usuario"
          v-model="form.email"/>

          <q-input
          hide-bottom-space
          outlined
          stack-label
          type="password"
          :error="Boolean(form.errors.password)"
          :error-message="form.errors.password ? form.errors.password : ''"
          label="Contraseña"
          v-model="form.password"/>
      </q-card-section>
      <q-card-actions class="justify-end">
        <q-btn
            :loading="form.processing"
            flat
            @click="save">
            {{ form.id ? 'Actualizar' : 'Guardar' }}
          </q-btn>
      </q-card-actions>
    </q-card>
  </q-dialog>

  <q-page-sticky position="bottom-right" :offset="[18, 18]">
    <q-btn
      @click="()=>{form.defaults({...defaultAdmin}); form.reset(); dialog=true}"
      fab
      icon="add"
      color="primary"
    >
    </q-btn>
  </q-page-sticky>
</template>