<script setup>
import { onMounted, ref, toRaw } from 'vue'
const lines = ref(null)
const fetchLines = () => {
    axios.get('/api/monitor')
        .then((response) => {
            lines.value = toRaw(response.data.data)
            console.log(lines.value)
        })
        .catch(function (error) {
            console.log(error)
        })
}
onMounted(() => {
    fetchLines()
})
</script>
<template>
    <p class="font-bold text-xl mb-3">library_api</p>
    <table>
        <tr v-for="line in lines" :key="line.id">
            <td>{{ line.method }}</td>
            <td class="px-3">{{ line.url }}</td>
            <td>{{ line.status }}</td>
        </tr>
    </table>
</template>