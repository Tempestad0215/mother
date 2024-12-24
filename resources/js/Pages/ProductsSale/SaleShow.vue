<script setup lang="ts">
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import FormSearch from "@components/FormSearch.vue";
import {salePaginationI} from "@/Interfaces/Sale";
import {getMoney} from "@/Global/Helpers";
import Pagination from "@components/Pagination.vue";
import InputError from "@components/InputError.vue";
import {ref, Ref} from "vue";
import TabLink from "@components/TabLink.vue";


/*
Datos de la pagina
 */
const page = usePage();

//Uso del toast



/*
 * Propiedades de la ventana
 */
const propsW = defineProps<{
    sales: salePaginationI,
}>();



/*
 * Datos del formulario
 */
const form = useForm({
    search: "",
    perPage: 15,
    general: "",
});



/*
Datos de la ventana
 */
const urlPdf:Ref<string> = ref("");
const pdfShow:Ref<boolean> = ref(false);



/*
Funciones
 */

//Enviar los datos
const submit = () => {
    form.get('',{
        preserveScroll: true,
        preserveState: true
    });
}


/**
 * Eliminar la registrada
 * @param id
 */
// const destroy = (id:number) => {
//
//     Swal.fire({
//         title: "Desea Eliminar Este Documento?",
//         text: "Los Cambios Realizados Son Irreversible!",
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#3085d6",
//         cancelButtonColor: "#d33",
//         confirmButtonText: "Si, Eliminar!",
//         cancelButtonText: "Cancelar"
//     }).then((result) => {
//         if (result.isConfirmed) {
//
//             Swal.fire({
//                 title: "Desea Afectar El Inventario?",
//                 html: `
//                     <div>
//                         <p> <b>Comentario :</b> </p>
//                         <input
//                             autocomplete="false"
//                             class="w-full border-gray-200 rounded-md"
//                             type="text"
//                             id="comment" />
//                     </div>
//                 `,
//                 showDenyButton: true,
//                 showCancelButton: true,
//                 confirmButtonText: "Si",
//                 denyButtonText: "No",
//                 cancelButtonText: "Cancelar",
//                 preConfirm() {
//
//                     //Tomar el valor del comentario
//                     let comment = (document.getElementById("comment") as HTMLInputElement).value;
//
//                     // Verificar si existe datos de comentario
//                     if (comment.length < 4)
//                     {
//                         Swal.showValidationMessage("Este Campos Es Obligaotorio y Debes Contener Al Menos 5 Caracter");
//                         return  false;
//                     }
//                 },
//                 preDeny() {
//                     //tomar el valor del input
//                     let comment = (document.getElementById("comment") as HTMLInputElement).value;
//
//                     if (comment.length < 4)
//                     {
//                         Swal.showValidationMessage("Este Campos Es Obligaotorio y Debes Contener Al Menos 5 Caracter");
//                         return  false;
//                     }
//
//                 }
//             }).then((result) => {
//                 let comment = (document.getElementById("comment") as HTMLInputElement).value;
//                 // console.log(com);
//
//                 /* Read more about isConfirmed, isDenied below */
//                 if (result.isConfirmed) {
//
//                     destroySale(id, true, comment);
//
//                 } else if (result.isDenied) {
//
//                     destroySale(id, false, comment);
//
//                 }
//             });
//
//         }
//     });
// }


/**
 *
 * @param id
 * @param inventoried
 * @param comment
 */
// const destroySale = (id: number, inventoried: boolean, comment: string) => {
//
//     router.patch(route('sale.destroy-sale',{sale: id, inventoried: inventoried}),{
//         comment: comment
//     },{
//         preserveScroll: true,
//         preserveState: true,
//         onSuccess: () => {
//             successHttp('Docuemnto Eliminado Correctamente');
//         }
//     });
//
// }

/**
 * Devolver la cuenta creada
 * @param id
 */
// const refund  = (id:number):void => {
//     //llmar la nota de credito
//     router.get(route('credit-note.index',{sale: id}));
//
// }

/**
 *
 */
const printFact = async (uuid:string) => {

    const popupOptions = `
        width=800,
        height=600,
        top=${(screen.height - 600) / 2},
        left=${(screen.width - 800) / 2},
        resizable=no,
        scrollbars=no,
        status=no
    `;

    // Abrir la ventana emergente
    const popupWindow = window.open(route('invoice.getA',{sale: uuid}), '_blank', popupOptions);

    // Verificar que la ventana se haya abierto
    if (!popupWindow || popupWindow.closed || typeof popupWindow.closed === 'undefined') {
        alert('Permite las ventanas emergentes en tu navegador.');
        return;
    }

    // Esperar a que la ventana se cargue y luego iniciar la impresión
    popupWindow.onload = () => {
        popupWindow.print(); // Llamar la función de imprimir
    };


    // //Data de la busqueda
    // const data = await axios.get(route('invoice.getA',{sale: id}));
    //
    // //Verificar si es diferente de la impresion
    // if (data.status !== 200)
    // {
    //     //PAra cancelar la instruccion
    //     return
    // }
    //
    //
    //
    // //Poner la url
    // urlPdf.value = data.data.url;
    // pdfShow.value = true;


    // setTimeout(()=>{
    //     urlPdf.value = "";
    //     pdfShow.value = false;
    // },5000)

    // window.print();
}


/**
 * Erro al imprimir el pdf
 */
// const getErrorPdf = (msj: string) => {
// }


/*
Para imprimir los mensaje del nevegador
 */
const printTest = () => {
    const popupOptions = `
        width=800,
        height=600,
        top=${(screen.height - 600) / 2},
        left=${(screen.width - 800) / 2},
        resizable=no,
        scrollbars=no,
        status=no
    `;

    // Abrir la ventana emergente
    const popupWindow = window.open(route('test'), '_blank', popupOptions);

    // Verificar que la ventana se haya abierto
    if (!popupWindow || popupWindow.closed || typeof popupWindow.closed === 'undefined') {
        alert('Permite las ventanas emergentes en tu navegador.');
        return;
    }

    // Esperar a que la ventana se cargue y luego iniciar la impresión
    popupWindow.onload = () => {
        popupWindow.print(); // Llamar la función de imprimir
    };
}

</script>

<template>
    <Head title="Mostrar Ventas"/>
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('sale.create')">
                Ventas
            </TabLink>
            <TabLink
                :active="true"
                :href="route('sale.show')">
                Mostrar
            </TabLink>
        </template>

        <div
            class="bg-blue-300 max-w-[1180px] rounded-md p-5 mx-auto overflow-hidden">
<!--          Mensajes  -->
<!--            Contenido-->
            <div class="flex justify-between items-center">
                <form
                    @submit.prevent="submit">
                    <FormSearch
                        v-model:per-page="form.perPage"
                        v-model:search="form.search"/>
                </form>
                <h3 class="text-3xl font-bold">
                    Ventas
                </h3>
            </div>

            <table
                class=" mt-3 styleTable w-full table-auto">
                <thead >
                    <tr class=" border-b-2 border-gray-800 text-left">
                        <th>Cliente</th>
                        <th>Itbis</th>
                        <th>Sub Total</th>
                        <th>Total</th>
                        <th>Mesa A/C</th>
                        <th>Act</th>
<!--                        <th v-if="page.props.auth.user.role === 'admin'">Act</th>-->
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class="hoverTable"
                        v-for="(item,index) in propsW.sales.data" :key="index">
                        <td>{{item.client_name}}</td>
                        <td>{{ getMoney(item.tax)}}</td>
                        <td>{{getMoney(item.sub_total)}}</td>
                        <td>{{getMoney(item.amount)}}</td>
                        <td>{{item.close_table ? 'Cerrada' : 'Abierta'}}</td>
                        <td>
                            <i
                                title="Imprimir"
                                @click="printFact(item.uuid)"
                                class=" icon-efect fa-solid fa-print"></i>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div>
                <InputError :message="page.props.errors.general"/>
            </div>


            <Pagination
                :current-page="propsW.sales.current_page"
                :total-page="propsW.sales.to"
                :prev="propsW.sales.prev_page_url
                    ? propsW.sales.prev_page_url+'&perPage='+form.perPage
                    :''"
                :next=" propsW.sales.prev_page_url
                    ? propsW.sales.next_page_url+'&perPage='+form.perPage
                    : ''"/>

            <!--           Mensajke de error-->
            <InputError :message="page.props.errors.comment"/>

        </div>




    </AppLayout>

</template>
