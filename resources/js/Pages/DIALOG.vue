<script setup>
import AppLayout from '@/js/Layouts/AppLayout.vue';
import {onBeforeUnmount, onMounted, ref} from "vue";
import * as dtTailwind from '@/js/Plugins/dataTables.tailwindcss';
import {downloadFile} from "@/js/Plugins/digismart";
import BasePageHeading from "@/js/Components/Base/BasePageHeading.vue";
import '@/css/dataTables.scss'
import DTableLoading from "@/js/Components/DTableLoading.vue";
import DCardHeadFoot from "@/js/Components/DCard/DCardHeadFoot.vue";
import DCard from "@/js/Components/DCard/DCard.vue";
import DCardBody from "@/js/Components/DCard/DCardBody.vue";
import SimpleBar from "simplebar";
import {
  Combobox,
  ComboboxInput,
  ComboboxOption,
  ComboboxOptions, Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild,
} from "@headlessui/vue";
import {debounce} from "lodash";
import DModal from "@/js/Components/DModal/DModal.vue";
import DDialog from "@/js/Components/DDialog/OLDDDialog.vue";

let table
let tableFooter
let dTable
let search = ref(null)
let searchTerm = ref('')
let printTitle = ref('')
let tableLoading = ref(false)

const isOpen = ref(false)
function setIsOpen(value) {
  console.log(isOpen.value)
  isOpen.value = value
}


const binLocations = ref([])
const query = ref('')

function clearSearch() {
  searchTerm.value = ''
  search.value.el.focus()
}
function performSearch() {
  dTable.ajax.reload(() => {
    subtitle.value = 'Bin: ' + searchTerm.value.toUpperCase()
    printTitle.value = searchTerm.value.toUpperCase()
    tableFooter.val('').change()
    dTable.columns([1, 2, 3]).every(function () {
      var column = this;
      var select = jQuery('<select class="text-xs w-full block border p-0 rounded-lg border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:focus:border-blue-500"><option value=""></option></select>')
          .appendTo(jQuery(column.footer()).empty())
          .on('change', function () {
            const val = jQuery.fn.dataTable.util.escapeRegex(
                jQuery(this).val()
            );
            column.search(val ? '^' + val + '$' : '', true, false).draw();
          });
      column.data().unique().sort().each(function (d) {
        select.append('<option value="' + d + '">' + d + '</option>')
      });
    });
    dTable.columns().search('').draw()

    dTable.columns.adjust().draw()
    document.getElementsByClassName('dataTables_scrollFootInner')[0].style.removeProperty('padding-right')
    clearSearch()
  })
}

function getFileName() {
  const d = new Date()
  return 'InventoryByBin_'+printTitle.value+'_' + d.getTime()
}

onMounted(async () => {
  search.value.el.focus()
  table = $('#inventory')
  tableFooter = jQuery('#inventory tfoot select')
  dTable = table.DataTable({
    ajax: function (data, callback) {
      tableLoading.value = true
      axios.patch(route('inventory.view.update', {view: 'bin'}), {search: searchTerm.value})
          .then(response => {
            callback(response.data)
            tableLoading.value = false
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
      //info: '_TOTAL_ records',
      //infoFiltered: '(out of _MAX_ records)'
    },
    drawCallback: () => {
      const emptyClass = ['text-lg','font-semibold']
      $('.dataTables_info').appendTo('#footer_start')
      if(dTable) {
        if(dTable.rows().count() === 0)
          document.getElementsByClassName('dataTables_empty')[0].classList.add(...emptyClass)
      }

    },
    columns: [
      {data: 'bin_location', className: 'text-nowrap'},
      {data: 'product_code', className: 'text-nowrap'},
      {data: 'manufacturer', className: 'text-nowrap'},
      {data: 'warehouse_code', className: 'text-nowrap text-center'},
      {data: 'quantity_on_hand', className: 'text-nowrap text-center'},
      {data: 'quantity_allocated', className: 'text-nowrap text-center'},
      {data: 'quantity_available', className: 'text-nowrap text-center'},
      {data: 'fifo_lifo', className: 'text-nowrap text-center'},
      {data: 'last_shipment', className: 'text-nowrap text-center'},
      {data: 'last_adjustment', className: 'text-nowrap text-center'}
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
          className: 'inline-flex justify-center items-center space-x-2 border font-semibold rounded-l-md px-2 py-1 leading-5 text-xs border-gray-200 bg-white text-gray-500 hover:z-1 hover:border-gray-300 hover:text-digicomm-700 hover:shadow-sm focus:z-1 focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:z-1 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-digicomm-700 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700',
          title: '',
          titleAttr: 'Export to Excel',
          text: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="h-4 fill-current"><path d="M48 448V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/></svg>',
          action: () => {
            downloadFile(route('inventory.view.create', {view: 'bin', bin: printTitle.value}), getFileName())
                .catch(response => {
                  Swal.fire(response.message)
                })
          }
        },
        {
          extend: 'print',
          className: '-ml-px inline-flex justify-center items-center space-x-2 border font-semibold px-2 py-1 leading-5 text-xs border-gray-200 bg-white text-gray-500 hover:z-1 hover:border-gray-300 hover:text-digicomm-700 hover:shadow-sm focus:z-1 focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:z-1 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700',
          title: function() {
            return 'Bin Inventory: ' + printTitle.value
          },
          titleAttr: 'Print',
          text: '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" class="h-4 fill-current"><path d="M112 160V64c0-8.8 7.2-16 16-16H357.5c4.2 0 8.3 1.7 11.3 4.7l26.5 26.5c3 3 4.7 7.1 4.7 11.3V160h48V90.5c0-17-6.7-33.3-18.7-45.3L402.7 18.7C390.7 6.7 374.5 0 357.5 0H128C92.7 0 64 28.7 64 64v96h48zm16 208H384v96H128V368zm-16-48c-17.7 0-32 14.3-32 32H48V256c0-8.8 7.2-16 16-16H448c8.8 0 16 7.2 16 16v96H432c0-17.7-14.3-32-32-32H112zm320 80h48c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64V368c0 17.7 14.3 32 32 32H80v80c0 17.7 14.3 32 32 32H400c17.7 0 32-14.3 32-32V400z"/></svg>',
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
          className: '-ml-px inline-flex justify-center items-center space-x-2 border font-semibold rounded-r-md px-2 py-1 leading-5 text-xs border-gray-200 bg-white text-gray-500 hover:z-1 hover:border-gray-300 hover:text-digicomm-700 hover:shadow-sm focus:z-1 focus:ring focus:ring-gray-300 focus:ring-opacity-25 active:z-1 active:border-gray-200 active:shadow-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:text-gray-200 dark:focus:ring-gray-600 dark:focus:ring-opacity-40 dark:active:border-gray-700',
          text: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-4 fill-current"><path d="M496 200c0 13.3-10.7 24-24 24h0H360 328c-13.3 0-24-10.7-24-24s10.7-24 24-24h32 54.1l-52.1-52.1C333.8 95.8 295.7 80 256 80c-72.7 0-135.2 44.1-162 107.1c-5.2 12.2-19.3 17.9-31.5 12.7s-17.9-19.3-12.7-31.5C83.9 88.2 163.4 32 256 32c52.5 0 102.8 20.8 139.9 57.9L448 142.1V88l0-.4V56c0-13.3 10.7-24 24-24s24 10.7 24 24V200zM40 288H152c13.3 0 24 10.7 24 24s-10.7 24-24 24H97.9l52.1 52.1C178.2 416.2 216.3 432 256 432c72.6 0 135-43.9 161.9-106.8c5.2-12.2 19.3-17.8 31.5-12.6s17.8 19.3 12.6 31.5C427.8 424 348.5 480 256 480c-52.5 0-102.8-20.8-139.9-57.9L64 369.9V424c0 13.3-10.7 24-24 24s-24-10.7-24-24V312c0-13.3 10.7-24 24-24z"/></svg>',
          titleAttr: 'Refresh Data',
          action: () => {
            searchTerm.value = printTitle.value
            performSearch()
          }
        }
      ],
    },
    initComplete: function () {
      this.api().columns([1, 2, 3]).every(function () {
        var column = this;
        var select = jQuery('<select class="text-xs p-0 w-full"><option value=""></option></select>')
            .appendTo(jQuery(column.footer()).empty())
            .on('change', function () {
              const val = jQuery.fn.dataTable.util.escapeRegex(
                  jQuery(this).val()
              );
              column.search(val ? '^' + val + '$' : '', true, false).draw();
            });
        column.data().unique().sort().each(function (d) {
          select.append('<option value="' + d + '">' + d + '</option>')
        });
      });
      new SimpleBar(document.getElementsByClassName('dataTables_scrollBody')[0])
    },
  })
  dTable.buttons().container().addClass('inline-flex')
  dTable.buttons().container().prependTo('#tableActions');

})
function closed(event) {
  console.log(event)
}

function closeModal() {
  isOpen.value = false
}
function openModal() {
  isOpen.value = true
}
function searchHandler(event) {
  query.value = event.target.value
  searchDebounceHandler(event)
}

const searchDebounceHandler = debounce(event => {
  axios.post('/autocomplete/binlocation', {search: event.target.value})
      .then((response) => {
        binLocations.value = response.data
      })
}, 500)

onBeforeUnmount(() => {
  searchDebounceHandler.cancel()
})
let title = ref('Inventory View')
let subtitle = ref('Bin: ')
</script>

<template>

  <AppLayout :title="title" layout="">
    <BasePageHeading :title="title" :subtitle="subtitle"></BasePageHeading>
    <div class="mx-auto p-4 lg:p-4 w-full">
      <!--

      ADD YOUR MAIN CONTENT BELOW

      -->
      <DCard container-class="relative" show-overflow>
        <DCardHeadFoot p="2" table-actions additional-container-class="rounded-t-lg">
          <div>
            <div class="relative w-full">
              <div class="absolute inset-y-0 left-0 w-7 my-px ml-px flex items-center justify-center pointer-events-none rounded-m text-gray-500">
                <svg class="hi-mini hi-magnifying-glass inline-block w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd"/></svg>
              </div>
              <Combobox v-model="searchTerm" @update:model-value="performSearch" nullable>
                <ComboboxInput @change="searchHandler" ref="search" placeholder="Search" class="w-full block border placeholder-gray-400 pl-7 pr-3 py-0 leading-6 text-xs rounded-md border-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:focus:border-blue-500 uppercase" />
                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-out"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                <ComboboxOptions class="z-1 absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                  <ComboboxOption v-if="query.includes('%')" :value="query" class="ui-active:bg-digicomm-500/25">
                    Wildcard "{{ query }}"
                  </ComboboxOption>
                  <ComboboxOption v-for="binLocation in binLocations" :key="binLocation.id" :value="binLocation.value" className="ui-active:bg-digicomm-500/25" v-if="query.includes('%')" disabled> {{ binLocation.label }}</ComboboxOption>
                  <ComboboxOption v-for="binLocation in binLocations" :key="binLocation.id" :value="binLocation.value" className="ui-active:bg-digicomm-500/25" v-else> {{ binLocation.label }}</ComboboxOption>
                </ComboboxOptions>
                </transition>
              </Combobox>

            </div>
          </div>
          <div class="flex text-sm" id="header_middle">
          </div>
        </DCardHeadFoot>
        <DCardBody p="0">
          <table
              id="inventory"
              class="max-w-99p text-xs">
            <thead>
            <tr>
              <th>Bin Location</th>
              <th>Product Code</th>
              <th>Manufacturer</th>
              <th>WHS</th>
              <th>On Hand</th>
              <th>Allocated</th>
              <th>Available</th>
              <th>FIFO</th>
              <th>Ship</th>
              <th>Adj</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
            </tfoot>
          </table>
        </DCardBody>
        <DCardHeadFoot p-x="2" p-y="1" additional-container-class="rounded-b-lg">
          <div class="flex text-xs" id="footer_start">
          </div>
          <div class="flex text-sm" id="footer_center">

          </div>
          <div class="flex items-center gap-2" id="footer_end">

          </div>
        </DCardHeadFoot>
        <DTableLoading loading-message="Refreshing Inventory Variance Report" v-if="tableLoading"/>
      </DCard>

      <DDialog no-close-on-esc size="2xl" position="center">
        <template #title>Test Title</template>

      </DDialog>






      <!--

      ADD YOUR MAIN CONTENT ABOVE

      -->
    </div>
    <!-- END Page Section -->

  </AppLayout>
</template>
