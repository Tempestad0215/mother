<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {paginationI} from "@/Interfaces/Global";
import {categoryBaseI} from "@/Interfaces/Categories";
import FRegister from "@/Pages/Categories/FRegister.vue";
import FShow from "@/Pages/Categories/FShow.vue";


const propsW = defineProps<{
    categories: paginationI<categoryBaseI>,
    categoryEdit?: categoryBaseI,
    update?: boolean
}>();
</script>

<template>
    <AppLayout>
        <template #header>
        </template>

        <div>
            <FRegister
                :update="update"
                :category-edit="propsW.categoryEdit"/>
            <FShow
                :categories="propsW.categories"/>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
