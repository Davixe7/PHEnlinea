<template>
  <div class="q-px-md">
    <q-table
    hide-bottom
    title="Residentes"
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

    <template v-slot:body-cell-picture="props">
      <q-td>
        <q-avatar v-if="props.row.picture" size="40px">
          <q-img :src="props.row.picture"></q-img>
        </q-avatar>
      </q-td>
    </template>

    <template v-slot:body-cell-is_resident="props">
      <q-td>
        <q-icon v-if="props.row.is_resident" name="sym_o_done_all"></q-icon>
      </q-td>
    </template>

      <template v-slot:body-cell-is_owner="props">
      <q-td>
        <q-icon v-if="props.row.is_owner" name="sym_o_done_all"></q-icon>
      </q-td>
    </template>

      <template v-slot:body-cell-is_authorized="props">
      <q-td>
        <q-icon v-if="props.row.is_authorized" name="sym_o_done_all"></q-icon>
      </q-td>
    </template>

    <template v-slot:body-cell-actions="props">
      <q-td>
        <div class="flex justify-end">
          <q-btn @click="router.visit(`/extensions/${extension.id}/residents/${props.row.id}/edit`)" round flat icon="sym_o_edit"></q-btn>
          <q-btn @click="destroy(props.row.id)" round flat icon="sym_o_delete"></q-btn>
        </div>
      </q-td>
    </template>
  </q-table>
  </div>
  
  <q-page-sticky position="bottom-right" :offset="[18, 18]">
    <q-btn
      @click="router.visit(`/extensions/${props.extension.id}/residents/create`)"
      fab
      icon="add"
      color="primary"
    >
    </q-btn>
  </q-page-sticky>
</template>
<script setup>
import Layout from './../ExtensionLayout.vue'
defineOptions({ layout: Layout })
import { router, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { useQuasar } from 'quasar';

const $q = useQuasar()
const form = useForm({})
const props = defineProps(['extension', 'residents'])
const search = ref('')
const rows = computed(()=>[...props.residents])
const columns = [
  {name: 'picture', label: 'Foto', align: 'left'},
  {name: 'name', field: 'name', label: 'Apto', align: 'left'},
  {name: 'age',  field: 'age', label: 'Edad', align: 'left'},
  {name: 'dni', field: 'dni', label: 'Cédula', align: 'left'},
  {name: 'card',  field: 'card', label: 'Tarjeta', align: 'left'},
  {name: 'is_resident', label: 'R', align: 'left'},
  {name: 'is_owner',  label: 'P', align: 'left'},
  {name: 'is_authorized',  label: 'A', align: 'left'},
  {name: 'disability',  label: 'D', align: 'left'},
  {name: 'actions',  label: '', align: 'right'},
]
function destroy(id){
  if( !window.confirm('Eliminar al residente?') ) return
  form.delete(`/residents/${id}`, {onSuccess:()=>$q.notify('Eliminado con éxito')})
}
</script>