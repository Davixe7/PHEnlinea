<template>
  <div class="row">
    <div class="col-md-6 q-mt-3 q-mx-auto">

      <q-card class="bg-light-green-2">
        <div class="flex items-center q-pa-md">

          <q-img
            :src="'/img/excel.png'"
            width="40px"
            height="auto"
            class="q-mr-md"
          />

          <p class="q-mb-none">
            <span style="font-weight: 500;">Descargue el formato requerido</span><br>
            Cargue los datos al archivo XLSX
          </p>

          <q-btn
            bordered
            flat
            class="q-ml-auto"
            href="/storage/formato-facturacion-residentes.xls">
            Descargar
          </q-btn>
        </div>
      </q-card>

      <q-card>
        <q-card-section v-if="importedCount">
        <div class="flex column justify-center items-center q-pb-md" style="height: 360px;">
          <q-icon name="task_alt" size="150px" color="primary"></q-icon>
          <div class="q-mb-md">{{importedCount}} Facturas importadas con éxito</div>
          <q-btn
            href="/resident-invoice-batches">
            Ver facturas
          </q-btn>
        </div>
        </q-card-section>

        <q-card-section v-else>
          <q-input outlined stack-label label="Período" v-model="form.periodo" mask="date" :rules="['date']">
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                  <q-date v-model="form.periodo">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>

          <q-input
            outlined
            stack-label
            label="Emisión"
            v-model="form.emision"
            mask="date"
            :rules="['date']"
            :error="Boolean(form.errors.emision)"
            :error-message="form.errors.emision ? form.errors.emision : ''">
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                  <q-date v-model="form.emision">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>

          <q-input
            outlined
            stack-label
            label="Limite"
            v-model="form.limite"
            mask="date"
            :rules="['date']"
            :error="Boolean(form.errors.limite)"
            :error-message="form.errors.limite ? form.errors.limite : ''">
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                  <q-date v-model="form.limite">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>

          <q-file
              outlined
              stack-label
              label="Archivo XLS"
              v-model="form.file"
              :error="Boolean(form.errors.file)"
              :error-message="form.errors.file ? form.errors.file : ''">
              <template v-slot:append>
                <q-icon name="sym_o_attachment"></q-icon>
              </template>
            </q-file>

          <q-input
            outlined
            stack-label
            label="Enlace al pago online"
            v-model="form.link"
            :error="Boolean(form.errors.link)"
            :error-message="form.errors.link ? form.errors.link : ''">
            <template v-slot:append>
              <q-icon name="sym_o_link"></q-icon>
            </template>
          </q-input>

          <q-input
            outlined
            stack-label
            label="Nota"
            v-model="form.note"
            :error="Boolean(form.errors.note)"
            :error-message="form.errors.note ? form.errors.note : ''"/>

          <div class="flex align-center">
            <q-linear-progress
              v-if="form.progress"
              stripe
              rounded
              size="20px"
              :value="`${form.progress.percentage}%`"
              color="green"
              class="q-mt-sm"
            />

            <q-btn
              color="green"
              class="text-white q-mb-md q-ml-auto"
              :loading="form.processing"
              @click="save">
              Guardar
            </q-btn>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props       = defineProps(['batch', 'importedCount'])

const form = useForm(props.batch ? {...props.batch} : {
  periodo: null,
  emision: null,
  limite: null,
  link: null,
  note: '',
  file: null
})

function save(){
  form.post('/resident-invoice-batches/import')
}
</script>