<template>
  <q-table
    class="q-mx-auto"
    style="max-width: 800px"
    title="Lotes de facturación"
    :rows="batches"
    :columns="columns">
    <template v-slot:body-cell-actions="props">
      <q-td class="text-right">
        <q-btn
          flat
          round
          :href="`/resident-invoice-batches/${props.row.id}`"
          icon="sym_o_open_in_new">
      </q-btn>
      </q-td>
    </template>
  </q-table>

  <q-page-sticky position="bottom-right" :offset="[18, 18]">
    <q-btn
      @click="router.visit('/resident-invoice-batches/upload')"
      fab
      icon="add"
      color="primary"
    >
    </q-btn>
  </q-page-sticky>
</template>

<script setup>
  import { router } from '@inertiajs/vue3';
  const props = defineProps(['batches'])
  const columns = ([
    {name: 'id', field: 'id', label: 'ID', align: 'left'},
    {name: 'periodo', field: 'periodo', label: 'Periodo', align: 'left'},
    {name: 'emision', field: 'emision', label: 'Emisión', align: 'left'},
    {name: 'limite',  field: 'limite',  label: 'Limite', align: 'left'},
    {name: 'resident_invoices_count',  field: 'resident_invoices_count',  label: 'Cant.', align: 'left'},
    {name: 'actions',  align: 'right'},
  ])
</script>