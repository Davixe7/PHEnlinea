<template>
  <q-card class="q-mb-md">
    <q-card-section class="row q-col-gutter-x-md items-center">
      <div class="col">
        <q-select
          outlined
          stack-label
          label="Mes"
          v-model="form.month"
          :options="monthsName">
        </q-select>
      </div>
      <div class="col">
        <q-select
          outlined
          stack-label
          label="Año"
          v-model="form.year"
          :options="['2021', '2022', '2023']">
        </q-select>
      </div>
      <div class="col">
        <q-btn
          @click="fetchInvoices"
          :loading="form.processing"
          color="primary"
          style="width: 100%;">
          Consultar
        </q-btn>
      </div>
    </q-card-section>
  </q-card>

  <q-table
    title="Facturas"
    :filter="search"
    :rows="invoices"
    :columns="columns">
  </q-table>

  <q-dialog v-model="dialog">
    <q-card style="min-width: 420px;">
      <q-card-section>
        Información del Administrador
      </q-card-section>
      <q-card-section class="q-gutter-y-md">
        <q-file
          outlined
          stack-label
          label="Archivo XLS"
          v-model="form.file"/>
        
        <q-select
          outlined
          stack-label
          label="Mes"
          :options="monthsName"
          v-model="form.month">
        </q-select>

        <q-select
          outlined
          stack-label
          label="Año"
          :options="['2020', '2021', '2022', '2023']"
          v-model="form.year">
        </q-select>
      </q-card-section>
      <q-card-actions>
        <q-btn
            style="width: 100%;"
            :loading="form.processing"
            color="primary"
            @click="uploadInvoices">
            Cargar facturas
          </q-btn>
      </q-card-actions>
    </q-card>
  </q-dialog>

  <q-page-sticky position="bottom-right" :offset="[18, 18]">
    <q-btn
      fab
      @click="dialog=true"
      icon="add"
      color="primary"
    />
  </q-page-sticky>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3';

const props = defineProps([
  'errors',
  'user',
  'monthsName',
  'month',
  'year',
  'invoices',
])
const form  = useForm({month: props.month, year: props.year, file: null})

const columns = ref([
  {name: 'id', field: 'id', label: 'ID', align: 'left'},
  {name: 'admin-nit', field: row => row.admin.nit, label: 'NIT', align: 'left'},
  {name: 'admin-name', field: row => row.admin.name, label: 'Admin', align: 'left'},
  {name: 'status', field: 'status', label: 'Status', align: 'left'},
  {name: 'total', field: 'total', label: 'Total', align: 'left'},
  {name: 'paid_at', field: 'paid_at', label: 'Pagago el', align: 'right'},
])

const search          = ref('')
const rows            = ref()
const dialog          = ref(false)
const attachmentInput = ref(null)
const month           = ref('')
const year            = ref('')

function updateInvoice(invoice) {
  if (!window.confirm('seguro que desea actualizar el estado de la factura?')) return
  axios.post(`/admin/invoices/${invoice.id}`, { status: invoice.status, '_method': 'PUT' })
  .then(response => invoices.value.splice(invoices.value.indexOf(invoice), 1, response.data.data))
}

function fetchInvoices() {
  form
  .transform(data=>({
    ...data,
    month: props.monthsName.indexOf(data.month) + 1
  }))
  .get('/admin/invoices/upload')
}

function uploadInvoices(){
  form
  .transform(data=>({
    ...data,
    month: props.monthsName.indexOf(data.month) + 1
  }))
  .post('/admin/invoices/import')
}
</script>