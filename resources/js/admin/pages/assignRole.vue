<template>
    <div>
       <div class="content">
			<div class="container-fluid">

				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Role Management
                        <Select v-model="data.id" placeholder="UserType" style="width: 300px" @on-change="changeAdmin">
							<Option :value="r.id" v-for="(r,i) in roles" :key="i" v-if="roles.length">{{r.roleName}}</Option>
							<!-- <Option value="Editor">Editor</Option> -->
						</Select>
                    </p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>Resources Name</th>
								<th>Read</th>
								<th>Write</th>
								<th>Update</th>
								<th>Delete</th>
							</tr>
								<!-- TABLE TITLE -->

								<!-- ITEMS -->
							<tr v-for="(r,i) in resources" :key="i">
								<td>{{r.resourceName}}</td>
								<td><Checkbox v-model="r.read"></Checkbox></td>
								<td><Checkbox v-model="r.write"></Checkbox></td>
								<td><Checkbox v-model="r.update"></Checkbox></td>
								<td><Checkbox v-model="r.delete"></Checkbox></td>
							</tr>
                            <div class="center_button" style="margin-top: 10px">
                                <Button type="primary" :loading = "isSending" :display = "isSending" @click="assignRoles">Assign</Button>
                            </div>
								<!-- ITEMS -->
					</table>
					</div>
				</div>
			</div>
		</div>
    </div>
</template>

<script>
export default {
	data(){
		return {
			data: {
				roleName: '',
                id: null
			},
            isSending: false,
            roles: [],
            resources: [
                        { resourceName: 'Home', read: false, write: false, update: false, delete: false, name: '/',},
                        { resourceName: 'Tags', read: false, write: false, update: false, delete: false, name: 'tags',},
                        { resourceName: 'Category', read: false, write: false, update: false, delete: false, name: 'category',},
                        { resourceName: 'Create blogs', read: false, write: false, update: false, delete: false, name: 'createBlog',},
                        { resourceName: 'Blogs', read: false, write: false, update: false, delete: false, name: 'blogs',},
                        { resourceName: 'Admin users', read: false, write: false, update: false, delete: false, name: 'adminusers',},
                        { resourceName: 'Role', read: false, write: false, update: false, delete: false, name: 'role',},
                        { resourceName: 'Assign role', read: false, write: false, update: false, delete: false, name: 'assignRole',},

                        ],
        resourcesFresh: [
                        { resourceName: 'Home', read: false, write: false, update: false, delete: false, name: '/',},
                        { resourceName: 'Tags', read: false, write: false, update: false, delete: false, name: 'tags',},
                        { resourceName: 'Category', read: false, write: false, update: false, delete: false, name: 'category',},
                        { resourceName: 'Create blogs', read: false, write: false, update: false, delete: false, name: 'createBlog',},
                        { resourceName: 'Blogs', read: false, write: false, update: false, delete: false, name: 'blogs',},
                        { resourceName: 'Admin users', read: false, write: false, update: false, delete: false, name: 'adminusers',},
                        { resourceName: 'Role', read: false, write: false, update: false, delete: false, name: 'role',},
                        { resourceName: 'Assign role', read: false, write: false, update: false, delete: false, name: 'assignRole',},

                        ],

		};
	},
	methods: {
        async assignRoles(){
            let data = JSON.stringify(this.resources)
            const res = await this.callApi('post', 'app/assign_role', {'permission' : data,id: this.data.id})
            if(res.status == 200){
                this.s('Role has been assigned successfuly!')
                let index = this.roles.findIndex(role => role.id == this.data.id)
                this.roles[index].permission = data
            }else {
                this.swr()
            }
        },
        changeAdmin(){
            let index = this.roles.findIndex(role => role.id == this.data.id)
            let permission = this.roles[index].permission
            if(!permission){
                this.resources = this.resourcesFresh
            } else{
                this.resources = JSON.parse(permission)
            }
        }
    },
	async created() {
			const res = await this.callApi('get','app/get_role')

			this.roles = res.data
			if(res.status == 200){
                this.data.id = res.data[0].id
                if(res.data.length){
                    if(res.data[0].permission){
                        this.resources = JSON.parse(res.data[0].permission)
                        // this.resources = this.resourcesFresh
                    }
                }
			}else {
				this.swr()
			}
		},
	}

</script>
