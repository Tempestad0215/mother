<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {paginationJoin} from "@/Global/Helpers";


interface paginationI {
    search: string;
    perPage: number;
    field: string;
    currentPage?: number
    totalPage?: number
    next?: (string|null)
    prev?: (string|null)
}


const props = defineProps<paginationI>()

</script>


<template>
    <!-- PAginacion-->
    <div class="py-5 ">
        <hr>
        <div class="flex items-center justify-between text-white mt-5">
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
                    preserve-state
                    preserve-scroll
                    :href="props.prev ? paginationJoin(props.prev,field, search, perPage)  : '' ">
                    <i
                        class="fa-solid fa-circle-arrow-left"></i>

                </Link>

                <!-- Siguiente -->
                <Link
                    preserve-state
                    preserve-scroll
                    :href="props.next ? paginationJoin(props.next,field,search, perPage) : '' ">
                    <i
                        class="fa-solid fa-circle-arrow-right"></i>
                </Link>

            </div>
        </div>


    </div>

</template>
