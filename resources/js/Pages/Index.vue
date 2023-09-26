<script setup>
import { onMounted, ref, toRaw, onBeforeUnmount } from 'vue'
import dayjs from 'dayjs'
const myProps = defineProps({
    ip_list: Object
})
const formatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
})
var intervalLines = null
var intervalAccumulatedMoney = null
var intervalStatistics = null
const lines = ref(null)
const lines1 = ref(null)
const lines2 = ref(null)
const lines3 = ref(null)
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
            if (lines.value.length < 60) {
                lines1.value = lines.value.slice()
                lines2.value = null
                lines3.value = null
            } else if (lines.value.length >= 60 && lines.value.length < 90) {
                lines1.value = lines.value.slice(0, Math.floor(lines.value.length / 2))
                lines2.value = lines.value.slice(Math.floor(lines.value.length / 2, lines.value.length))
                lines3.value = null
            } else {
                lines1.value = lines.value.slice(0, Math.floor(lines.value.length / 3))
                lines2.value = lines.value.slice(Math.floor(lines.value.length / 3), Math.floor(lines.value.length * 2 / 3))
                lines3.value = lines.value.slice(Math.floor(lines.value.length * 2 / 3, lines.value.length))
            }
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
    <div class="font-oldstandardtt text-amber-950 text-lg">
        <div class="flex justify-center">
            <h1 class="mt-2 mb-2 font-bold text-4xl">:<span class="ml-1">:</span> library_api <span class="mr-1">:</span>:</h1>
        </div>
        <div class="flex justify-center">
            <h3 class="mb-4 font-medium text-2xl">Accumulated: <i class="mr-0.5">F</i>{{ accumulatedMoney }}</h3>
        </div>
        <div class="flex justify-center">
            <table class="tableStyle">
                <tr>
                    <th class="p-1 bg-stone-300 font-semibold border-b border-stone-500 whitespace-nowrap">When ?</th>
                    <th class="thStyle">Method</th>
                    <th class="thStyle">URL</th>
                    <th class="thStyle">IP</th>
                    <th class="thStyle">Status</th>
                </tr>
                <tr v-if="lines1?.length === 0">
                    <td colspan="5" class="p-1 font-medium italic whitespace-nowrap">No Records Found!</td>
                </tr>
                <tr v-for="line in lines1" :key="line.id" :class="{ 'bg-stone-200': line.id % 6 > 2 }">
                    <td class="tdDatetimeStyle">{{ dayjs(line.datetime + '+00:00').format('YYYY/MM/DD HH:mm:ss') }}</td>
                    <td class="tdMethodStyle">{{ line.method }}</td>
                    <td class="tdUrlStyle">{{ line.url }}</td>
                    <td v-if="line.ip in ip_list" class="tdIpStyle">
                        <a :href="ip_list[line.ip]" target="_blank">{{ line.ip }}</a>
                    </td>
                    <td v-else class="tdIpStyle">{{ line.ip }}</td>
                    <td class="tdStatusStyle">{{ line.status }}</td>
                </tr>
            </table>
        </div>
        <div v-if="lines?.length >= 60">
            <div class="flex justify-center">
                <div class="mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer" onclick="window.open('https://owlsearch.games', '_blank')" >
                    <img src="https://owlsearch.games/images/logo/owl56.jpg" class="absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95" />
                    <span class="absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900">Owl&nbsp;Search&nbsp;Games</span>
                    <span class="absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800">&#10149;&nbsp;Turbospeed&nbsp;your&nbsp;Brain!</span>
                    <span class="absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800">&#10149;&nbsp;Play&nbsp;without&nbsp;Ads!</span>
                    <button class="absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg" >&#10148;&nbsp;Play&nbsp;Now!</button>
                    <div class="motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg" />
                </div>
            </div>
            <div class="flex justify-center">
                <div class="mt-0.5 mb-4 font-black text-xs uppercase">Please support our sponsor.</div>
            </div>
        </div>
        <div v-if="lines?.length >= 60" class="flex justify-center">
            <table class="tableStyle">
                <tr v-for="line in lines2" :key="line.id" :class="{ 'bg-stone-200': line.id % 6 > 2 }">
                    <td class="tdDatetimeStyle">{{ dayjs(line.datetime + '+00:00').format('YYYY/MM/DD HH:mm:ss') }}</td>
                    <td class="tdMethodStyle">{{ line.method }}</td>
                    <td class="tdUrlStyle">{{ line.url }}</td>
                    <td v-if="line.ip in ip_list" class="tdIpStyle">
                        <a :href="ip_list[line.ip]" target="_blank">{{ line.ip }}</a>
                    </td>
                    <td v-else class="tdIpStyle">{{ line.ip }}</td>
                    <td class="tdStatusStyle">{{ line.status }}</td>
                </tr>
            </table>
        </div>
        <div v-if="lines?.length >= 90">
            <div class="flex justify-center">
                <div class="mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer" onclick="window.open('https://owlsearch.games', '_blank')" >
                    <img src="https://owlsearch.games/images/logo/owl56.jpg" class="absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95" />
                    <span class="absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900">Owl&nbsp;Search&nbsp;Games</span>
                    <span class="absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800">&#10149;&nbsp;Turbospeed&nbsp;your&nbsp;Brain!</span>
                    <span class="absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800">&#10149;&nbsp;Play&nbsp;without&nbsp;Ads!</span>
                    <button class="absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg" >&#10148;&nbsp;Play&nbsp;Now!</button>
                    <div class="motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg" />
                </div>
            </div>
            <div class="flex justify-center">
                <div class="mt-0.5 mb-4 font-black text-xs uppercase">Please support our sponsor.</div>
            </div>
        </div>
        <div v-if="lines?.length >= 90" class="flex justify-center">
            <table class="tableStyle">
                <tr v-for="line in lines3" :key="line.id" :class="{ 'bg-stone-200': line.id % 6 > 2 }">
                    <td class="tdDatetimeStyle">{{ dayjs(line.datetime + '+00:00').format('YYYY/MM/DD HH:mm:ss') }}</td>
                    <td class="tdMethodStyle">{{ line.method }}</td>
                    <td class="tdUrlStyle">{{ line.url }}</td>
                    <td v-if="line.ip in ip_list" class="tdIpStyle">
                        <a :href="ip_list[line.ip]" target="_blank">{{ line.ip }}</a>
                    </td>
                    <td v-else class="tdIpStyle">{{ line.ip }}</td>
                    <td class="tdStatusStyle">{{ line.status }}</td>
                </tr>
            </table>
        </div>
        <div v-if="lines?.length < 60">
            <div class="flex justify-center">
                <div class="mt-5 relative z-0 w-[640px] h-20 bg-lime-100 border-2 border-lime-600 rounded-md shadow-md cursor-pointer" onclick="window.open('https://owlsearch.games', '_blank')" >
                    <img src="https://owlsearch.games/images/logo/owl56.jpg" class="absolute z-10 top-[9px] left-5 mt-[1px] p-[2px] block h-14 border-2 border-emerald-950 bg-black rounded-2xl opacity-95" />
                    <span class="absolute z-10 left-28 top-[3px] text-2xl font-bold font-sans text-green-900">Owl&nbsp;Search&nbsp;Games</span>
                    <span class="absolute z-10 left-28 top-[30px] text-base font-bold font-sans text-green-800">&#10149;&nbsp;Turbospeed&nbsp;your&nbsp;Brain!</span>
                    <span class="absolute z-10 left-28 top-[47px] text-base font-bold font-sans text-green-800">&#10149;&nbsp;Play&nbsp;without&nbsp;Ads!</span>
                    <button class="absolute left-[410px] top-[17px] z-20 px-9 py-2 bg-green-600 border border-green-900 rounded-lg text-lime-200 font-bold font-sans shadow-lg" >&#10148;&nbsp;Play&nbsp;Now!</button>
                    <div class="motion-safe:animate-ping absolute z-10 left-[451px] top-[21px] w-[113px] h-[34px] bg-red-600/100 rounded-lg" />
                </div>
            </div>
            <div class="flex justify-center">
                <div class="mt-0.5 font-black text-xs uppercase">Please support our sponsor.</div>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="justify-center">
                <h4 class="mt-3 font-bold text-xl">Statistics:<span class="ml-2 italic text-sm font-semibold">(updated hourly)</span></h4>
                <p class="font-normal"><i>category_count:</i> <span class="ml-0.5 font-semibold">{{ statistics?.category_count }}</span></p>
                <p class="font-normal"><i>book_count:</i> <span class="ml-0.5 font-semibold">{{ statistics?.book_count }}</span></p>
                <p class="font-normal"><i>exemplar_count:</i> <span class="ml-0.5 font-semibold">{{ statistics?.exemplar_count }}</span></p>
                <p class="font-normal"><i>database_size:</i> <span class="ml-0.5 font-semibold">{{ statistics?.mysql_count }}</span></p>
            </div>
        </div>
        <div class="flex justify-center text-sm font-medium italic">This site is best wiewed on a larger screen, like either from a laptop or desktop.</div>
        <div class="h-16" />
    </div>
</template>
<style lang="postcss" scoped>
.tableStyle {
    @apply bg-stone-50 border border-stone-500;
}
.tdDatetimeStyle {
    @apply px-1 whitespace-nowrap;
}
.tdIpStyle {
    @apply px-1 w-40 border-l border-stone-500;
}
.tdMethodStyle {
    @apply px-1 w-24 border-l border-stone-500;
}
.tdStatusStyle {
    @apply px-1 w-16 border-l border-stone-500;
}
.tdUrlStyle {
    @apply px-1 w-[570px] border-l border-stone-500;
}
.thStyle {
    @apply p-1 bg-stone-300 font-semibold border-l border-b border-stone-500;
}
</style>