import router from '../router/router.js';

export default {
    template: `
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center page-title">Manage Pages</h1>
            
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>SN</th>
                        <th>Page Title</th>
                        <th>Page Status</th>
                        <th><a class='btn btn-outline-primary page' href='#/admin/create-page'>Add New</a></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(page, index) in pages" :key="index">
                        <td>{{index+1}}</td>
                        <td>{{page.page_title}}</td>
                        <td>{{page.page_status}}</td>
                        <td width='20%'>
                            <a class='btn btn-outline-warning page' :href="'#/admin/view/'+page.id">View</a>
                            <a class='btn btn-outline-info page' :href="'#/admin/edit/'+page.id">Edit</a>
                            <button class='btn btn-outline-danger delete-page' @click="deletePage(page.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>`,

    data () {
        return {
            pages:[],
        }
    },
    created: function () {
        this.getPages();
    },
    methods: {
        getPages: function(){
            var root = this;

            axios.get('api/admin.php')
                .then(function (response) {
                    if(response.data.status)
                        root.pages = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        deletePage: function(id){
            if(confirm("Are you sure?")){
                var root = this;
                axios.delete('api/delete-page.php?page='+id)
                    .then(function (response) {
                        if(response.data.status){
                            let index = root.pages.findIndex(row => row.id === id);
                            root.pages.splice(index, 1);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                return true;
            }else return false;
        },
    },
    mounted () {
        let user = JSON.parse(localStorage.getItem("user"));
        if(!user){
            router.push({name: 'Login'})
        }
    }

}
