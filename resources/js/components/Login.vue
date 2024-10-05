<template>
  <v-app>
    <v-main>
      <v-container fluid fill-height>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card>
              <v-card-title class="text-center">Login</v-card-title>
              <v-card-text>
                <v-form v-model="formValid">
                  <v-text-field
                    label="Email"
                    variant="solo"
                    v-model="auth.email"
                    name="email"
                    id="email"
                    :rules="[
                      (v) => !!v || 'The email field is required.',
                      (v) => isValidEmail(v) || 'Invalid email format',
                    ]"
                    :error-messages="validationErrors.email"
                  ></v-text-field>
                  
                  <v-text-field
                    label="Password"
                    variant="solo"
                    v-model="auth.password"
                    type="password"
                    name="password"
                    id="password"
                    :rules="[(v) => !!v || 'The password field is required.']"
                    :error-messages="validationErrors.password"
                  ></v-text-field>

                  <v-btn color="primary" block @click="login">Login</v-btn>
                  <router-link :to="{ name: 'register' }"
                    >Register</router-link
                  >
                </v-form>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "login",
  components: {},
  data() {
    return {
      formValid: false,
      auth: {
        email: "",
        password: "",
      },
      validationErrors: {},
      processing: false,
    };
  },
  methods: {
    ...mapActions({
      signIn: "auth/login",
    }),
    isValidEmail(value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(value);
    },
    async login() {
      this.processing = true;
      await axios.get("/sanctum/csrf-cookie");
      await axios
        .post("/login", this.auth)
        .then(({ data }) => {
          this.signIn();
        })
        .catch(({ response }) => {
          this.processing = false;
          if (response.status === 422) {
            this.validationErrors = response.data.errors;
            this.$toast.open({
              message: response.data.message,
              type: "error",
              position: "top-right",
            });
          } else {
            this.$toast.open({
              message: response.data.message,
              type: "error",
              position: "top-right",
            });
          }
        })
        .finally(() => {
          this.processing = false;
        });
    },
  },
};
</script>