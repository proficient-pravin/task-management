const Task = () => import('@/components/Task.vue')
const Category = () => import('@/components/Category.vue')


const routes = [
    {
        name: "tasks",
        path: "/tasks",
        component: Task,
        meta: {
            middleware: "auth",
            title: `Task Management`
        }
    },
    {
        name: "categories",
        path: "/categories",
        component: Category,
        meta: {
            middleware: "auth",
            title: `Category Management`
        }
    },
]

export default routes