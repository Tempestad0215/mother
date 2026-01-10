<script setup lang="ts">
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import {useRoute} from "ziggy-js";
import {
    Card,
    FloatLabel,
    InputText,
    ToggleSwitch,
    FileUpload,
    Button,
    FileUploadSelectEvent, useToast, Breadcrumb
} from "primevue";
import {onMounted, onUpdated, ref} from "vue";
import type {MenuItem} from "primevue/menuitem";
import {itemsSettings} from "@/Helpers/SettingHelpers";


const route = useRoute();
const toast = useToast();
const page = usePage();

/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    company_type: string[]
}>();


/*
Datos de la ventana
 */

const isSequence:Ref<boolean> = ref(false);
const imagePath = ref()


/*
Datos del formulario
 */
const form = useForm({
    name:"",
    email:"",
    phone:"",
    address:"",
    website:"",
    company_id:"",
    is_branch: false,
    fiscal_year: "",
    image_path:"",
    cost: true,
    sequence: true,
    general: ""
});
/*
Al momento de cargar
 */
onMounted(() =>{

    //Verificar si existe correctamente
    if(page.props.setting)
    {
        form.name = page.props.setting.name;
        form.email = page.props.setting.email;
        form.phone = page.props.setting.phone ?? "";
        form.address = page.props.setting.address ?? "" ;
        form.website = page.props.setting.website ?? "";
        form.company_id = page.props.setting.company_id ?? "";
        form.cost = page.props.setting.save_cost;
        form.sequence = page.props.setting.sequence;
        imgName.value = page.props.setting.logo ? page.props.setting.logo: "logoexample.png";

        isSequence.value = page.props.setting.sequence;
    }
});

/*
Al momento de actualizar
 */
onUpdated(() =>{
    if(page.props.setting && page.props.setting.logo)
    {
        //Actualizar la imagen registrad
        imgName.value = page.props.setting.logo;
    }

});





/*
Datos de la ventana
 */
const url = ref(window.origin);
const imgName = ref("logoexample.png")


/*
Funciones
 */
//Enviar los datos
const submit = () => {
    form.post(route('setting.store'),{
        onSuccess:() => {
            toast.add({
                severity: "success",
                summary: "Exito en Registro",
                detail: "Registrado Correctamente",
                life: 3000
            })
        },
        onError: (err) => {
            toast.add({
                severity: "error",
                summary: "Error En Esta Peticion",
                detail: `Hubo un Error. Detalle : ${Object.values(err)[0]}`,
                life: 5000
            })
        }

    })
}
const getFileInfo = (event: FileUploadSelectEvent) =>{
    form.image_path = event.files[0]
}


</script>

<template>
    <Head title="Ajustes" />
    <AppLayout>

        <Card>
            <template #header>
                <div>
                    <Breadcrumb :model="itemsSettings"/>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-center" >Configuración</h3>

                </div>
            </template>
            <template #content>
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-3" >
                    <FloatLabel variant="on">
                        <InputText class="w-full" id="name" v-model="form.name" />
                        <label for="name">Nombre</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText class="w-full" id="company_id" v-model="form.company_id" />
                        <label for="company_id">Identificación/RNC</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText class="w-full" id="email" v-model="form.email" />
                        <label for="email">Correo</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText class="w-full" id="phone" v-model="form.phone" />
                        <label for="phone">Teléfono</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText class="w-full" id="address" v-model="form.address" />
                        <label for="address">Dirección</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText class="w-full" id="website" v-model="form.website" />
                        <label for="website">Pagina Web</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText class="w-full" id="fiscal_year" v-model="form.fiscal_year" />
                        <label for="fiscal_year">Año Fiscal</label>
                    </FloatLabel>
                    <FileUpload  @select="getFileInfo($event)"  ref="imagePath" name="image_path[]"  mode="basic" accept="image/*"  />
                    <div class="flex items-center">
                        <div class="flex items-center space-x-3 mr-5">
                            <ToggleSwitch v-model="form.cost" />
                            <label for="protect_cost">Proteger Costo</label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <ToggleSwitch v-model="form.sequence" />
                            <label for="protect_cost">NCF</label>
                        </div>

                    </div>
                    <div class="mt-5 text-right col-span-full">
                        <Button type="submit" icon="pi pi-send" label="Registrar" />
                    </div>
                </form>
            </template>
        </Card>
    </AppLayout>

</template>

