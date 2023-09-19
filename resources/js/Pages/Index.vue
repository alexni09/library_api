<script setup>
import { onMounted, ref, toRaw, onBeforeUnmount } from 'vue'
import dayjs from 'dayjs'
const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
})
var intervalLines = null
var intervalAccumulatedMoney = null
var intervalStatistics = null
const lines = ref(null)
const accumulatedMoney = ref("")
const statistics = ref(null)
const fetchAccumulatedMoney = () => {
    axios.get('/api/money')
        .then((response) => {
            accumulatedMoney.value = formatter.format(response.data.money / 100)
        })
        .catch(function (error) {
            console.log(error)
        })
}
const fetchLines = () => {
    axios.get('/api/monitor')
        .then((response) => {
            lines.value = toRaw(response.data.data)
        })
        .catch(function (error) {
            console.log(error)
        })
}
const fetchStatistics = () => {
    axios.get('/api/count')
        .then((response) => {
            statistics.value = toRaw(response.data.data)
        })
        .catch(function (error) {
            console.log(error)
        })
}
onMounted(() => {
    fetchAccumulatedMoney()
    fetchLines()
    fetchStatistics()
    intervalLines = setInterval(fetchLines, 5000)
    intervalAccumulatedMoney = setInterval(fetchAccumulatedMoney, 3500)
    intervalStatistics = setInterval(fetchStatistics, 60000)
})
onBeforeUnmount(() => {
    clearInterval(intervalLines)
    clearInterval(intervalAccumulatedMoney)
    clearInterval(intervalStatistics)
})
</script>
<template>
    <div class="flex justify-center">
        <h1 class="mt-2 mb-2 font-bold text-4xl">:<span class="ml-1">:</span> library_api <span class="mr-1">:</span>:</h1>
    </div>
    <div class="flex justify-center">
        <h3 class="mb-4 font-medium text-2xl">Accumulated: <i class="mr-0.5">F</i>{{ accumulatedMoney }}</h3>
    </div>
    <div class="flex justify-center">
        <table class="bg-zinc-50 border border-zinc-500">
            <tr>
                <th class="p-1 bg-zinc-300 font-semibold border-b border-zinc-500 whitespace-nowrap">When ?</th>
                <th class="thStyle">Method</th>
                <th class="thStyle">URL</th>
                <th class="thStyle">IP</th>
                <th class="thStyle">Status</th>
            </tr>
            <tr v-if="lines?.length === 0">
                <td colspan="5" class="p-1 font-medium italic whitespace-nowrap">No Records Found!</td>
            </tr>
            <tr v-for="line in lines" :key="line.id" :class="{ 'bg-zinc-200': line.id % 6 > 2 }">
                <td class="px-1 whitespace-nowrap">{{ dayjs(line.datetime + '+00:00').format('YYYY/MM/DD HH:mm:ss') }}</td>
                <td class="tdStyle">{{ line.method }}</td>
                <td class="tdStyle">{{ line.url }}</td>
                <td class="tdStyle">{{ line.ip }}</td>
                <td class="tdStyle">{{ line.status }}</td>
            </tr>
        </table>
    </div>
    <div class="flex justify-center">
        <div class="justify-center">
            <h4 class="mt-3 font-bold text-lg">Statistics:<span class="ml-2 italic text-sm font-semibold">(updated hourly)</span></h4>
            <p class="font-normal text-base"><i>category_count:</i> <span class="ml-0.5 font-semibold">{{ statistics?.category_count }}</span></p>
            <p class="font-normal text-base"><i>book_count:</i> <span class="ml-0.5 font-semibold">{{ statistics?.book_count }}</span></p>
            <p class="font-normal text-base"><i>exemplar_count:</i> <span class="ml-0.5 font-semibold">{{ statistics?.exemplar_count }}</span></p>
            <p class="font-normal text-base"><i>database_size:</i> <span class="ml-0.5 font-semibold">{{ statistics?.mysql_count }}</span></p>
        </div>
    </div>
    <div class="h-20" />
</template>
<style lang="postcss" scoped>
.tdStyle {
    @apply px-1 border-l border-zinc-500;
}
.thStyle {
    @apply p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500;
}
</style>