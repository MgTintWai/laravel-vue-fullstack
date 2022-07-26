import { mapGetters } from "vuex";
export default{
    data() {
        return {

        }
    },
    methods: {
        async callApi(method,url,dataObj) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.reponse;
            }
        },
        i(des,title="Hey") {
            this.$Notice.info({
                title: title,
                desc: des
            });
        },
        s(des, title="Great") {
            this.$Notice.success({
                title: title,
                desc: des
            });
        },
        w(des,title="Oops") {
            this.$Notice.warning({
                title: title,
                desc: des
            });
        },
        e(des,title="Oops!") {
            this.$Notice.error({
                title: title,
                desc: des
            });
        },
        swr(des="Something want wrong, Please try again",title="Oops") {
            this.$Notice.error({
                title: title,
                desc: des
            });
        },
        checkUserPermission(key){
            if(!this.userPermission) return true
            let isPermitted = false
            for(let d of this.userPermission){
                if(this.$route.name == d.name){
                    if(d[key]){
                        isPermitted = true
                        break;
                    }else {
                        break;
                    }
                }
            }
            return isPermitted
        }
    },
    computed: {
        ...mapGetters({
            'userPermission': 'getUserPermission'
        }),
        isReadPermitted(){
            return this.checkUserPermission('read')
        },
        isWritePermitted(){
            return this.checkUserPermission('write')
        },
        isUpdatePermitted(){
            return this.checkUserPermission('update')
        },
        isDeletePermitted(){
            return this.checkUserPermission('delete')
        },
    }

}
