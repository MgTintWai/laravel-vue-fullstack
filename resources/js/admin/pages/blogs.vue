<template>
    <div>
        <div class="content">
			<div class="container-fluid">

				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Blogs <Button @click="$router.push('/createBlog')" ><Icon type="md-add"/> Create Blog</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Title</th>
								<th>Categories</th>
								<th>Tags</th>
								<th>Views</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->

								<!-- ITEMS -->
							<tr v-for="(blog, i) in blogs" :key="i" v-if="blogs.length">
								<td>{{blog.id}}</td>
								<td class="_table_name">{{blog.title}}</td>
								<td><span class="_table_name" v-for="(c,i) in blog.cat" v-if="blog.cat.length"><Tag class="border">{{c.categoryName}}</Tag></span></td>
								<td><span class="_table_name" v-for="(t,i) in blog.tag" v-if="blog.tag.length"><Tag class="border">{{t.tagName}}</Tag></span></td>
								<td>{{blog.views}}</td>
								<td>
									<Button type="info" size="small" >Visit Blog</Button>
									<Button type="info" size="small"  @click="$router.push(`/editblog/${blog.id}`)" v-if="isUpdatePermitted">Edit</Button>
									<Button type="error" size="small" @click="showDeletingModal(blog,i)" :loading="isDeleting" v-if="isUpdatePermitted">Delete</Button>

								</td>
							</tr>
								<!-- ITEMS -->
					</table>
                     <Page :total="pageInfo.total"  :current="pageInfo.current_page" @on-change="getBlogData" :page-size="parseInt(pageInfo.per_page)"  v-if="pageInfo" />
					</div>
				</div>
				<deleteModal></deleteModal>

			</div>
		</div>
    </div>
</template>

<script>

import deleteModal from '../components/deleteModal.vue'
import { mapGetters } from 'vuex'
export default {
	data(){
		return {
			isAdding: false,
			blogs: [],
			index: -1 ,
			isDeleting: false,
			showDeleteModal: false,
			deleteItem: {},
			deletingIndex: -1,
            total: 1,
            pageInfo: null

		}
	},
	methods: {
		showDeletingModal(blog,i){
            this.deletingIndex = i
			const deleteModalObj = {
            showDeleteModal:true,
            deleteUrl: 'app/delete_blog',
            data: {id: blog.id},
            deletingIndex: i,
            isDeleted: false,
            msg: 'Are you sure want to delete this Blog?',
            successMsg: 'Blog has been deleted successfully',
        }
		this.$store.commit('setDeletingModalObj',deleteModalObj)
			// this.deleteItem = tag
			// this.deletingIndex = i
			// this.showDeleteModal = true

		},
        async getBlogData(page =1){
            const res = await this.callApi('get', `app/blogsdata?page=${page}&${this.total}`)
            if(res.status == 200){
				this.blogs = res.data.data
                this.pageInfo = res.data
			}else {
				this.swr()
			}
        }

	},
	async created() {
			this.getBlogData();

		},
		components : {
			deleteModal
		},
		computed : {
			...mapGetters(['getDeleteModalObj'])
		},
		watch: {
			getDeleteModalObj(obj){
				if(obj.isDeleted){
					this.blogs.splice(this.deletingIndex,1)
				}
			}
		}

}
</script>
