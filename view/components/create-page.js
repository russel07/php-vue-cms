import router from '../router/router.js';

export default {
    template: `
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center page-title">Add new page</h1>
        </div>

        <div class="col-md-8 offset-2">
            <div class="card card-body">
                <form  class="p-5">
                   <div class="form-group">
                        <label for="page_title">Page Title:</label>
                        <input type="text" name="page_title" class="form-control" id="page_title" v-model="page_title" maxlength="200">
                   </div>                      
                    
                   <div class="form-group">
                        <label for="page_content">Page Content:</label>

                        <div id="toolbar">
                            <!-- Add buttons as you would before -->
                            <button class="ql-bold"></button>
                            <button class="ql-italic"></button>
                            <button class="ql-list" value="ordered"></button>
                            <button class="ql-list" value="bullet"></button>
                            <button class="ql-link"></button>
                            <button class="ql-image"></button>
                        </div>
                        <div id="editor"></div>
                        <input type="hidden" class="form-control" name="page_content" id="page_content" v-model="page_content">
                   </div>
    
                   <div class="form-group text-center">
                        <button type="button"  id="create_page" class="btn btn-outline-success" @click="create_page">Create</button>
                   </div>
                </form>
            </div>
            
        </div>
    </div>`,

    data () {
        return {
            page_title:'',
            page_content: '',
            error: ''
        }
    },
    created: function () {

    },
    methods: {
        create_page: function(){
            this.page_content = $('#editor .ql-editor').eq(0).html();
            let data = {
                page_title: this.page_title,
                page_content: this.page_content
            }

            axios.post('api/create-page.php', data)
                .then(function (response) {
                    if(response.status) {
                        router.push({name: 'Admin'})
                    }
                    else alert(response.data.error);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getQuill(){
            var quill2 = new Quill('#editor', {
                placeholder: 'Compose your order details',
                theme: 'snow',
                modules: {
                    toolbar: '#toolbar'
                }
            });
        }
    },
    mounted(){
        let user = JSON.parse(localStorage.getItem("user"));
        if(!user){
            router.push({name: 'Login'})
        }
        this.getQuill();
    }

}
