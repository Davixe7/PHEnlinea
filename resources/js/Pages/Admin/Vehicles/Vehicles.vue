<template>
  <div class="row">
    <!-- 'directions_car' : 'two_wheeler' -->
    <div class="col-md-4 q-px-md">
      <q-card>
        <q-card-section>
          <div class="text-h6 text-weight-regular">
            Vehículo
          </div>
        </q-card-section>
        <q-card-section class="q-gutter-y-md">
          <q-input
            hide-bottom-space
            outlined
            stack-label
            label="Placa"
            v-model="form.plate"
            :error="Boolean(form.errors.plate)"
            :error-message="form.errors?.plate">
          </q-input>
          <q-select
            hide-bottom-space
            outlined
            stack-label
            label="Tipo"
            emit-value
            map-options
            v-model="form.type"
            :error="Boolean(form.errors.type)"
            :error-message="form.errors?.type"
            :options="[{label: 'Carro', value:'car'}, {label: 'Moto', value:'bike'}]">
          </q-select>
          <q-select
            hide-bottom-space
            outlined
            stack-label
            label="Residente"
            emit-value
            option-label="name"
            option-value="id"
            map-options
            v-model="form.resident_id"
            :error="Boolean(form.errors.resident_id)"
            :error-message="form.errors?.resident_id"
            :options="residents">
          </q-select>
          <q-input
            hide-bottom-space
            outlined
            stack-label
            label="Tag"
            v-model="form.tag"
            :error="Boolean(form.errors.tag)"
            :error-message="form.errors?.tag">
          </q-input>
          <q-btn
            flat
            color="primary"
            :loading="form.processing"
            @click="store()">
            {{ form.id ? 'Actualizar' : 'Registrar' }} vehículo
          </q-btn>
        </q-card-section>
      </q-card>
    </div>
    <div class="col-md-8 q-px-md">
      <q-table
        title="Vehículos"
        :filter="search"
        :rows="vehicles"
        :columns="columns"
        >
        <template v-slot:body-cell-actions="props">
          <q-td>
            <q-btn flat @click="setVehicle(props.row)" icon="sym_o_edit"></q-btn>
            <q-btn flat @click="destroy(props.row.id)" icon="sym_o_delete"></q-btn>
          </q-td>
        </template>
      </q-table>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue'
import { useQuasar } from 'quasar';

import Layout from './../ExtensionLayout.vue'
defineOptions({layout: Layout})

const props   = defineProps(['vehicles', 'extension', 'residents'])
const search  = ref('')
const $q      = useQuasar()
const columns = ref([
  {name: 'type', field: row => row.type == 'car' ? 'Carro' : 'Moto', label: 'Tipo', align: 'left'},
  {name: 'plate', field: 'plate', label: 'Placa', align: 'left'},
  {name: 'tag', field: 'tag', label: 'Tag', align: 'left'},
  {name: 'actions', align: 'right'},
])

const defaultVehicle = {
  type: 'car',
  plate: '',
  tag: '',
  id: null,
  resident_id: null,
  extension_id: props.extension.id
}
const form = useForm(props.vehicle ? {...props.vehicle} : {...defaultVehicle})

function setVehicle(vehicle){
  form.defaults({...vehicle})
  form.reset()
}

function clearForm(){
  form.defaults({...defaultVehicle})
  form.reset()
}

function store() {
  if( !form.id ){
    form.post(`/extensions/${props.extension.id}/vehicles`, {
      onSuccess:()=>{
        $q.notify('Registrado con éxito')
        clearForm()
      }
    })
    return
  }
  form.put(`/extensions/${props.extension.id}/vehicles/${form.id}`, {
    onSuccess:()=>{
      $q.notify('Actualizado con éxito')
      clearForm()
    }
  })
}

function destroy(index) {
  if (!window.confirm('Confirma la eliminación del vehículo?')) return
  form.delete(`/vehicles/${index}`,{
    onSuccess:()=>$q.notify('Actualizado con éxito')
  })
}
</script>