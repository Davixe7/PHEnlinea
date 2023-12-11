<template>
  <q-card class="bg-light-green-2 text-green-9 q-mb-md">
    <q-card-section class="flex items-center">

      <template v-if="form.progress">
        <q-linear-progress stripe rounded size="20px" color="primary" :value="`${form.progress.percentage}%`"></q-linear-progress>
      </template>

      <template v-else-if="form.recentlySuccessful">
        ¡Archivos subidos exitosamente!
      </template>

      <template v-else>

        <span v-if="!form.file">
          Cargue un archivo excel y actualice la información de los pagos
        </span>
        <span v-else>
          {{ form.file.name }}
        </span>

        <q-file
          ref="fileInput"
          accept=".xls,.xlsx"
          v-model="form.file"
          v-show="false">
        </q-file>

        <q-btn v-if="!form.file" class="text-white bg-green q-ml-auto" @click="fileInput.pickFiles()">Cargar archivo</q-btn>
        <q-btn v-if="form.file" class="text-white bg-green q-ml-auto"  @click="save">Enviar pagos</q-btn>
      </template>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { useQuasar } from 'quasar';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const $q        = useQuasar()
const props     = defineProps(['batch'])
const form      = useForm({...props.batch, '_method':'PUT', file: null})
const fileInput = ref(null)
function save(){
  form.post(`/resident-invoice-batches/${props.batch.id}`, {
    onSuccess: ()=>{
      $q.notify('Pagos actualizados con éxito')
      form.reset('file')
    }
  })
}
</script>