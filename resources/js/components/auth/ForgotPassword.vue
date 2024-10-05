<template>
  <v-app>
    <v-main>
      <v-container fluid fill-height>
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card>
              <v-card-title class="text-center">Forgot Password</v-card-title>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="Email"
                    variant="solo"
                    v-model="email"
                    name="email"
                    id="email"
                    :rules="[(v) => !!v || 'The email field is required.']"
                    :error-messages="validationErrors.email"
                  >
                  </v-text-field>
                  <v-btn color="primary" block @click="sendPasswordResetLink">{{
                    processing ? "Please wait" : "Send Password Reset Link"
                  }}</v-btn>
                  <router-link :to="{ name: 'login' }">Go to Login</router-link>
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
export default {
  data() {
    return {
      email: "",
      validationErrors: {},
      processing: false,
    };
  },
  methods: {
    isValidEmail(value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(value);
    },
    async sendPasswordResetLink() {
      this.processing = true;
      await axios.get("/sanctum/csrf-cookie");
      await axios
        .post("/password/email", { email: this.email })
        .then(({ data }) => {
          this.$toast.open({
            message: data.message,
            type: "success",
            position: "top-right",
          });
        })
        .catch(({ response }) => {
          if (response.status === 422) {
            this.validationErrors = response.data.errors;
            this.$toast.open({
              message: response.data.message,
              type: "error",
              position: "top-right",
            });
          } else {
            this.validationErrors = {};
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
