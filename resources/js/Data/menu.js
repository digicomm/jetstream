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
                        {
                            name: 'By Aisle',
                            to: 'inventory.view.show',
                            params: {
                                view: 'aisle'
                            },
                        },
                        {
                            name: 'Empty Bins',
                            to: 'inventory.view.show',
                            params: {
                                view: 'empty'
                            },
                        },
                    ]
                },


            ]
        },
        {
            name: "Inventory 2",
            icon: ['far', 'inventory'],
            subActivePaths: ["inventorys."],
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
                        {
                            name: 'By Aisle',
                            to: 'inventory.view.show',
                            params: {
                                view: 'aisle'
                            },
                        },
                        {
                            name: 'Empty Bins',
                            to: 'inventory.view.show',
                            params: {
                                view: 'empty'
                            },
                        },
                    ]
                },


            ]
        },
        {
            name: "Test Item",
            to: "inventory.variancereport.index",
            icon: ['far', 'tachometer']
        },
        {
            name: "Reports",
            heading: true,
        },
    ]
}