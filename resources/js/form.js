Nova.booting((Vue, router) => {
    Vue.component('PageForm-create', require('./components/form/Create'))
    Vue.component('PageForm-edit', require('./components/form/Edit'))
})