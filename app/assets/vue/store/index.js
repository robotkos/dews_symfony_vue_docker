import Vue from 'vue';
import Vuex from 'vuex';
import SecurityModule from './security';
import PostModule from './post';
import UserModule from './user';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        security: SecurityModule,
        post: PostModule,
        user: UserModule,
    },
});
