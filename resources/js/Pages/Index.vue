<script setup>
import { onMounted, ref, toRaw, onBeforeUnmount } from 'vue'
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
                <th class="p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500">Method</th>
                <th class="p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500">URL</th>
                <th class="p-1 bg-zinc-300 font-semibold border-l border-b border-zinc-500">Status</th>
            </tr>
            <tr v-if="lines.length === 0">
                <td colspan="4" class="font-medium whitespace-nowrap"><i>No Records Found!</i></td>
            </tr>
            <tr v-for="line in lines" :key="line.id">
                <td class="px-1 whitespace-nowrap" :class="{ 'bg-zinc-200': line.id % 6 > 2 }"></td>
                <td class="px-1 border-l border-zinc-500" :class="{ 'bg-zinc-200': line.id % 6 > 2 }">{{ line.method }}</td>
                <td class="px-1 border-l border-zinc-500" :class="{ 'bg-zinc-200': line.id % 6 > 2 }">{{ line.url }}</td>
                <td class="px-1 border-l border-zinc-500" :class="{ 'bg-zinc-200': line.id % 6 > 2 }">{{ line.status }}</td>
            </tr>
        </table>
    </div>
</template>