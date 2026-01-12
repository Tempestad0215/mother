<script setup lang="ts" generic="T" >
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {PageState, Paginator} from "primevue";
import {router} from "@inertiajs/vue3";
import {computed} from "vue";


interface paginationActualI {
    search: string;
    pag: PaginationI<T>
}

const propsW = defineProps<paginationActualI>()

const first = computed(() => (propsW.pag.current_page - 1) * propsW.pag.per_page);

const onPageChange =(value: number) => {
    const nextRoute = value + 1
    const perPage = propsW.pag.per_page

    router.get(`${propsW.pag.path}?search=${propsW.search}&per_page=${perPage}`)
}

const changePerPage = (value: number) => {
    router.get(`${propsW.pag.path}?search=${propsW.search}&per_page=${value}`)
}
</script>


<template>
    <Paginator
        :first="first"
        @update:first="onPageChange"
        @update:rows="changePerPage"
        :rowsPerPageOptions="[15, 30, 45,60,85,100]"
        :rows="propsW.pag.per_page ?? 0"
        :totalRecords="propsW.pag.total"
    >

    </Paginator>


</template>
