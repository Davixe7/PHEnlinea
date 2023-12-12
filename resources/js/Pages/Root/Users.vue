<script setup>
import { ref } from 'vue';

  const props = defineProps(['users'])
  const search = ref('')
  const columns = ref([
    {name: 'id', field: 'id', label: 'ID', align: 'left'},
    {name: 'name', field: 'name', label: 'Nombre', align: 'left'},
    {name: 'email', field: 'email', label: 'Email', align: 'left'},
    {name: 'actions', label: 'Opciones', align: 'right'},
  ])
</script>

<template>
  <q-table
    hide-bottom
    title="Superusuarios"
    :filter="search"
    :rows="users"
    :columns="columns">
    <template v-slot:body-cell-actions="props">
      <q-td class="text-right">
        <q-btn flat round icon="sym_o_edit"/>
      </q-td>
    </template>
  </q-table>
</template>