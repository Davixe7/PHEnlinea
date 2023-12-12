<template>
  <q-card class="q-mb-md">
    <q-card-section>
      <div class="row">
        <div class="col-6">
          <div class="row">
            <div class="col">
              <div class="stat-number">{{ petitions.length }}</div>
              <div>Total</div>
            </div>
            <div class="col">
              <div class="stat-number">{{ petitions.filter(f => f.read_at == null).length }}</div>
              <div>Sin leer</div>
            </div>
            <div class="col">
              <div class="stat-number">{{ petitions.filter(f => f.replied_at == null).length }}</div>
              <div>Sin responder</div>
            </div>
          </div>
        </div>
        <div class="col-6 flex justify-end align-items-center">
          <q-btn flat icon="sym_o_qr_code_2" label="Descargar QR">
            <q-menu>
              <q-list style="min-width: 100px">
                <q-item clickable v-close-popup href="pqrs/qr/?date={{ now() }}">
                  <q-item-section>Formato simple</q-item-section>
                </q-item>
                <q-item clickable v-close-popup href="pqrs/qr/?date={{ now() }}&type=template">
                  <q-item-section>Con plantilla informativa</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </q-btn>
        </div>
      </div>
    </q-card-section>
  </q-card>

  <div class="row">
    <div class="col">
      <q-table title="PQRS" :rows="petitions" :columns="columns" :filter="search" row-key="name"
        :pagination="{ rowsPerPage: 0 }">

        <template v-slot:top-right>
          <q-input debounce="300" v-model="search" placeholder="Buscar"></q-input>

          <div class="q-mr-md">
            Filtrar por:
          </div>
          <q-select :options="filters" v-model="filter"></q-select>
        </template>
        <template v-slot:body-cell-created_at="props">
          <q-td :props="props">
            {{ new Date(props.value).toLocaleString() }}
          </q-td>
        </template>
        <template v-slot:body-cell-read_at="props">
          <q-td :props="props">
            {{ props.value ? new Date(props.value).toLocaleString() : 'pendiente' }}
          </q-td>
        </template>
        <template v-slot:body-cell-replied_at="props">
          <q-td :props="props">
            {{ props.value ? new Date(props.value).toLocaleString() : 'pendiente' }}
          </q-td>
        </template>
        <template v-slot:body-cell-action="props">
          <q-td :props="props">
            <q-btn flat round @click="markAsRead(props.row)">
              <q-icon class="material-symbols-outlined">remove_red_eye</q-icon>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </div>

    <div class="col-4 q-px-md" v-if="pqrs">
      <q-card class="q-mb-md">
        <q-card-section style="height: 80px; font-size: 1.25rem; display: flex; align-items: center;">
          Detalles de PQRS
        </q-card-section>
        <q-card-section>
          <div class="flex justify-between">
            <div class="text-medium">
              Nombre
            </div>
            <div>
              {{ pqrs.name }}
            </div>
          </div>
          <div class="flex justify-between">
            <div class="text-medium">
              Apto
            </div>
            <div>
              {{ pqrs.extension_name }}
            </div>
          </div>
          <div class="flex justify-between">
            <div class="text-medium">
              Teléfono
            </div>
            <div>
              {{ pqrs.phone }}
            </div>
          </div>
          <div class="flex justify-between">
            <div class="text-medium">
              Descripción
            </div>
            <div>
              {{ pqrs.description }}
            </div>
          </div>
          <div>
            <q-separator class="q-my-md"></q-separator>
            <div class="flex justify-between q-mb-sm">
              <q-badge color="grey">Creado el</q-badge>
              <div>{{ new Date(pqrs.created_at).toLocaleString() }}</div>
            </div>
            <div class="flex justify-between q-mb-sm">
              <q-badge color="blue">Leído</q-badge>
              <div>{{ pqrs.read_at ? new Date(pqrs.read_at).toLocaleString() : 'pendiente' }}</div>
            </div>
            <div class="flex justify-between q-mb-sm">
              <q-badge color="green">Respuesta enviada</q-badge>
              <div>{{ pqrs.replied_at ? new Date(pqrs.replied_at).toLocaleString() : 'pendiente' }}</div>
            </div>
          </div>

          <div v-if="pqrs" class="flex">
            <q-avatar v-for="(attachment, i) in pqrs.attachments" :class="q - pe - 2" @click="showImg(i)">
              <img :src="attachment">
            </q-avatar>
          </div>

          <q-input autogrow type="textarea" v-model="pqrs.answer" label="Respuesta"></q-input>
          <div class="flex justify-end">
            <q-btn :disabled="!pqrs" @click="reply" flat icon="sym_o_send">
            </q-btn>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue'

const props = defineProps(['petitions', 'petition', 'errors'])
const pqrs  = ref(null)
const form  = useForm({ id: null })
const search = ref('')
const filters = ref([{
  value: 'read_at',
  label: 'No leídos'
},
{
  value: 'replied_at',
  label: 'Sin responder'
},
{
  value: 'all',
  label: 'Todos'
}])
const filter = ref(filters.value[2])
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
  name: 'read_at',
  label: 'Leído',
  field: 'read_at',
  sortable: true
},
{
  align: 'left',
  name: 'replied_at',
  label: 'Respuesta enviada',
  field: 'replied_at',
  sortable: true
},
{
  align: 'left',
  name: 'name',
  label: 'Nombres',
  field: 'name',
  sortable: true
},
{
  align: 'left',
  name: 'phone',
  label: 'Teléfono',
  field: 'phone',
  sortable: true
},
{
  align: 'right',
  name: 'action',
  label: 'Acciones'
},
]

function markAsRead(item) {
  pqrs.value = item
  if (pqrs.value.read_at != null) return
  let index = props.petitions.indexOf(item)
  form.put(`pqrs/${pqrs.value.id}/markAsRead`, {
    onSuccess: ()=>{
      pqrs.value = props.petitions[index]
    }
  })
}

function reply() {
  let newForm = useForm({...pqrs.value})
  let index = props.petitions.indexOf(pqrs.value)
  newForm.put(`pqrs/${pqrs.value.id}`, {
    onSuccess: ()=>{
      pqrs.value = props.petitions[index]
    }
  })
}
</script>