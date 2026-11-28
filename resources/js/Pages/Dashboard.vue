<script setup lang="ts">
import { KPI, ProductLowStock, TopProduct } from '@/Interfaces/DashboardInterface';
import { onMounted, ref } from 'vue';

const propsW = defineProps<{
    kpis: KPI;
    productsLowStock: ProductLowStock[];
    topProduct: Array<TopProduct>;
}>();

const chartData = ref<any>(null);
const chartOptions = ref<any>(null);

onMounted(() => {
    chartData.value = setChartData();
    chartOptions.value = setChartOptions();
});

const setChartData = () => {
    return {
        labels: Object.keys(propsW.kpis || {}).map((key) => {
            return key.replace(/_/g, ' ').toUpperCase();
        }),
        datasets: [
            {
                data: Object.values(propsW.kpis || {}),
                backgroundColor: ['#6366F1', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                hoverBackgroundColor: ['#4F46E5', '#059669', '#D97706', '#DC2626', '#7C3AED'],
                borderWidth: 2,
                borderColor: '#ffffff',
            },
        ],
    };
};

const setChartOptions = () => {
    return {
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    font: {
                        family: 'Inter, sans-serif',
                        size: 12,
                        weight: '500',
                    },
                    padding: 15,
                },
            },
        },
        maintainAspectRatio: false,
        responsive: true,
    };
};
</script>

<template>
    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50/50 min-h-screen space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start"></div>
    </div>
</template>
