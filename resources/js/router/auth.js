/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
/* Guest Component */

/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
/* Authenticated Component */
const ForgotPassword = () => import('@/components/auth/ForgotPassword.vue')

const ResetPassword = () => import('@/components/auth/ResetPassword.vue')


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "forgot-password",
        path: "/forgot-password",
        component: ForgotPassword,
        meta: {
            middleware: "guest",
            title: `ForgotPassword`
        }
    },
    {
        name: "password-reset",
        path: "/password/reset/:token",
        component: ResetPassword,
        meta: {
            middleware: "guest",
            title: `ResetPassword`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: Dashboard,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            }
        ]
    }
]

export default routes