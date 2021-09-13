export default {
    template: `
    <div class="row">
        <div class="col-md-2">
            <ul class="custom-nav">
                <li v-for="(item, index) in pages" :key="index" v-on:click="getPage(item.id)">
                    <a href="#">Page {{index +1}}</a></li>
            </ul>
        </div>

        <div class="col-md-10">
            <div class="card" v-if="!home">
                <div class="card-header">
                    <h1>{{page.page_title}}</h1>
                </div>
                <div class="card-body">
                    <p>{{page.page_content}}</p>
                </div>
            </div>

            <div v-else>
                <div class="alert alert-warning" v-if="!pages.length">
                    <p class="text-center">No page found. Contact with administrator to create pages</p>
                </div>
                <h1  v-else class="page-title text-center"> Home Page</h1>
            </div>
        </div>
    </div>`,

    data () {
        return {
            pages: [],
            page: [],
            home: true
        }
    },
    created: function () {
        if(this.$route.params.pageId){
            this.getPage(this.$route.params.pageId);
        }

        this.getPages()
    },

    methods: {
        getPages: function(){
            var root = this;

            axios.get('api/index.php')
                .then(function (response) {
                    if(response.data.status)
                        root.pages = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getPage: function(id){
            var root = this;
            this.home = false;
            axios.get('api/index.php?page='+id)
                .then(function (response) {
                    if(response.data.status)
                        root.page = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }
}
