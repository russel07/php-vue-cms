import router from '../router/router.js';
import EventBus from './event-bus.js';

export default {
    template: `
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center page-title">Login to explore</h1>
        </div>

        <div class="col-md-8 offset-2">
            <div class="card card-body">
                <form  method="POST" class="p-5" id="login_form">
                        <div class="form-group">
                            <label for="admin_email">Email:</label>
                            <input type="email" name="email" class="form-control" id="admin_email" v-model="email">
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_password">Admin Password:</label>
                            <input type="password" name="password" class="form-control" id="admin_password"v-model="password">
                        </div>
        
                        <div class="form-group text-center">
                            <button type="button"  id="login" class="btn btn-outline-success" @click="login">Login</button>
                        </div>
                </form>
            </div>
            
        </div>
    </div>`,

    data () {
        return {
            email:'',
            password: '',
            error: ''
        }
    },
    created: function () {
        console.log("login page");
    },
    methods: {
        login: function(){
            let data = {
                email: this.email,
                password: this.password
            }

            var root = this;
            axios.post('api/login.php', data)
                .then(function (response) {
                    if(response.data.status) {
                        EventBus.$emit('IS_LOGGEDIN', response.data.user);
                        localStorage.setItem('user', JSON.stringify(response.data.user));
                        router.push({name: 'Admin'})
                    }
                    else alert(response.data.error);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    mounted () {
        let user = JSON.parse(localStorage.getItem("user"));
        if(user){
            router.push({name: 'Admin'})
        }
    }
}
