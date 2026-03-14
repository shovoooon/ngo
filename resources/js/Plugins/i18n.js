export const i18nVue = {
    install: (app) => {
        app.config.globalProperties.$t = (key) => {
            // Get translations from standard Inertia page props
            const translations = app.config.globalProperties.$page?.props?.translations || {};
            return translations[key] || key;
        };
    }
};
