<template>
  <q-table
    title="Visitantes"
    :filter="search"
    :rows="visits"
    :columns="columns">
    <template v-slot:body-cell-picture="props">
      <q-td>
        <q-avatar size="40px">
          <q-img v-if="props.row.picture" :src="props.row.picture"></q-img>
        </q-avatar>
      </q-td>
    </template>
  </q-table>
</template>

<script setup>
  import {ref} from 'vue'
  const props   = defineProps(['visits'])
  const search  = ref('')
  const columns = ref([
    {name: 'picture', label: 'Foto', align: 'left'},
    {name: 'name', label: 'Nombre', field: row => row.visitor.name, align: 'left'},
    {name: 'dni', label: 'Cédula', field: row => row.visitor.dni, align: 'left'},
    {name: 'company', label: 'Compañia', field: row => row.visitor.company, align: 'left'},
    {name: 'extension_name', field: row => row.extension_name, label: 'Apto', align: 'left'},
    {name: 'arl_eps', field: 'arl_eps', label: 'ARL - EPS', align: 'left'},
    {name: 'checkin', field: 'checkin', label: 'Checkin', align: 'left'},
    {name: 'checkout', field: 'checkout', label: 'Checkout', align: 'left'},
  ])
</script>