export default {
    main: [
        {
            name: "Dashboard",
            to: "dashboard",
            icon: ['far', 'tachometer']
        },
        {
            name: "Inventory",
            icon: ['far', 'inventory'],
            subActivePaths: ["inventory."],
            sub: [
                {
                    name: "Negative Quantities",
                    to: "inventory.negativequantities.index",
                },
                {
                    name: "Variance Report",
                    to: "inventory.variancereport.index",
                },
                {
                    name: "View Inventory",
                    subActivePaths: ["inventory.view."],
                    sub: [
                        {
                            name: 'By Product',
                            to: 'inventory.view.show',
                            params: {
                                view: 'product'
                            },
                        },
                        {
                            name: 'By Bin',
                            to: 'inventory.view.show',
                            params: {
                                view: 'bin'
                            },
                        },
                    ]
                },


            ]
        },
        {
            name: "Reports",
            heading: true,
        },
    ]
}