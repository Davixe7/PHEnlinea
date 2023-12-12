<template>
  <q-table
    hide-bottom
    title="Porterias"
    :filter="search"
    :rows="porterias"
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
        <q-btn flat round icon="sym_o_delete" @click="deletePorteria(props.row)"/>
      </q-td>
    </template>
  </q-table>

  <q-dialog v-model="dialog">
    <q-card style="min-width: 420px;">
      <q-card-section>
        Información del Administrador
      </q-card-section>
      <q-card-section class="q-gutter-y-md">
        <q-select
          hide-bottom-space
          outlined
          stack-label
          :error="Boolean(form.errors.device_comunity_id)"
          :error-message="form.errors.device_comunity_id ? form.errors.device_comunity_id : ''"
          label="Administrador"
          v-model="form.admin_id"
          :options="admins"
          option-label="name"
          option-value="id"
          emit-value
          map-options>
      </q-select>

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
          :error="Boolean(form.errors.email)"
          :error-message="form.errors.email ? form.errors.email : ''"
          label="Email"
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
      @click="()=>{form.defaults({...defaultPorteria}); form.reset(); dialog=true}"
      fab
      icon="add"
      color="primary"
    >
    </q-btn>
  </q-page-sticky>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';
import { ref } from 'vue';

  const props = defineProps(['admins', 'porterias', 'errors', 'user']);
  const columns = ref([
    {name: 'admin', field: row => row.admin.name, label: 'Administrador', align: 'left'},
    {name: 'name', field: 'name', label: 'Nombre', align: 'left'},
    {name: 'email', field: 'email', label: 'Email', align: 'left'},
    {name: 'actions', align: 'right'},
  ])
  const $q     = useQuasar()
  const search = ref('')
  const dialog = ref(false)
  const defaultPorteria = {
    name: '',
    email: '',
    admin_id: '',
    password: '',
  }
  const form   = useForm({...defaultPorteria})

  function save(){
    if( form.id ){
      form.put(`/admin/porterias/${form.id}`, {
        onSuccess: () => {
          dialog.value = false
          form.reset()
          $q.notify('Actualizado con éxito')
        }
      })
      return
    }
    form.post(`/admin/porterias`, {
      onSuccess: () => {
        dialog.value = false
        form.reset()
        $q.notify('Registrado con éxito')
      }
    })
  }

  function deletePorteria(porteria){
    if( !window.confirm('Seguro de eliminar la porteria?') ) return
    form.id = porteria.id
    form.delete(`/admin/porterias/${form.id}`, {
      onSuccess: ()=>{
        $q.notify('Eliminado con éxito')
      }
    })
  }
</script>