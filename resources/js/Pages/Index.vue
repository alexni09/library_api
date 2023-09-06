<script setup>
import { onMounted, ref, toRaw, onBeforeUnmount } from 'vue'
import dayjs from 'dayjs'
var myInterval = null
const lines = ref(null)
const fetchLines = () => {
    axios.get('/api/monitor')
        .then((response) => {
            lines.value = toRaw(response.data.data)
        })
        .catch(function (error) {
            console.log(error)
        })
}
onMounted(() => {
    fetchLines()
    myInterval = setInterval(fetchLines, 5000)
})
onBeforeUnmount(() => clearInterval(myInterval))
</script>
<template>
    <div class="flex justify-center">
        <h1 class="mt-2 mb-3 font-bold text-3xl">library_api</h1>
    </div>
    <div class="flex justify-center">
        <table class="bg-zinc-50 border border-zinc-500">
            <tr>
                <th class="p-1 bg-zinc-300 font-semibold border-b border-zinc-500 whitespace-nowrap">When ?</th>
                <th class="thStyle">Method</th>
                <th class="thStyle">URL</th>
                <th class="thStyle">Status</th>
            </tr>
            <tr v-if="lines?.length === 0">
                <td colspan="4" class="p-1 font-medium italic whitespace-nowrap">No Records Found!</td>
            </tr>
            <tr v-for="line in lines" :key="line.id" :class="{ 'bg-zinc-200': line.id % 6 > 2 }">
                <td class="px-1 whitespace-nowrap">{{ dayjs(line.datetime + '+00:00').format('DD/MM/YYYY HH:mm:ss') }}</td>
                <td class="tdStyle">{{ line.method }}</td>
                <td class="tdStyle">{{ line.url }}</td>
                <td class="tdStyle">{{ line.status }}</td>
            </tr>
        </table>
    </div>
</template>
<style lang="postcss" scoped>
.tdStyle {
    @apply px-1 border-l border-zinc-500;
}
.thStyle {
    @apply p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500;
}
</style>