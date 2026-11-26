<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Label } from '@components/ui/label';
import { Input } from '@components/ui/input';
import { Send } from '@lucide/vue';
import { Button } from '@components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@components/ui/card';
import SettingController from '@/actions/App/Http/Controllers/SettingController';
``;
import { Switch } from '@components/ui/switch';
import InputError from '@components/InputError.vue';
import { settingsDataI } from '@/Interfaces/SettingInterface';

const page = usePage();

/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    setting: settingsDataI;
    company_type: string[];
}>();

/*
Datos de la ventana
 */

const isSequence = ref(false);
const imagePath = ref();
const save_cost = ref(true);
const sequence = ref(false);

watch(propsW.setting, (newValue) => {
    if (newValue && newValue.uuid) {
        save_cost.value = newValue.save_cost;
        sequence.value = newValue.sequence;
    }
});

/*
Al momento de cargar
//  */
// onMounted(() => {
//   //Verificar si existe correctamente
//   if (page.props.setting) {
//     form.name = page.props.setting.name;
//     form.email = page.props.setting.email;
//     form.phone = page.props.setting.phone ?? '';
//     form.address = page.props.setting.address ?? '';
//     form.website = page.props.setting.website ?? '';
//     form.company_id = page.props.setting.company_id ?? '';
//     form.cost = page.props.setting.save_cost;
//     form.sequence = page.props.setting.sequence;
//     imgName.value = page.props.setting.logo ? page.props.setting.logo : 'logoexample.png';
//
//     isSequence.value = page.props.setting.sequence;
//   }
// });

/*
Al momento de actualizar
 */
// onUpdated(() => {
//   if (page.props.setting && page.props.setting.logo) {
//     //Actualizar la imagen registrad
//     imgName.value = page.props.setting.logo;
//   }
// });
//
// /*
// Datos de la ventana
//  */
// const url = ref(window.origin);
// const imgName = ref('logoexample.png');
//
// /*
// const getFileInfo = (event: FileUploadSelectEvent) => {
//   form.image_path = event.files[0];
// };
</script>

<template>
    <Head title="Ajustes" />
    <Card class="w-full">
        <CardHeader class="space-y-3">
            <CardTitle class="text-2xl font-bold text-center">Configuración</CardTitle>
            <CardDescription> Registro de configuracion </CardDescription>
        </CardHeader>

        <CardContent>
            <Form
                :transform="
                    (data) => ({
                        ...data,
                        save_cost: Boolean(save_cost),
                        sequence: Boolean(sequence),
                    })
                "
                :action="SettingController.store()"
                method="post"
                enctype="multipart/form-data"
                #default="{
                    processing,
                    errors,
                }: {
                    processing: boolean;
                    errors: Record<string, string>;
                }"
                class="grid grid-cols-1 md:grid-cols-2 gap-4"
            >
                <div class="space-y-1.5">
                    <Label for="name">Nombre</Label>
                    <Input
                        :defaultValue="setting?.name"
                        id="name"
                        name="name"
                        placeholder="Nombre de la empresa"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="space-y-1.5">
                    <Label for="company_id">Identificación/RNC</Label>
                    <Input
                        :defaultValue="setting?.company_id ?? ''"
                        id="company_id"
                        name="company_id"
                        placeholder="RNC o identificación"
                    />
                    <InputError :message="errors.company_id" />
                </div>

                <div class="space-y-1.5">
                    <Label for="email">Correo</Label>
                    <Input
                        :defaultValue="setting?.email"
                        id="email"
                        name="email"
                        type="email"
                        placeholder="correo@empresa.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="space-y-1.5">
                    <Label for="phone">Teléfono</Label>
                    <Input
                        :defaultValue="setting?.phone"
                        id="phone"
                        name="phone"
                        placeholder="Teléfono"
                    />
                    <InputError :message="errors.phone" />
                </div>

                <div class="space-y-1.5">
                    <Label for="address">Dirección</Label>
                    <Input
                        :defaultValue="setting?.address"
                        id="address"
                        name="address"
                        placeholder="Dirección"
                    />
                    <InputError :message="errors.address" />
                </div>

                <div class="space-y-1.5">
                    <Label for="website">Pagina Web</Label>
                    <Input
                        :defaultValue="setting?.website"
                        id="website"
                        name="website"
                        placeholder="https://..."
                    />
                    <InputError :message="errors.website" />
                </div>

                <div class="space-y-1.5">
                    <Label for="fiscal_year">Año Fiscal</Label>
                    <Input
                        :defaultValue="setting?.fiscal_year ?? ''"
                        id="fiscal_year"
                        name="fiscal_year"
                        placeholder="2026"
                    />
                    <InputError :message="errors.fiscal_year" />
                </div>

                <div class="space-y-1.5">
                    <Label for="image_path">Logo</Label>
                    <Input id="image_path" name="image_path" type="file" accept="image/*" />
                    <InputError :message="errors.image_path" />
                </div>
                <div class="flex gap-3">
                    <Switch v-model="save_cost" name="save_cost" />
                    <Label for="cost">Proteger costo</Label>
                </div>
                <div class="flex gap-3">
                    <Switch v-model="sequence" name="sequence" />
                    <Label for="cost">Maneja comprobante</Label>
                </div>

                <div class="mt-4 text-right col-span-full">
                    <Button type="submit">
                        <Send class="w-4 h-4 mr-2" />
                        Registrar
                    </Button>
                </div>
            </Form>
        </CardContent>
    </Card>
</template>
