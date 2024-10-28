<script setup lang="ts">
import {Chart} from "chart.js/auto";
import {onMounted, reactive} from "vue";
import {mostSoldI} from "@/Interfaces/Report";
import {generateColors} from "@/Global/Helpers"


/*
Propiedades de la ventana
 */


const propsW = defineProps<{
    mostSold: mostSoldI[]
}>();


onMounted(() => {
    if (propsW.mostSold)
    {
        propsW.mostSold.map((item:mostSoldI) => {
           dataFormat.productName.push(item.name);
           dataFormat.productSold.push(item.totalSaled);
        });
    }

    //Para craer los datos
    createChart();
});


//Para parasr los datos
const dataFormat = reactive({
  productName: [],
  productSold:[],
  productColor:[]
});


/**
 * Crear los el grafico de pastel
 */
const createChart = () => {
    //Ontener el id
    const ctx = document.getElementById('mostSold');

    //Crea los datos
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: dataFormat.productName,
            datasets: [{
                label: '10 Productos Mas Vendido',
                data: dataFormat.productSold,
                borderColor: dataFormat.productColor,
                backgroundColor: generateColors(dataFormat.productName.length),
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

</script>


<!--Contenido de la ventana-->
<template>
    <canvas
        class="max-h-[400px]"
        id="mostSold"
        aria-label="test de la mejor">
    </canvas>
</template>

<style scoped>

</style>
