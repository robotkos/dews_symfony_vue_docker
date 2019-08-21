import Vue from 'vue'
import { MLInstaller, MLCreate, MLanguage } from 'vue-multilanguage'

Vue.use(MLInstaller);

export default new MLCreate({
    initial: 'en',
    save: process.env.NODE_ENV,
    // save: process.env.NODE_ENV === 'production',
    languages: [
        new MLanguage('en').create({
            title: 'Home',
            titlePosts: 'Posts',
            titleLogout: 'Logout',
            titleUsers: 'Users',
            createBtn: 'Create',
            name: 'Display name',
            pass: 'Password',
            repeat_pass: 'Repeat password',
        }),

        new MLanguage('ru').create({
            title: 'Домой',
            titlePosts: 'Публикации',
            titleLogout: 'Выход',
            titleUsers: 'Пользователи',
            createBtn: 'Создать',
            name: 'Имя',
            pass: 'Пароль',
            repeat_pass: 'Повторите пароль',
        })
    ]
})
