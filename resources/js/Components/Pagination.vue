<script setup lang="ts">
import { Link } from '@inertiajs/vue3';


interface paginationI {
    currentPage?: number
    totalPage?: number
    next?: (string|null)
    prev?: (string|null)
}


const props = defineProps<paginationI>()

</script>


<template>
    <!-- PAginacion de todo -->
    <div class=" mt-5 flex justify-between items-center border-t border-gray-600 pt-2">
        <div>
            <!-- Pagina -->
            <span>
                <strong>
                    Página :
                </strong>
                {{ props.currentPage }}
            </span>

            <!-- Total -->
            <span>
                <strong>
                    Total :
                </strong>
                {{ props.totalPage}}
            </span>
        </div>

        <!-- Paginacion -->
        <div class=" text-3xl space-x-5">
            <!-- Anterior -->
            <Link
                :href="props.prev ? props.prev : '' ">
                <i
                    class="fa-solid fa-circle-arrow-left"></i>

            </Link>

            <!-- Siguiente -->
            <Link
                :href="props.next ? props.next : '' ">
                <i
                    class="fa-solid fa-circle-arrow-right"></i>
            </Link>

        </div>

    </div>

</template>
