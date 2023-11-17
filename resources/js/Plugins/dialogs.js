export const swalNotification = Swal.mixin({
    buttonsStyling: false,
    customClass: {
        popup: 'rounded-lg',
        confirmButton: 'px-4 py-2 border-digicomm-700 bg-digicomm-700 text-white hover:text-white hover:bg-digicomm-600 hover:border-digicomm-600 focus:ring focus:ring-digicomm-400 focus:ring-opacity-50 active:bg-digicomm-700 active:border-digicomm-700 dark:focus:ring-digicomm-400 dark:focus:ring-opacity-90 text-xs uppercase rounded-md inline-flex justify-center items-center space-x-2 border font-semibold transition ease-in-out duration-150'
    },
    showCloseButton: true,
})