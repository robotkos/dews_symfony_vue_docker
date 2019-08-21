<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <router-link class="nav-item" tag="li" to="/home" active-class="active">
                        <a class="nav-link" v-text="$ml.with('VueJS').get('title')"></a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/posts" active-class="active">
                        <a class="nav-link" v-text="$ml.with('VueJS').get('titlePosts')"></a>
                    </router-link>
                    <router-link class="nav-item" tag="li" to="/users" active-class="active">
                        <a class="nav-link" v-text="$ml.with('VueJS').get('titleUsers')"></a>
                    </router-link>
                    <li class="nav-item" v-if="isAuthenticated">
                        <a class="nav-link" href="/api/security/logout" v-text="$ml.with('VueJS').get('titleLogout')"></a>
                    </li>
                </ul>
                <div class="button-right">
                    <button
                            v-for="lang in $ml.list"
                            :key="lang"
                            @click="$ml.change(lang)"
                            v-text="lang"></button>
                </div>

            </div>
        </nav>

        <router-view></router-view>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'app',
        created() {
            let isAuthenticated = JSON.parse(this.$parent.$el.attributes['data-is-authenticated'].value),
                roles = JSON.parse(this.$parent.$el.attributes['data-roles'].value);

            let payload = {isAuthenticated: isAuthenticated, roles: roles};
            this.$store.dispatch('security/onRefresh', payload);

            axios.interceptors.response.use(undefined, (err) => {
                return new Promise(() => {
                    if (err.response.status === 403) {
                        this.$router.push({path: '/login'})
                    } else if (err.response.status === 500) {
                        document.open();
                        document.write(err.response.data);
                        document.close();
                    }
                    throw err;
                });
            });
        },
        computed: {
            isAuthenticated() {
                return this.$store.getters['security/isAuthenticated']
            },
        },
    }
</script>
