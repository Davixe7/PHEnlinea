import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import { Quasar, Notify } from 'quasar'
import quasarLang from 'quasar/lang/es'
import quasarIconSet from 'quasar/icon-set/material-symbols-outlined'

// Import icon libraries
import '@quasar/extras/roboto-font/roboto-font.css'
import '@quasar/extras/material-icons/material-icons.css'
import '@quasar/extras/material-symbols-outlined/material-symbols-outlined.css'
import 'quasar/src/css/index.sass'

import Layout from './Pages/Root/Layout.vue'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/Public/**/*.vue', { eager: true })
    return pages[`./Pages/Public/${name}.vue`]
    page.default.layout =  page.default.layout || Layout
    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Quasar, {
        config: {
          notify: {position: 'bottom-right'}
        },
        plugins: {Notify}, // import Quasar plugins and add here
        lang: quasarLang,
        iconSet: quasarIconSet,
      })
      .mount(el)
  },
})