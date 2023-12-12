<template>
  <q-table
    style="max-width: 800px;"
    class="q-mx-auto"
    flat
    bordered
    title="Estado de cuenta"
    :rows="facturas"
    :columns="columns"
    separator="cell">
    <template v-slot:top-right class="flex items-center">
      <q-input
        outlined
        stack-label
        label="Desde"
        v-model="form.from"
        mask="date"
        class="q-mr-md"
        :hide-bottom-space="true"
        :rules="['date']"
        :error="Boolean(form.errors.from)"
        :error-message="form.errors.from ? form.errors.from : ''">
        <template v-slot:append>
          <q-icon name="event" class="cursor-pointer">
            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
              <q-date v-model="form.from">
                <div class="row items-center justify-end">
                  <q-btn v-close-popup label="Close" color="primary" flat />
                </div>
              </q-date>
            </q-popup-proxy>
          </q-icon>
        </template>
      </q-input>

        <q-input
          outlined
          stack-label
          label="Hasta"
          v-model="form.to"
          mask="date"
          class="q-mr-md"
          :hide-bottom-space="true"
          :rules="['date']"
          :error="Boolean(form.errors.to)"
          :error-message="form.errors.to ? form.errors.to : ''">
          <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                <q-date v-model="form.to">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

        <q-btn flat @click="fetchBalance">Actualizar</q-btn>
        <q-btn class="text-red" flat @click="downloadPDF">Descargar PDF</q-btn>
    </template>
    <template v-slot:body="props">
      <q-tr
        :props="props"
        :class="{'text-red': props.row.extension_id ? false : true}">
        <q-td key="id" :props="props">
          {{ props.row.extension_id ? props.row.extension_id : 'PAGO' }}
        </q-td>
        <q-td key="client" :props="props">
          {{ extension.owner_name }}
        </q-td>
        <q-td key="date" :props="props">
          {{ props.row.created_at }}
        </q-td>
        <q-td key="month" :props="props">
          {{ props.row.mes }}
        </q-td>
        <q-td key="total" :props="props">
          {{ props.row.extension_id ? '' : '-' }}
          $ {{ props.row.total }}
        </q-td>
      </q-tr>
    </template>
    <q-tr>
      <q-td colspan="3"></q-td>
      <q-td>Total</q-td>
      <q-td>{{ total }}</q-td>
    </q-tr>
  </q-table>
</template>

<script setup>
import Layout from './../ExtensionLayout.vue'
import { useForm } from '@inertiajs/vue3';
import {useDateFormat} from '@vueuse/core'
import {computed} from 'vue'

const props = defineProps([
  'from',
  'to',
  'extension',
  'rows',
  'total',
  'errors',
  'user'
])

defineOptions({layout: Layout})

const form = useForm({
  from: props.from,
  to: props.to
})

function fetchBalance(){
  form.transform((data)=>({
    from: data.from.replaceAll('/', '-'),
    to: data.to.replaceAll('/', '-')
  }))
  form.get(`/extensions/${props.extension.id}/account-balance`)
}

function downloadPDF(){
  window.location.href = `/descargar-estado-cuenta/${props.extension.id}`
}

const columns = [
{name: 'id', field: row => row.extension_id ? row.extension_id : 'PAGO', label: 'No factura', align: 'left'},
{name: 'client', field: row => props.extension.owner_name, label: 'Cliente', align: 'left'},
{name: 'date', field: 'created_at', label: 'Fecha', align: 'left', sortable: true},
{name: 'month', field: 'mes', label: 'Mes', align: 'left'},
{name: 'total', field: 'total', label: 'Monto', align: 'right'},
]

const facturas = computed(()=>{
  return props.rows.map(row => {
    let mes = useDateFormat(new Date(row.created_at), 'MMMM').value;
    return {...row, mes}
  })
})
</script>