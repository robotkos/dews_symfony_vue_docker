import Vue from 'vue';
import App from './App';
import router from './router';
import store from './store';
import './store/ml';

Vue.config.productionTip = false;

new Vue({
    template: '<App/>',
    components: { App },
    router,
    store,
    render: h => h(App)
}).$mount('#app');
