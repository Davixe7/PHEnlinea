<template>
  <div class="q-pa-lg">
  <div class="container">
    <div class="row">
      <div class="col">

        <q-table
          title="Novedades"
          :rows="novelties"
          :columns="columns"
          :filter="search"
          row-key="title"
          :pagination="{rowsPerPage: 0}">

          <template v-slot:body-cell-created_at="props">
            <q-td :props="props">
              {{ new Date(props.value).toLocaleString() }}
            </q-td>
          </template>

          <template v-slot:body-cell-action="props">
            <q-td :props="props">
              <q-btn flat round @click="novelty = props.row">
                <q-icon class="material-symbols-outlined">remove_red_eye</q-icon>
              </q-btn>
            </q-td>
          </template>
        </q-table>
      </div>

      <div class="col-4 q-px-md" v-if="novelty">
        <q-card class="q-mb-md">
          <q-card-section style="height: 80px; font-size: 1.25rem; display: flex; align-items: center;">
            Novedad detallada
          </q-card-section>
          <q-card-section>
            <div class="flex justify-between">
              <div class="text-medium">
                Descripción
              </div>
              <div>
                {{ novelty.description }}
              </div>
            </div>

            <div>
              <q-separator class="q-my-md"></q-separator>
              <div class="flex justify-between q-mb-sm">
                <q-badge color="grey">Creado el</q-badge>
                <div>{{ new Date(novelty.created_at).toLocaleString() }}</div>
              </div>
            </div>

            <div v-if="novelty" class="flex">
              <q-avatar v-for="(picture, i) in novelty.pictures" :class="q-pe-2">
                <img :src="picture.url">
              </q-avatar>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
</div>
</template>

<style>
  .stat-number {
    font-size: 1.5rem;
  }
</style>
<script setup>
import {ref} from 'vue'
const props   = defineProps(['novelties'])
const search  = ref('')
const novelty = ref(null)
const columns = [{
    align: 'left',
    name: 'id',
    label: 'Código',
    field: 'id',
    sortable: true
  },
  {
    align: 'left',
    name: 'created_at',
    label: 'Creado',
    field: 'created_at',
    sortable: true
  },
  {
    align: 'left',
    name: 'excerpt',
    label: 'Extracto',
    field: 'excerpt',
    sortable: true
  },
  {
    align: 'right',
    name: 'action',
    label: 'Acciones'
  },
]
</script>
@endsection