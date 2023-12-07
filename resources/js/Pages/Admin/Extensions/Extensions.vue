<template>
  <q-table
    hide-bottom
    title="Apartamentos"
    :filter="search"
    :rows="rows"
    :columns="columns">
    <template v-slot:top-right>
        <q-input outlined dense debounce="300" v-model="search" placeholder="Buscar">
            <template v-slot:append>
                <q-icon name="search" />
            </template>
        </q-input>
    </template>
    <template v-slot:body-cell-actions="props">
      <q-td>
        <div class="flex justify-end">
          <q-btn @click="router.visit(`/extensions/${props.row.id}/edit`)" round flat icon="sym_o_remove_red_eye"></q-btn>
          <q-btn @click="destroy(props.row.id)" round flat icon="sym_o_delete"></q-btn>
        </div>
      </q-td>
    </template>
  </q-table>

  <q-page-sticky position="bottom-right" :offset="[18, 18]">
    <q-btn
      @click="router.visit('/extensions/create')"
      fab
      icon="add"
      color="primary"
    >
    </q-btn>
  </q-page-sticky>
</template>
<script setup>
import { router, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { useQuasar } from 'quasar';

const $q = useQuasar()
const form = useForm({})
const props = defineProps(['extensions'])
const search = ref('')
const rows = computed(()=>[...props.extensions])
const columns = [
  {name: 'name', field: 'name', label: 'Apto', align: 'left'},
  {name: 'pets_count', field: 'pets_count', label: 'Mascotas', align: 'left'},
  {name: 'vehicles', field: row => row.vehicles ? row.vehicles.length : 0, label: 'Vehículos', align: 'left'},
  {name: 'deposit', field: row => row.deposit ? 'SÍ' : 'NO', label: 'Útil', align: 'left'},
  {name: 'owner_phone', field: 'owner_phone', label: 'Tel. Propietario', align: 'left'},
  {name: 'phone_1', field: 'phone_1', label: 'Cit. 1', align: 'left'},
  {name: 'phone_2', field: 'phone_2', label: 'Cit. 2', align: 'left'},
  {name: 'actions', align: 'right'},
]
function destroy(id){
  if( !window.confirm('Eliminar la extension?') ) return
  form.delete(`/extensions/${id}`, {onSuccess:()=>$q.notify('Eliminado con éxito')})
}
</script>