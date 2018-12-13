Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'PageTool',
            path: '/PageTool',
            component: require('./components/Tool'),
        },
    ])
})
