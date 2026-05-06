<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { productTransI } from '@/Interfaces/ProductInterface';
import FormSearch from '@components/FormSearch.vue';
import InputError from '@components/InputError.vue';
import { useRoute } from 'ziggy-js';

const route = useRoute();
/**
 * Propiedades
 */
const props = defineProps<{
  trans: productTransI;
}>();

/**
 * Formulario
 */
const form = useForm({
  search: '',
  perPage: 15,
  general: '',
});

/*
Funciones
 */
const submit = () => {
  // form.get(`?search=${form.search}`, {
  //     preserveScroll: true,
  //     preserveState: true
  // });

  form.get(``, {
    preserveScroll: true,
    preserveState: true,
  });
};

//Editar la entrada
// const edit = (id:number) => {
//     router.get(route('in.edit',{trans: id}));
// }

//Eliminar la transaccion
const destroy = (uuid: string) => {
  // Swal.fire({
  //     title: "Desea Eliminar?",
  //     text: "Los Cambios Realizados Son Irreversible!",
  //     icon: "warning",
  //     showCancelButton: true,
  //     confirmButtonColor: "#3085d6",
  //     cancelButtonColor: "#d33",
  //     confirmButtonText: "Si, Eliminar!",
  //     cancelButtonText: "Cancelar"
  // }).then((result) => {
  //     if (result.isConfirmed) {
  //         router.patch(route('in.destroy',{trans: uuid}),{},{
  //             preserveScroll: true,
  //             preserveState: true,
  //             onSuccess: () => {
  //
  //             },
  //             onError: () => {
  //                 //Limpiar el error luego de 5 Segundo
  //                 setTimeout(() => {
  //                     form.clearErrors('general');
  //                 },5000)
  //             }
  //         });
  //     }
  // });
};
</script>

<template>
  <!--    -->
  <Head title="Transacciones" />
  <AppLayout>
    <!-- Cabecera de la pagina-->
    <template #header> </template>

    <!-- Contenido de la ventana-->
    <div class="bg-gray-200 rounded-md mx-auto overflow-hidden p-5">
      <div class="flex items-center justify-between">
        <div>
          <form @submit.prevent="submit">
            <FormSearch holder="Buscar" v-model:select-value="form.perPage" v-model="form.search" />
            <InputError :message="form.errors.search" />
          </form>
        </div>
        <h3 class="text-3xl font-bold text-center">Transacciones</h3>
      </div>
      <!-- Datos de los productos para la entrada    -->
      <table class="mt-3 styleTable w-full">
        <thead>
          <tr>
            <th>Producto/Servicio</th>
            <th>Disp.</th>
            <th>Itbis</th>
            <th>Precio</th>
            <th>Tipo</th>
            <th>Act</th>
          </tr>
        </thead>
        <tbody>
          <!--                    <tr v-for="(item) in props.trans">-->
          <!--                        <td>{{item.product_name}}</td>-->
          <!--                        <td>{{ item.stock}}</td>-->
          <!--                        <td>{{ getMoney(item.tax)}}</td>-->
          <!--                        <td>{{ getMoney(item.price)}}</td>-->
          <!--                        <td>{{ item.type}}</td>-->
          <!--                        <td>-->
          <!--                            <span-->
          <!--                                v-if="item.type != 'entrada'">-->
          <!--                                N/A-->
          <!--                            </span>-->
          <!--                            <i-->
          <!--                                v-if="item.type == 'entrada'"-->
          <!--                                @click="destroy(item.uuid)"-->
          <!--                                class=" icon-efect ml-3 fa-solid fa-trash"></i>-->
          <!--                        </td>-->
          <!--                    </tr>-->
        </tbody>
      </table>
      <!-- Paginacion-->
      <!--            <Pagination-->
      <!--                :next="props.trans.links.next"-->
      <!--                :prev="props.trans.links.prev"-->
      <!--                :total-page="props.trans.meta.to"-->
      <!--                :current-page="props.trans.meta.current_page"/>-->
    </div>
  </AppLayout>
</template>
