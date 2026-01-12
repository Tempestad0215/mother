<script setup lang="ts">
import {ProductTypeEnumI} from "@/Interfaces/ProductInterface";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";
import FRegisterWarehouse from "@/Pages/Setting/WH/FRegister.vue";
import {inject, ref} from "vue";
import {formProductKey} from "@/Injections/InjectionKeys";
import {FloatLabel, InputText, Select, Dialog, InputGroup, InputGroupAddon, Fieldset} from "primevue";


const propsW = defineProps<{
    productType: ProductTypeEnumI
	wareHouses: WarehouseBaseI[],
}>()

const form = inject(formProductKey)!!

const createWarehouse = ref<boolean>(false);



</script>

<template>
    <Fieldset legend="Informacion Extra">
        <div class="grid grid-cols-2 gap-4">
            <FloatLabel variant="on" >
                <InputText fluid id="sku"  v-model="form.sku" />
                <label for="sku">Codigo Externo</label>
            </FloatLabel>
            <FloatLabel variant="on" >
                <InputText fluid id="bar_code"  v-model="form.bar_code" />
                <label for="bar_code">Codigo de Barra</label>
            </FloatLabel>
            <InputGroup class="h-10">
                <InputGroupAddon>
                    <i @click="createWarehouse = true" class="pi pi-warehouse"></i>
                </InputGroupAddon>
                <FloatLabel variant="on" >
                    <Select :options="propsW.wareHouses" optionLabel="name" optionValue="id" class="" fluid id="warehouse"  v-model="form.warehouse_id" />
                    <label for="warehouse">Almacen</label>
                </FloatLabel>

            </InputGroup>
        </div>

        <Dialog
            v-model:visible="createWarehouse"
            modal
            header="Crear Almacen">
            <FRegisterWarehouse
                :edit-ware-houses="null"
                :update="false"/>
        </Dialog>

    </Fieldset>


</template>
