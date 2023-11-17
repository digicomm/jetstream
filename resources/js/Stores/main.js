import {defineStore} from 'pinia';

export const useMainStore = defineStore({
    id: 'main',
    state: () => (({
        app: {
            darkMode: false,
            darkModeSystem: true,
        }
    })),
    getters: {
        getDarkMode(state) {
            return state.app.darkMode
        },
        getDarkModeSystem(state) {
            return state.app.darkModeSystem
        }

    },
    actions: {
        setDarkMode(payload) {
            if (payload === true) {
                this.app.darkMode = true
                document.documentElement.dataset.mode = 'dark'
            } else if (payload === false) {
                this.app.darkMode = false
                document.documentElement.dataset.mode = 'light'
            }
        },
        setDarkModeSystem(payload) {
            if (payload === true) {
                this.app.darkModeSystem = true

                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    this.app.darkMode = true
                } else {
                    this.app.darkMode = false
                }
            } else if (payload === false) {
                this.app.darkModeSystem = false
            }
        }
    }
})