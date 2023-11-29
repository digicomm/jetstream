<script setup>
import AppLayout from '@/js/Layouts/AppLayout.vue';
import {computed, onBeforeMount, onBeforeUnmount, ref} from "vue";
import BasePageHeading from "@/js/Components/Base/BasePageHeading.vue";
import {router, usePage} from "@inertiajs/vue3";
import {Pie} from "vue-chartjs";
import {Chart, registerables} from "chart.js";
import ChartDataLabels from "chartjs-plugin-datalabels";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import colors from "tailwindcss/colors";
import DCardHeadFoot from "@/js/Components/DCard/DCardHeadFoot.vue";
import DCard from "@/js/Components/DCard/DCard.vue";
import DRow from "@/js/Components/DRow.vue";
import DCardBody from "@/js/Components/DCard/DCardBody.vue";

const page = usePage();

Chart.register(...registerables);
Chart.defaults.color = "#818d96";
Chart.defaults.scale.grid.lineWidth = 0;
Chart.defaults.scale.beginAtZero = true;
Chart.defaults.datasets.bar.maxBarThickness = 45;
Chart.defaults.elements.bar.borderRadius = 4;
Chart.defaults.elements.bar.borderSkipped = false;
Chart.defaults.elements.point.radius = 0;
Chart.defaults.elements.point.hoverRadius = 0;
Chart.defaults.plugins.tooltip.radius = 3;
Chart.defaults.plugins.legend.labels.boxWidth = 10;

const colorGreen = convertHex(colors.green["400"])
const colorGreenTrans = convertHex(colors.green["400"], .75)
const colorOrange = convertHex(colors.orange["400"])
const colorOrangeTrans = convertHex(colors.orange["400"], .75)
const colorYellow = convertHex(colors.yellow["400"])
const colorYellowTrans = convertHex(colors.yellow["400"], .75)
const colorBlue = convertHex(colors.blue["400"])
const colorBlueTrans = convertHex(colors.blue["400"], .75)


const chartPlugins = [ChartDataLabels]
const myStyles = computed(() => {
  return {
    height: `${300}px`,
    position: 'relative'
  }
})


const openOrdersData = computed(() => ({
  labels: [
    'Past',
    '-2 Weeks',
    '+2 Weeks',
    'Future'
  ],
  datasets: [{
    data: page.props.data.openOrders,
    backgroundColor: [
      colorOrangeTrans,
      colorYellowTrans,
      colorGreenTrans,
      colorBlueTrans,
    ],
    hoverBackgroundColor: [
      colorOrange,
      colorYellow,
      colorGreen,
      colorBlue,
    ],
    borderWidth: 0
  }]
}))
const openOrdersOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top'
    },
    title: {
      display: false,
      text: 'Open Orders',
      font: {
        family: "'Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial'",
        size: 20,
        weight: 700,

      },
      color: '#36474d'
    },
    datalabels: {
      color: 'rgb(80, 80, 80)',
      anchor: 'end',
      align: 'start',
      offset: 10,
      font: {
        weight: 'bold'
      }
    },
    tooltip: {
      callbacks: {
        label: addDollar
      }
    }
  },
})

const ordersAllocatedData = computed(() => ({
  labels: [
    'Complete',
    'Partial',
    'Unallocated'
  ],
  datasets: [{
    data: page.props.data.ordersAllocated,
    backgroundColor: [
      colorGreenTrans,
      colorYellowTrans,
      colorBlueTrans,
    ],
    hoverBackgroundColor: [
      colorGreen,
      colorYellow,
      colorBlue,
    ],
    borderWidth: 0
  }]
}))
const ordersAllocatedOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top'
    },
    title: {
      display: false,
      text: 'Orders Allocated',
      font: {
        family: "'Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial'",
        size: 20,
        weight: 700,

      },
      color: '#36474d'
    },
    datalabels: {
      color: 'rgb(80, 80, 80)',
      anchor: 'end',
      align: 'start',
      offset: 10,
      font: {
        weight: 'bold'
      }
    }
  },
});

const ordersPrintedData = computed(() => ({
  labels: [
    'Printed',
    'Unprinted'
  ],
  datasets: [{
    data: page.props.data.ordersPrinted,
    backgroundColor: [
      colorGreenTrans,
      colorYellowTrans
    ],
    hoverBackgroundColor: [
      colorGreen,
      colorYellow
    ],
    borderWidth: 0
  }]
}))
const ordersPrintedOptions = ref({
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: true,
      position: 'top'
    },
    title: {
      display: false,
      text: 'Orders Printed',
      font: {
        family: "'Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial'",
        size: 20,
        weight: 700,

      },
      color: '#36474d'
    },
    datalabels: {
      color: 'rgb(80, 80, 80)',
      anchor: 'end',
      align: 'start',
      offset: 10,
      font: {
        weight: 'bold'
      }
    }
  },
})

const updateTimer = setInterval(() => {
  updateData()
}, 60000)

onBeforeMount(() => {
  updateData()
})
onBeforeUnmount(() => {
  clearInterval(updateTimer)
})

function updateData() {
  router.post('/', {
    _method: 'get'
  }, {
    only: ['data']
  })
}

function convertHex(hexCode, opacity = 1) {
  var hex = hexCode.replace('#', '');

  if (hex.length === 3) {
    hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
  }

  var r = parseInt(hex.substring(0, 2), 16),
      g = parseInt(hex.substring(2, 4), 16),
      b = parseInt(hex.substring(4, 6), 16);

  /* Backward compatibility for whole number based opacity values. */
  if (opacity > 1 && opacity <= 100) {
    opacity = opacity / 100;
  }

  return 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';
}

function addDollar(tooltipItem) {
  switch (tooltipItem.dataIndex) {
    case 3:
      return [tooltipItem.label + ': ' + tooltipItem.parsed + ' Orders', page.props.data.future.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0
      })]
    case 2:
      return [tooltipItem.label + ': ' + tooltipItem.parsed + ' Orders', page.props.data.twoWeeksFuture.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0
      })]
    case 1:
      return [tooltipItem.label + ': ' + tooltipItem.parsed + ' Orders', page.props.data.twoWeeksPast.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0
      })]
    default:
      return [tooltipItem.label + ': ' + tooltipItem.parsed + ' Orders', page.props.data.past.toLocaleString("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0
      })]
  }
}

let title = ref('Dashboard')
</script>

<template>

  <AppLayout :title="title" layout="">
    <BasePageHeading :subtitle="`Welcome ${page.props.auth.user.given_name}, everything looks great.`" :title="title"/>

    <div class="mx-auto p-4 lg:p-4 w-full">
      <!--      ADD YOUR MAIN CONTENT BELOW      -->
      <DRow gap-x="8" md="3" sm="2">

        <!-- Open Orders Card -->
        <DCard>
          <DCardHeadFoot title="Open Orders" p-x="3" p-y="2"/>
          <DCardBody p="2">
            <Pie :data="openOrdersData" :options="openOrdersOptions" :plugins="chartPlugins" :style="myStyles"/>
          </DCardBody>
          <DCardHeadFoot container-class="text-xxs text-center text-gray-600 dark:text-gray-400" p="2">
            <div v-if="page.props.user.permissions.includes('view dollars')">
              <font-awesome-icon :icon="['fas','square']" class="chart-label-orange"></font-awesome-icon>
              {{
                page.props.data.past.toLocaleString("en-US", {
                  style: "currency",
                  currency: "USD",
                  maximumFractionDigits: 0
                })
              }} &nbsp;<font-awesome-icon :icon="['fas','square']" class="chart-label-yellow"></font-awesome-icon>
              {{
                page.props.data.twoWeeksPast.toLocaleString("en-US", {
                  style: "currency",
                  currency: "USD",
                  maximumFractionDigits: 0
                })
              }} &nbsp;<font-awesome-icon :icon="['fas','square']" class="chart-label-green"></font-awesome-icon>
              {{
                page.props.data.twoWeeksFuture.toLocaleString("en-US", {
                  style: "currency",
                  currency: "USD",
                  maximumFractionDigits: 0
                })
              }} &nbsp;<font-awesome-icon :icon="['fas','square']" class="chart-label-blue"></font-awesome-icon>
              {{
                page.props.data.future.toLocaleString("en-US", {
                  style: "currency",
                  currency: "USD",
                  maximumFractionDigits: 0
                })
              }}
            </div>
            <div v-else>&nbsp;</div>
          </DCardHeadFoot>
        </DCard>
        <!-- END Open Orders Card -->


        <!-- Orders Allocated Card -->
        <DCard>
          <DCardHeadFoot title="Orders Allocated" p-x="3" p-y="2"/>
          <DCardBody p="2">
            <Pie :data="ordersAllocatedData" :options="ordersAllocatedOptions" :plugins="chartPlugins" :style="myStyles"/>
          </DCardBody>
          <DCardHeadFoot container-class="text-xxs text-center text-gray-600 dark:text-gray-400" p="2">
            <font-awesome-icon :icon="['fas','square']" class="chart-label-green"></font-awesome-icon>
            {{ page.props.data.ordersAllocated[0] }} &nbsp;<font-awesome-icon :icon="['fas','square']"
                                                                              class="chart-label-yellow"></font-awesome-icon>
            {{ page.props.data.ordersAllocated[1] }} &nbsp;<font-awesome-icon :icon="['fas','square']"
                                                                              class="chart-label-blue"></font-awesome-icon>
            {{ page.props.data.ordersAllocated[2] }}
          </DCardHeadFoot>
        </DCard>
        <!-- END Orders Allocated Card -->

        <!-- Orders Printed Card -->
        <DCard>
          <DCardHeadFoot title="Orders Printed" p-x="3" p-y="2"/>
          <DCardBody p="2">
            <Pie :data="ordersPrintedData" :options="ordersPrintedOptions" :plugins="chartPlugins" :style="myStyles"/>
          </DCardBody>
          <DCardHeadFoot container-class="text-xxs text-center text-gray-600 dark:text-gray-400" p="2">
            <div v-if="page.props.user.permissions.includes('view dollars')">
              <font-awesome-icon :icon="['fas','square']" class="chart-label-green"></font-awesome-icon>
              {{
                page.props.data.printed.toLocaleString("en-US", {
                  style: "currency",
                  currency: "USD",
                  maximumFractionDigits: 0
                })
              }} &nbsp;<font-awesome-icon :icon="['fas','square']" class="chart-label-yellow"></font-awesome-icon>
              {{
                page.props.data.unprinted.toLocaleString("en-US", {
                  style: "currency",
                  currency: "USD",
                  maximumFractionDigits: 0
                })
              }}
            </div>
            <div v-else>&nbsp;</div>
          </DCardHeadFoot>
        </DCard>
        <!-- END Orders Printed Card -->

        <!-- Orders Shipped Card -->
        <DCard container-class="self-start md:col-end-4">
          <DCardHeadFoot title="Orders Shipped" p-x="3" p-y="2"/>
          <DCardBody container-class="flex" p="2">
            <dl class="flex flex-col grow space-y-1 py-5 text-center">
              <dt class="text-3xl font-bold">
                {{ page.props.data.postedShipments.posted }}
              </dt>
              <dd class="font-medium text-gray-500 dark:text-gray-400">
                Posted
              </dd>
            </dl>
            <dl class="flex flex-col grow space-y-1 py-5 text-center">
              <dt class="text-3xl font-bold">
                {{ page.props.data.postedShipments.cancelled }}
              </dt>
              <dd class="font-medium text-gray-500 dark:text-gray-400">
                Cancelled
              </dd>
            </dl>
          </DCardBody>
          <DCardHeadFoot container-class="text-xxs text-center text-gray-600 dark:text-gray-400" p="2">
            <div v-if="page.props.user.permissions.includes('view dollars')"><strong>Invoiced</strong> {{
                page.props.data.invoiced.toLocaleString("en-US", {
                  style: "currency",
                  currency: "USD",
                  maximumFractionDigits: 0
                })
              }}
            </div>
            <div v-else>&nbsp;</div>
          </DCardHeadFoot>
        </DCard>
        <!-- END Orders Shipped Card -->
      </DRow>

      <!-- ADD YOUR MAIN CONTENT ABOVE -->
    </div>

  </AppLayout>
</template>
