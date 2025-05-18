<template>
    <div class="overflow-x-auto">
        <slot name="add-button"></slot>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-white">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                        #</th>
                    <th v-for="column in headerColumns" :key="column"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                        {{ column }}</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" v-if="data && data.length > 0">
                <tr v-for="(data, index) in paginatedData" :key="data.id">
                    <td class="px-6 py-4 whitespace-nowrap dark:bg-teal-50 text-xs">
                        {{ (currentPage - 1) * pageSize + index + 1 }}
                    </td>

                    <td v-for="column in columns" :key="column.model"
                        class="px-6 py-4 text-xs whitespace-nowrap dark:bg-teal-50 dark:text-gray-700">
                        {{ formatValue(column, data[column], data) }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap dark:bg-teal-50">
                        <slot name="button" :data="data"></slot>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%" class="text-center px-6 py-4 text-gray-500 text-xs">No data found.</td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center gap-5 items-center">
            <button @click="prevPage" :disabled="currentPage === 1"
                class="cursor-pointer px-3 py-1 bg-teal-600 text-white rounded hover:bg-teal-700 disabled:opacity-50">
                &lt;
            </button>

            <span class="text-xs dark:text-gray-900 text-gray-600">Page {{ currentPage }} of {{ totalPages }}</span>

            <button @click="nextPage" :disabled="currentPage === totalPages"
                class="cursor-pointer px-3 py-1 bg-teal-600 text-white rounded hover:bg-teal-700 disabled:opacity-50">
                &gt;
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, defineProps } from 'vue'
import { formatValueRupiah } from '@/utils/Utils'

const props = defineProps({
    data: Array,
    columns: Array,
    headerColumns: Array,
    currentPage: Number,
    pageSize: Number,
    totalPages: Number,
    prevPage: Function,
    nextPage: Function,
})

const formatValue = (column, value) => {
    if (typeof value === 'number' && column.toLowerCase().includes('price')) {
        return formatValueRupiah(value)
    }
    return value
}

const paginatedData = computed(() => {
    const start = (props.currentPage - 1) * props.pageSize
    const end = start + props.pageSize
    return props.data.slice(start, end)
})


</script>

<style scoped></style>