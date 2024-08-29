<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import Swal from "sweetalert2";
import LinkHeader from "@components/LinkHeader.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {onMounted, onUpdated, ref} from "vue";
import {settingsDataI} from "@/Interfaces/Setting";
import {successHttp} from "@/Global/Alert";


/**
 * Propiedades
 *
 */
const props = defineProps<{
    setting: settingsDataI
}>()



/**
 * Al momento de cargar
 *
 */
onMounted(() =>{
    if(props.setting)
    {
        form.name = props.setting.name;
        form.email = props.setting.email;
        form.phone = props.setting.phone;
        form.address = props.setting.address;
        form.website = props.setting.website;
        form.company_id = props.setting.company_id;
        form.tax = props.setting.tax !== null ? props.setting.tax : [];
        imgName.value = props.setting.logo;
    }
});

/**
 * Al momento de actualizar
 */
onUpdated(() =>{
    //Actualizar la imagen registrad
    imgName.value = props.setting.logo;
})




/**
 * Datos del formulario
 */
const form = useForm({
    name:"",
    email:"",
    phone:"",
    address:"",
    website:"",
    company_id:"",
    taxValue:"",
    tax: [],
    is_branch: false,
    fiscal_year: "",
    logo:"",
});


/**
 * Datos de la ventana
 */
const url = ref(window.origin);
const imgName = ref("logoexample.png")


/**
 * Funciones
 */
//Enviar los datos
const submit = () => {
    form.post(route('setting.store'),{
        onSuccess:() => {
            successHttp('Datos registrado correctamente');
        }
    })
}
//Agregar lis impuesto
const addTax = () => {
    //Verificar si existe
    let exists = form.tax.find((el) => el == form.taxValue);

    if (form.taxValue === "")
    {
        form.setError('tax','EL Campo ITBIS No Puede Estar En Blanco ');
    }
    else if (exists)
    {
        //Poner el error
        form.setError('tax','El Campo ITBIS No Se puede Repetir');
        return false;

    }else if(form.tax.length > 5)
    {
        form.setError('tax','Ha Alcanzado la Cantidad Maxima de Impuesto');
    }
    else{
        //Limpiar los errores
        form.clearErrors('tax');
        // Agregar los datos de impuesto
        form.tax.push(<number>form.taxValue);
        //Limpiar el campo para agregar otro
        form.reset('taxValue');
    }

}

//Eliminar los Itbis
const removeTax = (index:number) => {
    //Eliminar los datos seleccionado
    Swal.fire({
        title: "Desea eliminar este ITBIS?",
        text: "Los Cambios Realizados Son Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Eliminar!"
    }).then((result) => {
        if (result.isConfirmed) {
            //Eliminar los datos
            form.tax.splice(index, 1);

        }
    });

}

</script>

<template>
    <Head title="Ajustes" />
    <AppLayout>
        <template #header>
            <LinkHeader
                :active="true"
                :href="route('register')">
                Ajustes
            </LinkHeader>
        </template>

        <form
            @submit.prevent="submit"
            class="max-w-2/4 bg-gray-200 rounded-md p-5"
            action="">

            <div class="">
                <img
                    class="rounded-full mx-auto"
                    :src="`${url}/storage/images/${imgName}`"
                    alt="logo"
                    width="150">
            </div>

            <fieldset class=" mt-5 grid grid-cols-3 gap-3 border-2 border-gray-400 p-5 rounded-md">
                <legend>
                    Datos de la Empresa
                </legend>
                <div>
                    <InputLabel
                        for="company"
                        value="Nombre *"/>
                    <TextInput
                        v-model="form.name"
                        required
                        maxLength="75"
                        class="w-full"/>
                    <InputError :message="form.errors.name"/>
                </div>
                <div>
                    <InputLabel
                        for="email"
                        value="Correo *"/>
                    <TextInput
                        v-model="form.email"
                        required
                        class="w-full"
                        maxLength="75"
                        type="email" />
                    <InputError :message="form.errors.email"/>
                </div>
                <div>
                    <InputLabel
                        for="phone"
                        value="Teléfono *"/>
                    <TextInput
                        v-model="form.phone"
                        required
                        maxLength="30"
                        class="w-full"
                        type="text" />
                    <InputError :message="form.errors.phone"/>
                </div>
                <div>
                    <InputLabel
                        for="address"
                        value="Direccion"/>
                    <TextInput
                        v-model="form.address"
                        maxLength="255"
                        class="w-full"
                        type="text" />
                    <InputError :message="form.errors.address"/>
                </div>
                <div>
                    <InputLabel
                        for="website"
                        value="Pagina Web"/>
                    <TextInput
                        v-model="form.website"
                        maxLength="255"
                        class="w-full"
                        type="text" />
                    <InputError :message="form.errors.website"/>
                </div>
                <div>
                    <InputLabel
                        for="id"
                        value="RNC"/>
                    <TextInput
                        v-model="form.company_id"
                        name="id"
                        maxLength="30"
                        class="w-full"
                        type="text" />
                    <InputError :message="form.errors.company_id"/>
                </div>
                <div>
                    <InputLabel
                        for="tax"
                        value="Itbis"/>
                    <div class="relative">
                        <TextInput
                            class="w-full pr-10"
                            name="tax"
                            v-model="form.taxValue"
                            type="number" />
                        <i
                            @click="addTax"
                            class=" absolute inset-y-0 right-0 p-3 bg-gray-300 rounded-tr-md rounded-br-md flex items-center fa-solid fa-circle-plus"></i>
                    </div>
                    <InputError :message="form.errors.tax"/>
                    <div
                        class=""
                        v-if="form.tax.length > 0"
                        v-for="(item, index) in form.tax" :key="item">
                        <p class=" border-b-2 border-gray-400 rounded-md px-5">
                            <span>
                                {{item}}%
                            </span>
                            <span class="float-right">
                                <i
                                    @click="removeTax(index)"
                                    class=" text-red-500 fa-solid fa-circle-xmark"></i>
                            </span>
                        </p>
                    </div>
                </div>


                <div>
                    <InputLabel
                        for="fiscal_year"
                        value="Año Fiscal"/>
                    <TextInput
                        v-model="form.fiscal_year"
                        name="fiscal_year"
                        class="w-full"
                        type="date" />
                    <InputError :message="form.errors.fiscal_year"/>
                </div>


                <div>
                    <InputLabel for="logo" value="Logo"/>
                    <TextInput
                        @input="form.logo = $event.target.files[0]"
                        multiple="false"
                        class=""
                        type="file"/>
                    <InputError/>
                </div>




            </fieldset>


            <div class="text-right mt-5">
                <PrimaryButton>
                    Registrar
                </PrimaryButton>
            </div>

        </form>

    </AppLayout>

</template>

