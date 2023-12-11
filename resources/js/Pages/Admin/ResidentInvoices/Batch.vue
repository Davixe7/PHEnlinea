<template>
  <payment-uploader :batch="batch">
  </payment-uploader>

  <q-table
    :title="`Facturas del lote ${batch.id}`"
    :rows="batch.resident_invoices"
    :columns="columns"
  >
  <template v-slot:body-cell-actions="props">
    <q-td class="text-right">
      <q-btn
        round
        flat
        v-if="payments = props.row.resident_invoice_payments.length ? props.row.resident_invoice_payments : false"
        :href="`/pago/${payments[payments.length-1].id}`"
        icon="sym_o_receipt_long"
        target="_blank">
      </q-btn>

      <q-btn
        round
        flat
        :href="`/descargar-factura/${props.row.id}`"
        icon="sym_o_receipt"
        target="_blank">
      </q-btn>

      <q-btn
        flat
        :href="`/descargar-factura/${props.row.id}`"
        target="_blank">
        Edo. Cta
      </q-btn>
    </q-td>
  </template>
  </q-table>
</template>
<script setup>
import PaymentUploader from './PaymentUploader.vue';
const props = defineProps(['batch'])
const columns = ([
    {name: 'id', field: 'id', label: 'ID', align: 'left'},
    {name: 'apto', field: 'apto', label: 'Apto', align: 'left'},
    {name: 'total', field: 'total', label: 'Total', align: 'left'},
    {name: 'paid',  field: 'paid',  label: 'Pagado', align: 'left'},
    {name: 'pending',  field: 'pending',  label: 'Pendiente.', align: 'left'},
    {name: 'actions',  align: 'right'},
  ])
</script>