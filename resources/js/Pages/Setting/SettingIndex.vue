<script setup lang="ts">
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {onMounted, onUpdated, Ref, ref} from "vue";
// import {settingsDataI} from "@/Interfaces/Setting";
import {successHttp} from "@/Global/Alert";
import {taxI} from "@/Interfaces/Global";
import ToggleButton from "@components/ToggleButton.vue";
import TabLink from "@components/TabLink.vue";




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
    company_type: "BAR",
    logo:"",
    cost: true,
    sequence: true,
    general: ""
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

    //Eliminar los datos
    form.tax.splice(index, 1);


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
    //Eliminar los datos
    form.unit.splice(index, 1);


}


</script>

<template>
    <Head title="Ajustes" />
    <AppLayout>
<!--Cabecera de la pagina-->
        <template #header>
            <TabLink
                :active="true"
                :href="route('setting.index')">
                Ajustes
            </TabLink>
            <TabLink
                :href="route('aco.index')">
                Cuentas
            </TabLink>
        </template>
        <div
            class="max-w-[70rem] mx-auto bg-blue-300 rounded-md p-5">
            <form
                @submit.prevent="submit">
                <!--Muestra del logo-->
                <div class="">
                    <img
                        class="rounded-2xl mx-auto"
                        :src="`${url}/storage/images/${imgName}`"
                        alt="logo"
                        width="150">
                </div>
                <!-- Informaicon de la emprea-->
                <fieldset class="field">
                    <legend class="">
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
                            placeholder="Jose Manuel"
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
                            placeholder="jose@example.com"
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
                            placeholder="+1 (425) 456-6456"
                            v-mask="['+# (###) ###-####','+## (###) ###-####']"
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
                            placeholder="Camino Real #12"
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
                            placeholder="www.paginaweb.com"
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
                            placeholder="123-456891"
                            v-mask="['###-######']"
                            maxLength="30"
                            class="w-full"
                            type="text" />
                        <InputError :message="form.errors.company_id"/>
                    </div>
                    <div>
                        <InputLabel for="company_type" value="Tipo de Negocio" />
                        <select
                            class="inputGeneral py-1 w-full"
                            v-model="form.company_type">
                            <option
                                v-for="(item, index) in propsW.company_type"
                                :key="index"
                                :value="item">
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
                            class="file"
                            type="file"/>
                        <InputError/>
                    </div>
                </fieldset>
                <!--            Datos de inventario-->
                <fieldset class="field">
                    <legend class="px-3">
                        Inventario
                    </legend>

                    <!--               Proteger costo -->
                    <div class="col-span-full grid grid-cols-2 gap-3 ">
                        <div>
                            <ToggleButton
                                label="Proteger costo"
                                v-model="form.cost"
                                on-label="SI"
                                off-label="NO"/>

                            <InputError :message="form.errors.cost"/>
                        </div>
                        <div>
                            <ToggleButton
                                label="Manejar Comprobante"
                                v-model="form.sequence"
                                on-label="SI"
                                off-label="NO"/>
                            <InputError :message="form.errors.sequence"/>
                        </div>
                    </div>

                    <!--Unidades de medida-->
                    <div>
                        <InputLabel
                            for="unit"
                            value="Unidades"/>
                        <div class="relative">
                            <TextInput
                                class="pr-8 w-full "
                                name="unit"
                                placeholder="Unidad"
                                v-model="form.unitValue"/>
                            <i
                                @click="addUnit"
                                class=" flex items-center inset-y-0 absolute right-0 p-2 bg-transparent fa-solid fa-square-plus"></i>

                        </div>


                        <InputError :message="form.errors.unit"/>
                        <div
                            class=" text-sm"
                            v-if="form.unit.length > 0"
                            v-for="(item, index) in form.unit" :key="index">
                            <span
                                @click="removeUnit(index)"
                                class=" px-3 py-1 mt-1 bg-blue-400 rounded-md flex items-center justify-between">
                                {{item}}
                                <i class="p-1 cursor-pointer text-[1.2rem] fa-regular fa-rectangle-xmark"></i>
                            </span>

                        </div>
                    </div>


                    <!--Itbis-->
                    <div>
                        <InputLabel
                            for="unit"
                            value="Unidades"/>
                        <div class="relative flex gap-3">
                            <TextInput
                                class="pr-8"
                                placeholder="ITBIS Name"
                                name="unit"
                                v-model="form.taxName"/>
                            <TextInput
                                class="pr-8 w-full "
                                placeholder="ITBIS Valor"
                                name="unit"
                                v-model="form.taxValue"/>
                            <i
                                @click="addTax"
                                class=" flex items-center inset-y-0 absolute right-0 p-2 bg-transparent fa-solid fa-square-plus"></i>

                        </div>
                        <InputError :message="form.errors.tax"/>
                        <div
                            class="text-sm flex flex-wrap"
                            v-if="form.tax.length > 0"
                            v-for="(item, index) in form.tax" :key="index">
                            <span
                                @click="removeTax(index)"
                                class=" px-3 py-1 mt-1 bg-blue-400 rounded-md flex flex-1 items-center justify-between">
                                {{item.name}} = {{item.amount}}
                                <i class="p-1 cursor-pointer text-[1.2rem] fa-regular fa-rectangle-xmark"></i>
                            </span>

                        </div>
                    </div>

                </fieldset>
                <div >
                    <InputError :message="form.errors.general"/>
                </div>


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

