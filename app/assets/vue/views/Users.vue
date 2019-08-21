<template>
    <div>
        <div class="row col">
            <h1 v-text="$ml.with('VueJS').get('titleUsers')"></h1>
        </div>

        <div class="row col" v-if="canCreateUser">
            <form>
                <div class="form-row">
                    <div class="col-8">
                        <input v-model="email" type="email" class="form-control" placeholder="Email">
                        <input v-model="display_name" type="text" class="form-control" :placeholder="$ml.with('VueJS').get('name')">
                        <input v-model="password" type="password" class="form-control" :placeholder="$ml.with('VueJS').get('pass')">
                        <input v-model="repeat_password" type="password" class="form-control" :placeholder="$ml.with('VueJS').get('repeat_pass')">
                    </div>
                    <div class="col-4">
                        <button @click="createUser()" :disabled="message.length === 0 || isLoading" type="button" class="btn btn-primary" v-text="$ml.with('VueJS').get('createBtn')"></button>
                    </div>
                </div>
            </form>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <error-message :error="error"></error-message>
        </div>

        <div v-else-if="!hasUsers" class="row col">
            No Users!
        </div>

        <div v-else v-for="user in users" class="row col">
            <user :message="user.userName"></user>
        </div>
    </div>
</template>

<script>
    import User from '../components/User';
    import ErrorMessage from '../components/ErrorMessage';

    export default {
        name: 'users',
        components: {
            User,
            ErrorMessage,
        },
        data () {
            return {
                message: '',
                email: '',
                display_name: '',
                password: '',
                repeat_password: '',
            };
        },
        created () {
            this.$store.dispatch('user/fetchUsers');
        },
        computed: {
            isLoading () {
                return this.$store.getters['user/isLoading'];
            },
            hasError () {
                return this.$store.getters['user/hasError'];
            },
            error () {
                return this.$store.getters['user/error'];
            },
            hasUsers () {
                return this.$store.getters['user/hasUsers'];
            },
            users () {
                return this.$store.getters['user/users'];
            },
            canCreateUser () {
                return this.$store.getters['security/hasRole']('ROLE_FOO');
            }
        },
        methods: {
            createUser () {
                this.$store.dispatch('user/createUser', this.$data.message)
                    .then(() => this.$data.message = '')
            },
        },
    }
</script>
