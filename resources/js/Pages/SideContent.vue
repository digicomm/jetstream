<script setup>
import AppLayout from '@/js/Layouts/AppLayout.vue';
import {onMounted, ref} from "vue";
import * as dtTailwind from '@/js/Plugins/dataTables.tailwindcss';
import {readCookie} from "@/js/Plugins/digismart";
import BasePageHeading from "@/js/Components/Base/BasePageHeading.vue";
import {notify} from 'notiwind';
import '../../css/dataTables.scss'

let table
let dTable
let search = ref(null)
let searchTerm = ref('')
let tableUrl = ref('https://www.godigismart.net/api/v2/inventory/variancereport')
let tableMethod = ref('GET')
const mobileSideContentOpen = ref(false)
function clearSearch() {
    searchTerm.value = ''
    dTable.search(searchTerm.value).draw()

}
function performSearch() {
  dTable.search(searchTerm.value).draw()
}

function getFileName() {
  const d = new Date()
  return 'VarianceReport_' + d.getTime()
}
let rows = ref([])

onMounted(async () => {
  table = $('#variance')
  dTable = table.DataTable({
    ajax: function (data, callback, settings) {
      jQuery.ajax({
        headers: {
          'X-XSRF-TOKEN': decodeURIComponent(readCookie('XSRF-TOKEN' + (!import.meta.env.VITE_SESSION_TOKEN ? null : '-' + import.meta.env.VITE_SESSION_TOKEN)))
        },
        type: tableMethod.value,
        url: tableUrl.value,
        data: data,
        success: callback,
        xhrFields: {
          withCredentials: true
        }
      })
    },
    dom: "<'row'<'col-span-10't>>"
        + "<'row'<'col-span-10 self-center smaller'iB>>",
    paging: false,
    processing: true,
    scrollY: '60vh',
    scrollX: true,
    scrollCollapse: true,
    language: {
      processing: '<span class="dtLoading">Loading Records</span>',
      info: '_TOTAL_ records',
      infoFiltered: '(out of _MAX_ records)'
    },
    initComplete: (settings, json) => {
      $('.dataTables_info').appendTo('#header_middle')
    },
    columns: [
      {
        data: 'product_code'
      },
      {
        data: 'warehouse_code', class: 'text-center', searchable: false
      },
      {
        data: 'sage', class: 'text-center', searchable: false
      },
      {
        data: 'wms', class: 'text-center', searchable: false
      },
      {
        data: 'variance', class: 'text-center', searchable: false
      }
    ],
    buttons: {
      dom: {
        buttonLiner: {
          tag: null,
        },
        button: {
          className: 'btn-block-option'
        }
      },
      buttons: [
        {
          extend: 'excel',
          className: 'inline-flex justify-center items-center space-x-2 border font-semibold rounded-l-md px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-800 hover:z-1 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:z-1 focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:z-1 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700',
          title: '',
          titleAttr: 'Export to Excel',
          filename: () => {
            return getFileName()
          },
          text: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="h-4 fill-current"><path d="M48 448V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/></svg>',
          autoFilter: true,
          sheetName: 'Variance Report',
          excelStyles: [
            {
              template: "green_medium",
            },
            {
              cells: "B1:E",
              style: {
                alignment: {
                  vertical: "center",
                  horizontal: "center",
                  wrapText: false,
                }
              }
            },
            {
              cells: "sA1:B-0",
              style: {
                numFmt: "@"
              }
            },
            {
              cells: "sC1:E-0",
              style: {
                numFmt: "#,##0;[Red] -#,##0;0;@"
              }
            },
          ],
        },
        {
          extend: 'print',
          className: '-ml-px inline-flex justify-center items-center space-x-2 border font-semibold px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-200 hover:z-1 hover:border-gray-300 hover:text-gray-200 hover:shadow-sm focus:z-1 focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:z-1 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700',
          title: 'Variance Report',
          titleAttr: 'Print',
          text: '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="fill-current"><path d="M112 160V64c0-8.8 7.2-16 16-16H357.5c4.2 0 8.3 1.7 11.3 4.7l26.5 26.5c3 3 4.7 7.1 4.7 11.3V160h48V90.5c0-17-6.7-33.3-18.7-45.3L402.7 18.7C390.7 6.7 374.5 0 357.5 0H128C92.7 0 64 28.7 64 64v96h48zm16 208H384v96H128V368zm-16-48c-17.7 0-32 14.3-32 32H48V256c0-8.8 7.2-16 16-16H448c8.8 0 16 7.2 16 16v96H432c0-17.7-14.3-32-32-32H112zm320 80h48c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64V368c0 17.7 14.3 32 32 32H80v80c0 17.7 14.3 32 32 32H400c17.7 0 32-14.3 32-32V400z"/></svg>',
          customize: function (win) {
            const css = '@page { size: landscape; } body { -webkit-print-color-adjust:exact !important;\n' +
                '  print-color-adjust:exact !important;}'
            const head = win.document.head || win.document.getElementsByTagName('head')[0]
            const style = win.document.createElement('style')

            let title = win.document.getElementsByTagName('h1')[0]
            title.classList.add('text-xl','font-semibold','text-center')
            let table = win.document.getElementsByTagName('table')[0]
            table.classList.add('text-xs')
            let rows = win.document.getElementsByTagName('tr')
            let cells = win.document.getElementsByTagName('td')

            for(let i = 0; i < rows.length; i++) {
              rows[i].classList.add('even:bg-gray-100','border-b','border-gray-200')
            }
            for(let i = 0; i < cells.length; i++) {
              cells[i].classList.add('border','border-gray-200', 'leading-3')
            }

            style.type = 'text/css';
            style.media = 'print';
            if (style.styleSheet) {
              style.styleSheet.cssText = css;
            } else {
              style.appendChild(win.document.createTextNode(css));
            }
            head.appendChild(style);
          }
        },
        {
          className: '-ml-px inline-flex justify-center items-center space-x-2 border font-semibold rounded-r-md px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-800 hover:z-1 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:z-1 focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:z-1 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700',
          text: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-4 fill-current"><path d="M496 200c0 13.3-10.7 24-24 24h0H360 328c-13.3 0-24-10.7-24-24s10.7-24 24-24h32 54.1l-52.1-52.1C333.8 95.8 295.7 80 256 80c-72.7 0-135.2 44.1-162 107.1c-5.2 12.2-19.3 17.9-31.5 12.7s-17.9-19.3-12.7-31.5C83.9 88.2 163.4 32 256 32c52.5 0 102.8 20.8 139.9 57.9L448 142.1V88l0-.4V56c0-13.3 10.7-24 24-24s24 10.7 24 24V200zM40 288H152c13.3 0 24 10.7 24 24s-10.7 24-24 24H97.9l52.1 52.1C178.2 416.2 216.3 432 256 432c72.6 0 135-43.9 161.9-106.8c5.2-12.2 19.3-17.8 31.5-12.6s17.8 19.3 12.6 31.5C427.8 424 348.5 480 256 480c-52.5 0-102.8-20.8-139.9-57.9L64 369.9V424c0 13.3-10.7 24-24 24s-24-10.7-24-24V312c0-13.3 10.7-24 24-24z"/></svg>',
          titleAttr: 'Refresh Data',
          action: () => {
            tableMethod.value = 'POST'
            axios.get('https://www.godigismart.net/api/v2/inventory/variancereport')
                .then(response => {
                  rows.value = response.data
                })
                .finally(() => {
                  dTable.clear().draw()
                  //dTable.rows.add([rows.value])
                })

            /*
            dTable.ajax.reload(() => {
              window.alert('Test')
            })*/
          }
        }
      ],
    },

  })
  dTable.buttons().container().addClass('inline-flex')
  dTable.buttons().container().prependTo('#varianceOptions');

})
let title = ref('Variance Report')
</script>

<template>

    <AppLayout :title="title" layout="right">

      <!-- Toggle Side Content -->
      <div class="lg:hidden w-full p-4 lg:p-8 bg-gray-50 dark:bg-gray-800 dark:bg-opacity-25">
        <button
            @click="mobileSideContentOpen = !mobileSideContentOpen"
            type="button"
            class="w-full inline-flex justify-center items-center space-x-2 border font-semibold rounded-lg px-3 py-2 leading-5 text-sm border-gray-200 bg-white text-gray-800 hover:border-gray-300 hover:text-gray-900 hover:shadow-sm focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700"
        >
          Toggle Side Content
        </button>
      </div>
      <!-- END Toggle Side Content -->

      <!-- Side Content -->
      <div
          class="flex-none lg:flex flex-col w-full lg:w-80 xl:w-96 p-4 lg:p-8 bg-gray-50 dark:bg-gray-800 dark:bg-opacity-25"
          :class="{
          'hidden': !mobileSideContentOpen,
        }"
      >
        <!-- Placeholder -->
        <div class="flex-auto flex items-center justify-center rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 text-gray-400 py-64 dark:bg-gray-800 dark:border-gray-700">Side Content</div>
      </div>
      <!-- END Side Content -->

      <!-- Page Section -->
      <div class="flex-grow flex flex-col max-w-10xl mx-auto px-8 py-4 w-full">
        <!--

        ADD YOUR MAIN CONTENT BELOW

        -->
        <BasePageHeading :title="title"></BasePageHeading>

        <!-- Placeholder -->
        <div class="flex items-center justify-center rounded-xl bg-gray-50 border-2 border-dashed border-gray-200 text-gray-400 py-64 dark:bg-gray-800 dark:border-gray-700">Main Content</div>


        <!--

        ADD YOUR MAIN CONTENT ABOVE

        -->
      </div>
      <!-- END Page Section -->

    </AppLayout>
</template>
