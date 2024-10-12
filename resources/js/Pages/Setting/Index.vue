<script setup lang="ts">
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import Swal from "sweetalert2";
import LinkHeader from "@components/LinkHeader.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {onMounted, onUpdated, Ref, ref} from "vue";
// import {settingsDataI} from "@/Interfaces/Setting";
import {successHttp} from "@/Global/Alert";
import {taxI} from "@/Interfaces/Global";


/*
Datos de ajuste
 */
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
        form.company_type = page.props.setting.company_type ?? "";
        form.tax = page.props.setting.tax;
        form.unit = page.props.setting.unit;
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
Datos del formulario
 */
const form = useForm({
    name:"",
    email:"",
    phone:"",
    address:"",
    website:"",
    company_id:"",
    taxName:"",
    taxValue:"",
    tax: [] as taxI[],
    unitValue:"",
    unit:[] as string[],
    is_branch: false,
    fiscal_year: "",
    company_type: "",
    logo:"",
    cost: true,
    sequence: true
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
            successHttp('Datos registrado correctamente');
        }
    })
}
//Agregar lis impuesto
const addTax = () => {
    //Verificar si existe
    let exists = form.tax.find((el) => el.name === form.taxValue);

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
        form.tax.push({
            name: form.taxName.toUpperCase(),
            amount: parseFloat(form.taxValue)
        });
        //Limpiar el campo para agregar otro
        form.reset('taxValue','taxName');
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

//Agregar Unidad a la lista
const addUnit = () => {
    //Verificar si existe
    let exists = form.tax.find((el) => el.name === form.taxValue);

    if (form.unitValue === "")
    {
        form.setError('unit','EL Campo Unidad No Puede Estar En Blanco ');
    }
    else if (exists)
    {
        //Poner el error
        form.setError('unit','El Campo Unidad No Se puede Repetir');
        return false;

    }else if(form.unit.length > 10)
    {
        form.setError('unit','Ha Alcanzado la Cantidad Maxima de Impuesto');
    }
    else{
        //Limpiar los errores
        form.clearErrors('unit');
        // Agregar los datos de impuesto
        form.unit.push(form.unitValue.toUpperCase());
        //Limpiar el campo para agregar otro
        form.reset('unitValue');
    }

}


//Eliminar los Itbis
const removeUnit = (index:number) => {
    //Eliminar los datos seleccionado
    Swal.fire({
        title: "Desea eliminar esta Unidad?",
        text: "Los Cambios Realizados Son Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Eliminar!"
    }).then((result) => {
        if (result.isConfirmed) {
            //Eliminar los datos
            form.unit.splice(index, 1);

        }
    });

}


</script>

<template>
    <Head title="Ajustes" />
    <AppLayout>
<!--Cabecera de la pagina-->
        <template #header>
            <LinkHeader
                :active="true"
                :href="route('setting.index')">
                Ajustes
            </LinkHeader>
            <LinkHeader
                v-if="isSequence"
                :href="route('sequence.create')">
                Correlativos
            </LinkHeader>
        </template>

        <div class="max-w-[1180px] mx-auto bg-gray-200 rounded-md p-5">
            <form
                @submit.prevent="submit">

                <!--Muestra del logo-->
                <div class="">
                    <img
                        class="rounded-full mx-auto"
                        :src="`${url}/storage/images/${imgName}`"
                        alt="logo"
                        width="150">
                </div>

                <!-- Informaicon de la emprea-->
                <fieldset class=" mt-5 grid grid-cols-3 gap-3 border-2 border-gray-400 p-5 rounded-md">
                    <legend class="px-3">
                        Datos de la Empresa
                    </legend>
                    <!-- Nombre-->
                    <div>
                        <InputLabel
                            for="company"
                            value="Nombre *"/>
                        <TextInput
                            name="name"
                            v-model="form.name"
                            required
                            maxLength="75"
                            class="w-full"/>
                        <InputError :message="form.errors.name"/>
                    </div>
                    <!--Correo-->
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
                    <!--Telefono-->
                    <div>
                        <InputLabel
                            for="phone"
                            value="Teléfono *"/>
                        <TextInput
                            name="phone"
                            v-model="form.phone"
                            required
                            maxLength="30"
                            class="w-full"
                            type="text" />
                        <InputError :message="form.errors.phone"/>
                    </div>
                    <!--Direccion-->
                    <div>
                        <InputLabel
                            for="address"
                            value="Direccion"/>
                        <TextInput
                            name="address"
                            v-model="form.address"
                            maxLength="255"
                            class="w-full"
                            type="text" />
                        <InputError :message="form.errors.address"/>
                    </div>
                    <!--Pagina Web-->
                    <div>
                        <InputLabel
                            for="website"
                            value="Pagina Web"/>
                        <TextInput
                            name="website"
                            v-model="form.website"
                            maxLength="255"
                            class="w-full"
                            type="text" />
                        <InputError :message="form.errors.website"/>
                    </div>
                    <!--Rnc-->
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
                        <InputLabel for="company_type" value="Empresa" />
                        <select
                            v-model="form.company_type"
                            class="border-gray-300 rounded-md w-full"
                            form="company_type" id="company_type">
                            <option selected value="">
                                ----- Selecciona La Empresa -----
                            </option>
                            <option v-for="(item, index) in propsW.company_type" :key="index" >
                                {{item}}
                            </option>

                        </select>
                    </div>





                    <!--Tiempo fiscal-->
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


                    <!--Logo-->
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

                <!--            Datos de inventario-->
                <fieldset class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
                    <legend class="px-3">
                        Inventario
                    </legend>

                    <!--               Proteger costo -->
                    <div class="col-span-full grid grid-cols-2 gap-3 ">

                        <fieldset class="flex">
                            <legend>
                                Proteger Costo
                            </legend>
                            <!--                       Si -->
                            <div >
                                <input
                                    class="peer hidden"
                                    :value="true"
                                    v-model="form.cost"
                                    type="radio"
                                    name="yes_cost"
                                    id="yes_cost">
                                <label
                                    class=" bg-gray-400 px-3 py-1 rounded-md peer-checked:bg-gray-800 peer-checked:text-white duration-300"
                                    id="yes_cost"
                                    for="yes_cost">
                                    Si
                                </label>

                            </div>

                            <!--                        No-->
                            <div class="ml-5">
                                <input
                                    class="peer hidden"
                                    :value="false"
                                    v-model="form.cost"
                                    type="radio"
                                    name="no_cost"
                                    id="no_cost">
                                <label
                                    class=" bg-gray-400 px-3 py-1 rounded-md peer-checked:bg-gray-800 peer-checked:text-white duration-300"
                                    id="no_cost"
                                    for="no_cost">
                                    No
                                </label>

                            </div>
                            <InputError :message="form.errors.cost"/>
                        </fieldset>


                        <!-- Manejar comprobantes -->
                        <fieldset class="flex">
                            <legend>
                                Manejar Comprobante
                            </legend>
                            <!--                        Si-->
                            <div >
                                <input
                                    class="peer hidden"
                                    :value="true"
                                    v-model="form.sequence"
                                    type="radio"
                                    name="yes_sequence"
                                    id="yes_sequence">
                                <label
                                    class=" bg-gray-400 px-3 py-1 rounded-md peer-checked:bg-gray-800 peer-checked:text-white duration-300"
                                    id="yes_sequence"
                                    for="yes_sequence">
                                    Si
                                </label>

                            </div>
                            <!--                        No-->
                            <div class="ml-5">
                                <input
                                    class="peer hidden"
                                    :value="false"
                                    v-model="form.sequence"
                                    type="radio"
                                    name="no_sequence"
                                    id="no_sequence">
                                <label
                                    class=" bg-gray-400 px-3 py-1 rounded-md peer-checked:bg-gray-800 peer-checked:text-white duration-300"
                                    id="no_sequence"
                                    for="no_sequence">
                                    No
                                </label>

                            </div>
                            <InputError :message="form.errors.sequence"/>
                        </fieldset>
                    </div>




                    <!--Unidades de medida-->
                    <div>
                        <InputLabel
                            for="unit"
                            value="Unidades"/>
                        <div class="relative">
                            <TextInput
                                :autocomplete="false"
                                class="w-full pr-10"
                                name="unit"
                                v-model="form.unitValue"
                                type="text" />
                            <i
                                @click="addUnit"
                                class=" absolute inset-y-0 right-0 p-3 bg-gray-300 rounded-tr-md rounded-br-md flex items-center fa-solid fa-circle-plus"></i>
                        </div>
                        <InputError :message="form.errors.unit"/>
                        <div
                            class=""
                            v-if="form.unit.length > 0"
                            v-for="(item, index) in form.unit" :key="item">
                            <p class=" border-b-2 border-gray-400 rounded-md px-5">
                            <span>
                                {{item}}
                            </span>
                                <span class="float-right">
                                <i
                                    @click="removeUnit(index)"
                                    class=" text-red-500 fa-solid fa-circle-xmark"></i>
                            </span>
                            </p>
                        </div>
                    </div>


                    <!--Itbis-->
                    <div>
                        <InputLabel
                            for="tax"
                            value="Itbis"/>
                        <div class="relative">
                            <TextInput
                                type="text"
                                name="name"
                                v-model="form.taxName"
                                placeholder="Nombre"
                                class="inline w-[40%] "/>
                            <TextInput
                                class=" inline w-[60%] pr-10"
                                name="tax"
                                placeholder="Valor"
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
                            v-for="(item, index) in form.tax" :key="index">
                            <p class=" border-b-2 border-gray-400 rounded-md px-5">
                            <span>
                                 {{item.name}} - {{item.amount}}%
                            </span>
                                <span class="float-right">
                                <i
                                    @click="removeTax(index)"
                                    class=" text-red-500 fa-solid fa-circle-xmark"></i>
                            </span>
                            </p>
                        </div>
                    </div>

                </fieldset>


                <!--Botones-->
                <div class="text-right mt-5">
                    <PrimaryButton>
                        Registrar
                    </PrimaryButton>
                </div>

            </form>
        </div>


    </AppLayout>

</template>

