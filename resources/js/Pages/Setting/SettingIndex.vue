<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { useRoute } from 'ziggy-js';
import { onMounted, onUpdated, ref } from 'vue';
import { itemsSettings } from '@/Helpers/SettingHelpers';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';

const route = useRoute();
const page = usePage();

/*
Propiedades de la ventana
 */
const propsW = defineProps<{
  company_type: string[];
}>();

/*
Datos de la ventana
 */

const isSequence = ref(false);
const imagePath = ref();

/*
Datos del formulario
 */
const form = useForm({
  name: '',
  email: '',
  phone: '',
  address: '',
  website: '',
  company_id: '',
  is_branch: false,
  fiscal_year: '',
  image_path: '',
  cost: true,
  sequence: true,
  general: '',
});
/*
Al momento de cargar
 */
onMounted(() => {
  //Verificar si existe correctamente
  if (page.props.setting) {
    form.name = page.props.setting.name;
    form.email = page.props.setting.email;
    form.phone = page.props.setting.phone ?? '';
    form.address = page.props.setting.address ?? '';
    form.website = page.props.setting.website ?? '';
    form.company_id = page.props.setting.company_id ?? '';
    form.cost = page.props.setting.save_cost;
    form.sequence = page.props.setting.sequence;
    imgName.value = page.props.setting.logo ? page.props.setting.logo : 'logoexample.png';

    isSequence.value = page.props.setting.sequence;
  }
});

/*
Al momento de actualizar
 */
onUpdated(() => {
  if (page.props.setting && page.props.setting.logo) {
    //Actualizar la imagen registrad
    imgName.value = page.props.setting.logo;
  }
});

/*
Datos de la ventana
 */
const url = ref(window.origin);
const imgName = ref('logoexample.png');

/*
const getFileInfo = (event: FileUploadSelectEvent) => {
  form.image_path = event.files[0];
};
</script>

<template>
  <Head title="Ajustes" />
  <AppLayout>
    <Card class="w-full">
      <CardHeader class="space-y-3">
        <div>
          <BreadCrumbComponent :itemOptions="itemsSettings" />
        </div>
        <div>
          <CardTitle class="text-2xl font-bold text-center">Configuración</CardTitle>
        </div>
      </CardHeader>

      <CardContent>
        <Form
          :action="route('setting.store')"
          method="post"
          enctype="multipart/form-data"
          #default="{ processing, errors }"
          class="grid grid-cols-1 md:grid-cols-2 gap-4"
        >
          <div class="space-y-1.5">
            <Label for="name">Nombre</Label>
            <Input
              id="name"
              name="name"
              :default-value="setting?.name ?? ''"
              placeholder="Nombre de la empresa"
            />
            <p v-if="errors.name" class="text-sm text-destructive">{{ errors.name }}</p>
          </div>

          <div class="space-y-1.5">
            <Label for="company_id">Identificación/RNC</Label>
            <Input
              id="company_id"
              name="company_id"
              :default-value="setting?.company_id ?? ''"
              placeholder="RNC o identificación"
            />
            <p v-if="errors.company_id" class="text-sm text-destructive">{{ errors.company_id }}</p>
          </div>

          <div class="space-y-1.5">
            <Label for="email">Correo</Label>
            <Input
              id="email"
              name="email"
              type="email"
              :default-value="setting?.email ?? ''"
              placeholder="correo@empresa.com"
            />
            <p v-if="errors.email" class="text-sm text-destructive">{{ errors.email }}</p>
          </div>

          <div class="space-y-1.5">
            <Label for="phone">Teléfono</Label>
            <Input
              id="phone"
              name="phone"
              :default-value="setting?.phone ?? ''"
              placeholder="Teléfono"
            />
            <p v-if="errors.phone" class="text-sm text-destructive">{{ errors.phone }}</p>
          </div>

          <div class="space-y-1.5">
            <Label for="address">Dirección</Label>
            <Input
              id="address"
              name="address"
              :default-value="setting?.address ?? ''"
              placeholder="Dirección"
            />
            <p v-if="errors.address" class="text-sm text-destructive">{{ errors.address }}</p>
          </div>

          <div class="space-y-1.5">
            <Label for="website">Pagina Web</Label>
            <Input
              id="website"
              name="website"
              :default-value="setting?.website ?? ''"
              placeholder="https://..."
            />
            <p v-if="errors.website" class="text-sm text-destructive">{{ errors.website }}</p>
          </div>

          <div class="space-y-1.5">
            <Label for="fiscal_year">Año Fiscal</Label>
            <Input
              id="fiscal_year"
              name="fiscal_year"
              :default-value="setting?.fiscal_year ?? ''"
              placeholder="2026"
            />
            <p v-if="errors.fiscal_year" class="text-sm text-destructive">
              {{ errors.fiscal_year }}
            </p>
          </div>

          <div class="space-y-1.5">
            <Label for="image_path">Logo</Label>
            <Input id="image_path" name="image_path" type="file" accept="image/*" />
            <p v-if="errors.image_path" class="text-sm text-destructive">{{ errors.image_path }}</p>
          </div>

          <div class="flex flex-wrap items-center gap-6 pt-2">
            <input type="hidden" name="cost" :value="costChecked ? 1 : 0" />
            <div class="flex items-center gap-3">
              <Switch id="protect_cost" v-model:checked="costChecked" />
              <Label for="protect_cost">Proteger Costo</Label>
            </div>

            <input type="hidden" name="sequence" :value="sequenceChecked ? 1 : 0" />
            <div class="flex items-center gap-3">
              <Switch id="sequence_ncf" v-model:checked="sequenceChecked" />
              <Label for="sequence_ncf">NCF</Label>
            </div>
          </div>

          <div class="mt-4 text-right col-span-full">
            <Button type="submit" :disabled="processing">
              <Send class="w-4 h-4 mr-2" />
              Registrar
            </Button>
          </div>
        </Form>
      </CardContent>
    </Card>
  </AppLayout>
</template>
