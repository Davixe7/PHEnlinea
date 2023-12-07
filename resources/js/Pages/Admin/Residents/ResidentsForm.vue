<template>
  <q-card>
      <q-card-section>
        <div class="text-h6 text-weight-regular">
          Información del residente
        </div>
      </q-card-section>
      <q-card-section class="q-gutter-y-md">
        <div class="q-gutter-x-sm">
        <q-checkbox v-model="form.is_owner" label="Propietario" color="primary" />
        <q-checkbox v-model="form.is_resident" label="Residente" color="primary" />
        <q-checkbox v-model="form.is_authorized" label="Autorizado" color="primary" />
      </div>

        <Camera
          v-if="camera"
          @cameraClosed="camera = false"
          @pictureTaken="updatePicture">
        </Camera>

        <q-input
        hide-bottom-space
        outlined
        stack-label
        :error="Boolean(form.errors.name)"
        :error-message="form.errors.name ? form.errors.name : ''"
        label="Nombre"
        v-model="form.name"/>

        <q-input
        hide-bottom-space
        outlined
        stack-label
        :error="Boolean(form.errors.age)"
        :error-message="form.errors.age ? form.errors.age : ''"
        label="Edad"
        v-model="form.age"/>

        <q-input
        hide-bottom-space
        outlined
        stack-label
        :error="Boolean(form.errors.dni)"
        :error-message="form.errors.dni ? form.errors.dni : ''"
        label="Cédula"
        v-model="form.dni"/>

        <q-input
        hide-bottom-space
        outlined
        stack-label
        :error="Boolean(form.errors.card)"
        :error-message="form.errors.card ? form.errors.card : ''"
        label="Tarjeta"
        v-model="form.card"/>

        <div class="flex items-center">
          <q-btn outline icon="sym_o_camera" @click="camera=true" class="q-mr-md"></q-btn>
          <q-file
            outlined
            stack-label
            label="Cargar archivo"
            ref="fileInput"
            @input="event => updatePicture(event.target.files[0])">
          </q-file>
          <q-btn color="primary" :loading="form.processing" @click="store" class="q-ml-auto">
            {{ form.id ? 'Actualizar' : 'Registrar' }} residente
          </q-btn>
        </div>
      </q-card-section>
    </q-card>
</template>
  
<script setup>
import Layout from './../ExtensionLayout.vue'
defineOptions({ layout: Layout })
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue'
import Camera from './../../../Admin/components/Camera.vue'

const props = defineProps(['errors', 'resident', 'extension'])
const emit  = defineEmits('reset', 'residentUpdated', 'residentStored')

const form = useForm(props.resident ? {extension_id: props.extension.id, ...props.resident, picture: null, '_method':'PUT'}:{
  errors: {},
  extension_id: props.extension.id,
  name: '',
  age: '',
  card: '',
  dni: '',
  is_owner: false,
  is_resident: false,
  is_authorized: false,
  picture: null
})

const camera      = ref(false)
const picture     = ref(null)
const fileInput   = ref(null)

function updatePicture(takenPicture){
  camera.value  = false
  fileInput.value.nativeEl.value = ''
  picture.value = takenPicture
}

// const typeChecked = computed(() => resident.value.is_resident || resident.value.is_owner || resident.value.is_authorized)

function store() {
  form.picture = picture.value

  if( !form.id ){
    form.post(`/residents`)
    return
  }
  form.post('/residents/' + form.id)
}
</script>

