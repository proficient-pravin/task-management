import { createStore } from 'vuex';
import createPersistedState from 'vuex-persistedstate';
import auth from '@/store/auth';
import categories from '@/store/categories';
import tasks from '@/store/tasks';

const store = createStore({
    plugins: [
        createPersistedState(),
    ],
    modules: {
        auth,
        categories, // Ensure categories module is included here
        tasks, // Ensure categories module is included here
    },
});

export default store;
