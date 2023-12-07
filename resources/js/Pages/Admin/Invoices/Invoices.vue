<template>
  <div class="row justify-center">
    <div class="col-12 col-md-7">
      <q-table
        title="Mis facturas"
        :rows="invoices"
        :columns="columns"
        :pagination="{perPage: 0}">
        <template v-slot:body-cell-status="props">
          <q-td>
            <q-badge :color="props.row.status == 'pagado' ? 'green' : 'red'">
              {{ props.row.status }}
            </q-badge>
          </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
          <q-td class="text-right">
            <q-btn
              flat
              color="primary"
              :href="`/invoices/${props.row.id}`">
              DESCARGAR
            </q-btn>
            <q-btn        
              v-if="invoices.status != 'pagado'"
              flat
              color="primary"
              href="https://winred.co/payment/invoice-wallet.jsp?step=1&pId=108">
              PAGAR
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps(['invoices'])
const columns = ref([
  {name: 'date', field: 'date', label: 'Fecha', align: 'left'},
  {name: 'total', field: 'total', label: 'Total', align: 'left'},
  {name: 'status', label: 'Estado', align: 'left'},
  {name: 'paid_at', field: row => row.paid_at ? row.paid_at : 'pendiente', label: 'Fecha de pago', align: 'left'},
  {name: 'actions', label: '', align: 'right'},
])
</script>