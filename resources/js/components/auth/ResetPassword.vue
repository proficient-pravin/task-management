<template>
    <v-container class="">
    <div class="col-12" v-if="Object.keys(validationErrors).length > 0">
        <div class="alert alert-danger">
            <ul class="mb-0">
                <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
            </ul>
        </div>
    </div>
    <v-row no-gutters >
      <v-col cols="12" sm="4" md="4"></v-col>
      <v-col cols="12" sm="4" md="4">
        <v-card text="Forgot Password">
                <input type="hidden" v-model="form.token" name="token"/>
                <v-text-field
                    label="Email"
                    variant="solo"
                    v-model="form.email"
                    name="email"
                    id="email"
                    :rules="[v => !!v || 'Please enter a Email']"
                    readonly
                    >
                </v-text-field>
                <v-text-field
                    label="Password"
                    variant="solo"
                    v-model="form.password"
                    name="password"
                    id="password"
                    :rules="[v => !!v || 'Please enter a Password']"
                    >
                </v-text-field>
                <v-text-field
                    label="Password Confirmation" 
                    variant="solo"
                    v-model="form.password_confirmation"
                    name="password"
                    id="password"
                    :rules="[v => !!v || 'Please enter a Password Confirmation']"
                    >
                </v-text-field>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="resetPassword">{{ processing ? "Please wait" : "Reset Password" }}</v-btn>
                    <router-link :to="{name:'login'}">Go to Login</router-link>
                </v-card-actions>
        </v-card>
      </v-col>
      <v-col cols="12" sm="4" md="4"></v-col>
    </v-row>
  </v-container>
</template>

<script>
    export default {
        data(){
            return {
                form : {
                    token : '',
                    email : '',
                    password : '',
                    password_confirmation : '',
                },
                validationErrors:{},
                processing:false
            }
        },
        created(){
            this.form.token = this.$route.params.token
            this.form.email = this.$route.query.email
        },
        methods:{
            isValidEmail(value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(value);
            },
            async resetPassword(){
                this.processing = true
                await axios.get('/sanctum/csrf-cookie')
                await axios.post('/password/reset',this.form).then(({data})=>{
                    console.log(data);
                    
                    }).catch(({response})=>{
                    if(response.status === 422){
                        this.validationErrors = response.data.errors
                    }else{
                        this.validationErrors = {}
                        alert(response.data.message)
                    }
                }).finally(()=>{
                    this.processing = false
                })
            },
        }
    }
</script>
