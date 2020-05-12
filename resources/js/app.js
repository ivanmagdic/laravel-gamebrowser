import '@fortawesome/fontawesome-free/css/all.css'
import {InertiaApp} from '@inertiajs/inertia-vue'
import Vue from 'vue'
import Vuetify from 'vuetify'

//Vue.config.productionTip = false
Vue.mixin({methods: {route: window.route}})
Vue.use(InertiaApp)
Vue.use(Vuetify,{
    iconfont: 'fa'
});

const app = document.getElementById('app')

new Vue({
    vuetify: new Vuetify(),
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => import(`./Pages/${name}`).then(module => module.default),
        },
    })
}).$mount(app)
